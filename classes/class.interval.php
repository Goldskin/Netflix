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


    public function getMonth () {
        $start = !isset($this->start) ? new Date () : clone $this->start;
        $end = !isset($this->end) ? new Date () : clone $this->end;

        $date = $start->diff($end);
        $total     = $date->format('%r%m') + $date->format('%r%y') * 12;

        return $total;
    }
}
