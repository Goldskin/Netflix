<?php

class Service extends Main
{
    public function getStart () {
        foreach ($this->user() as $key => $User) {
            // $Intervals = $User->interval();
            
            if (is_array($User->interval())) {
                foreach ($User->interval() as $Interval) {
                    
                    if (!isset($earliest) || $Interval->getDays() > $earliest->getDays()) {
                        $earliest = $Interval;
                    }
                    
                }
            } else if (!isset($earliest) || $User->interval()->getDays() > $earliest->getDays()) {
                $earliest = $User->interval();
            }
            
        }
        
        return $earliest->start();
    }
}
