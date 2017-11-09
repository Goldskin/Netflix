<?php /**
 *
 */
class Interval extends Main
{
    protected $start = null;
    protected $end = null;


    function start ($date)
    {
        return $this->date($date, 'start');
    }

    function end ($date)
    {
        return $this->date($date, 'end');
    }


}
