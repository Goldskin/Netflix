<?php

class Date extends DateTime
{

    function __construct($time = "now", $timezone = null) {
        Main::storeId($this, Main::newId());
        parent::__construct($time, $timezone);
    }
}
