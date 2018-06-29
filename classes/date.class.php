<?php

class Date extends DateTime
{
    public $id;
    public $type = 'date';

    function __construct($time = "now", $timezone = null) {
        $this->id = Main::storeId($this, Main::newId());
        parent::__construct($time, $timezone);
    }
}
