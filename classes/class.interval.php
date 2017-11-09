<?php 
class Interval extends Main
{
    /**
     * Add starting date
     * @param  string|Date $date Starting date
     * return Class 
     */
    public function start ($date)
    {
        return $this->date($date, 'start');
    }
    
    /**
     * Add ending date
     * @param  string|Date $date Starting date
     * return Class 
     */
    public function end ($date)
    {
        return $this->date($date, 'end');
    }
}
