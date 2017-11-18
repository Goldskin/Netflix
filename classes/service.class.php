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
        $rotation = [];

        for ($currentMonth = 0; $currentMonth <= $Duration->month(); $currentMonth++) {

            $dateCurrent = new Date ($Start->format('Ymd'));

            // add month
            $dateCurrent->modify('+' . $currentMonth . ' month');

            // get current price of service
            $currentTarif = $this->getActiveTarif($dateCurrent);

            // get active user
            $Users      = $this->getActiveUsers($dateCurrent);
            $totalUser  = count($Users);

            // get split bill
            $userRepartition = $currentTarif->split($totalUser);



            // Applying bill
            foreach ($Users as $key => $User) {
                $User->bill( (new Price ())->set($userRepartition[$key])->date($dateCurrent) );
            }

        }

        return $this->update();
    }
}
