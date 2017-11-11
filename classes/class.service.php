<?php

class Service extends Main
{

    /**
     * get the befinning
     * @return Date
     */
    public function getStart () {

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
    public function getUser ($name) {
        foreach ($this->user() as $key => $user) {
            if (strtolower($user->name()) == strtolower($name)) {
                return $user;
            }
        }
    }
}
