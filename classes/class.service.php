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
    public function getUser ($name)
    {
        $return = null;
        Self::each($this->user(), function ($User) use (&$return, $name) {
            if (strtolower($User->name()->get()) == strtolower($name)) {
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
    public function getActiveUsers (Date $dateCurrent = null)
    {
        $dateCurrent = is_null($dateCurrent) ? $this->getStart() : $dateCurrent;
        $totalUser = [];

        Self::each($this->user(), function ($User) use (&$totalUser, $dateCurrent)
        {
            Self::each($User->interval(), function ($Interval) use (&$totalUser, $dateCurrent, $User)
            {

                if ($Interval->between($dateCurrent)) {
                    $totalUser[] = $User;
                }
            });
        });

        return $totalUser;
    }

    /**
     * get total actives users
     * @param  Date $dateCurrent current date where you search
     * @return int               total user
     */
    public function getActiveUsersNumber (Date $dateCurrent = null) {
        return count($this->getActiveUsers($dateCurrent));
    }

    /**
     * get
     * @param  Date  $dateCurrent
     * @return object|null tarif
     */
    public function getActiveTarif ($dateCurrent) {
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
}
