<?php

class Date extends DateTime
{
    public $id;
    public $type = 'Date';

    function __construct($time = "now", $timezone = null) {
        $this->id = Main::storeId($this, Main::newId());
        parent::__construct($time, $timezone);
    }
}
