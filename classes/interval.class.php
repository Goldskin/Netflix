<?php
class Interval extends Main
{
    /**
     * Add starting date
     * @param  string|Date $date Starting date
     * return Class
     */
    public function start($date = null)
    {
        return $this->date($date, 'start');
    }

    /**
     * Add ending date
     * @param  string|Date $date Starting date
     * return Class
     */
    public function end($date = null)
    {
        return $this->date($date, 'end');
    }

    /**
     * get month since
     * @return int days number
     */
    public function month()
    {
        $start = !isset($this->start) ? new Date () : clone $this->start;
        $end   = !isset($this->end) ? new Date () : clone $this->end;

        $date  = $start->diff($end);
        $total = $date->format('%r%m') + $date->format('%r%y') * 12;

        return $total;
    }

    /**
     * get days since
     * @return int days number
     */
    public function days()
    {
        $start = !isset($this->start) ? new Date () : clone $this->start;
        $end   = !isset($this->end) ? new Date () : clone $this->end;

        if ($start > $end) {
            return 0;
        }

        $date  = $start->diff($end);
        $total = $date->days;

        return $total;
    }

    /**
     * check if the date is between interval
     * @param  Date     $Date       marked date
     * @param  callback $callback
     * @return boolean
     */
    public function between(Date $Date, $callback = null)
    {
        $started     = $this->start()->format('Ymd') <= $Date->format('Ymd');
        $notFinished = is_null($this->end()) ? true : $this->end()->format('Ymd') > $Date->format('Ymd');

        if ($started && $notFinished) {
            if (is_callable($callback)) {
                $callback($vars);
            }

            return true;
        }

        return false;
    }

}
