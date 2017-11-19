<?php

class Service extends Main
{
    /**
     * get the befinning
     * @return Date
     */
    public function getStart ()
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
     * get user based on name
     * @param  string $name name
     * @return User
     */
    public function getId ($id, $object = 'user')
    {
        $return = null;

        Self::each($this->user(), function ($User) use (&$return, $id) {
            if ($User->id() == $id) {
                $return = $User;
                return;
            }
        });

        return $return;
    }

    /**
     * return all actives users at the date
     * @param  Date $dateCurrent
     * @return array User
     */
    public function getActiveUsers (Date $Date = null)
    {
        $Date = is_null($Date) ? $this->getStart() : $Date;
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
    public function getActiveTarif ($dateCurrent)
    {
        $dateCurrent = is_null($dateCurrent) ? $this->getStart() : $dateCurrent;
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
    public function update ()
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

    protected function applyBill ($Users, $bill, $Date) {

        $totalUser  = count($Users);

        // number of price diff
        $number = count(array_filter($bill, function ($a) use ($bill) {
            return $a != $bill[0];
        }));

        // applying bills
        foreach ($Users as $key => $User) {
            $User->bill( (new Price ())->set($bill[$this->rotation])->date($Date) );
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
     * add all tarifs
     * @param  array $tarifs all tarifs
     * @return object
     */
    public function createTarif ($tarifs) {
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
    public function createUser ($user) {
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
    public function createOptions ($options) {
        $options = get_object_vars($options);
        foreach ($options as $key => $value) {
            $this->{$key}($value);
        }
        return $this;
    }
}
