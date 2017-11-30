<?php

class Service extends Main
{
    /**
     * get the befinning
     * @return Date
     */
    public function getStart()
    {
        $earliest = null;

        Self::each($this->user(), function ($User) use (&$earliest)
        {
            Self::each($User->interval(), function ($Interval) use (&$earliest)
            {
                if (is_null($earliest) || $Interval->days() > $earliest->days()) {
                    $earliest = $Interval;
                }
            });
        });

        return $earliest->start();
    }

    /**
     * return all actives users at the date
     * @param  Date $dateCurrent
     * @return array User
     */
    public function getActiveUsers(Date $Date = null)
    {
        $Date = is_null($Date) ? new Date () : $Date;
        $totalUser = [];

        Self::each($this->user(), function ($User) use (&$totalUser, $Date)
        {
            Self::each($User->interval(), function ($Interval) use (&$totalUser, $Date, &$User)
            {

                if ($Interval->between($Date)) {
                    $totalUser[] = $User;
                }
            });
        });

        return $totalUser;
    }

    /**
     * get
     * @param  Date  $dateCurrent
     * @return object|null tarif
     */
    public function getActiveTarif($dateCurrent = null)
    {
        $dateCurrent = is_null($dateCurrent) ? new Date ()  : $dateCurrent;
        $currentTarif = null;

        // Calc right price for current month
        Main::each($this->price(), function ($Price) use (&$currentTarif, $dateCurrent)
        {
            Main::each($Price->interval(), function ($Interval) use (&$currentTarif, $Price, $dateCurrent)
            {
                if ($Interval->between($dateCurrent)) {
                    $currentTarif = $Price;
                }
            });
        });

        return $currentTarif;
    }

    /**
     * update service status
     * @return object
     */
    public function update()
    {
        Self::each($this->user(), function ($User)
        {
            $User->update();
        });

        return $this;
    }

    /**
     * generate bills for each user
     * @return mixed
     */
    public function createBills()
    {
        $Start = $this->getStart();
        $Duration = (new Interval ())->start($Start);
        $this->rotation = 0;
        $free = $this->free()->get();

        for ($currentMonth = !is_null($free) ? $free : 0; $currentMonth <= $Duration->month(); $currentMonth++) {

            $Date = new Date ($Start->format('Ymd'));

            // add month
            $Date->modify('+' . $currentMonth . ' month');

            // get current price of service
            $currentTarif = $this->getActiveTarif($Date);

            // get active user
            $Users      = $this->getActiveUsers($Date);
            $totalUser  = count($Users);

            // get split bill
            $bill = $currentTarif->split($totalUser);

            // apply all bills
            $this->applyBill($Users, $bill, $Date);

        }

        return $this->update();
    }

    /**
     * apply bill and rotate for the next month
     * @param  array $Users
     * @param  array $bill
     * @param  Date  $Date
     * @return object
     */

    protected function applyBill($Users, $bill, $Date)
    {
        $totalUser  = count($Users);

        // number of price diff
        $number = count(array_filter($bill, function ($a) use ($bill) {
            return $a != $bill[0];
        }));

        // applying bills
        foreach ($Users as $key => $User) {

            $price = (new Price ())->set($bill[$this->rotation])->date($Date);
            $price->status($this->applyStatus($User, $bill[$this->rotation]));
            $User->bill( $price );

            $this->rotation++;
            if ($this->rotation == $totalUser ) {
                $this->rotation = 0;
            }
        }

        // set for next rotation the number
        if ($this->rotation + $number < $totalUser) {
            $this->rotation += $number;
        } else {
            $this->rotation = $number - ( ($totalUser) - $this->rotation);
        }

        return $this;
    }

    /**
     * get status
     * @param  User $User  user t0 check
     * @param  int $price  Price to sub
     * @return int         status
     */
    public function applyStatus($User, $price)
    {
        // if user is admin, he's always paying
        if ($User->admin()) {
            $return = Price::payed;
        }

        // if user all ready payed, check the current statement of payment
        else if ($User->getAdvanced()) {
            if ($User->getAdvanced()->total() > 0 && $price > $User->getAdvanced()->total()) {
                $return = Price::paying;
            } else if ($User->getAdvanced()->total() < 0 ) {
                $return = Price::unpayed;
            } else {
                $return = Price::payed;
            }
            $User->advanced( (new Price ())->set(-$price) );
        }

        // if user is actif but hasn't payed yet
        else {
            $return = Price::unpayed;
        }

        return $return;
    }

    /**
     * add all tarifs
     * @param  array $tarifs all tarifs
     * @return object
     */
    public function createTarif($tarifs)
    {
        foreach ($tarifs as $Data) {
            $Interval = (new Interval ())->start($Data->start);

            // a tarif does not necessarily have a end
            if (isset($Data->end)) {
                $Interval->end($Data->end);
            }

            $Tarif = (new Price ())->set( $Data->price )->interval( $Interval );
            $this->price($Tarif);
        }

        return $this;
    }

    /**
     * add all users
     * @param  array $user all users
     * @return object
     */
    public function createUser($user)
    {
        foreach ($user as $Data) {
            $User = (new User ())->name($Data->name);

            // add all usages
            if (isset($Data->use)) {
                foreach ($Data->use as $Use) {
                    $Interval = (new Interval ())->start( $Use->start );

                    // if user allready finished the session
                    if (isset($Use->end)) {
                        $Interval->end( $Use->end );
                    }

                    // set session use
                    $User->interval($Interval);
                }
            }

            // check if current user is admin
            if (isset($Data->admin)) {
                $User->admin($Data->admin);
            }

            // add all payments
            if (isset($Data->payed)) {
                foreach ($Data->payed as $Payment) {
                    $User->payment( (new Price ())->set( $Payment->price )->date( $Payment->date ) );
                }
                $User->advanced( (new Price ())->set($User->payment()->total()) );
            }

            // add user
            $this->user($User);
        }

        return $this;
    }

    /**
     * add options to your service
     * @param  array $options options
     * @return object
     */
    public function createOptions($options)
    {
        $options = get_object_vars($options);
        foreach ($options as $key => $value) {
            $this->{$key}($value);
        }
        return $this;
    }
}
