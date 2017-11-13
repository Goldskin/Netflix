<?php

class Date extends DateTime
{
    public static $counter = 0;

    function __construct($time = "now", $timezone = null) {
        self::$counter++;
        parent::__construct($time, $timezone);
    }
}
