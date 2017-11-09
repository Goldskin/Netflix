<?php

require_once 'classes/class.user.php';
require_once 'classes/class.price.php';
require_once 'classes/class.tarif.php';
require_once 'classes/class.interval.php';
require_once 'classes/class.payment.php';

const classes = [
    'interval',
    'payment',
    'price',
    'tarif',
    'user'
];

foreach (classes as $class) {
    require_once "classes/class.$class.php";
}

class Main
{



    public function __call ($method, $param = null)
    {

        // $method = $this->getCalledMethod();
        $className = array_map('ucfirst', array_filter(classes, function ($a) use ($method) {
            return strtolower($method) == $a;
        }));

        if (is_array($className)) {
            $className = array_values($className)[0];
        }
        return $this->call($className, $method, $param);
    }

    private function getCalledMethod () {
        $trace  = debug_backtrace();
        $caller = $trace[1];
        return $caller['function'];
    }

    private function call($class, $method, $param) {
        if (is_object($param) && get_class($param) == $class) {
            $Obj = $param;
        } else {

            $Obj = new $class();
        }

        if (isset($this->$method) && is_array($this->$method)) {
            $this->$method[] = $Obj;
        } else {
            $this->$method = $Obj;
        }

        return $this;
    }

    public function date ($param, $var = 'date')
    {
        $this->call('DateTime', $var, $param);
        return $this;
    }

}
