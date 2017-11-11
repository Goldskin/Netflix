<?php

const classes = [
    'interval',
    'payment',
    'price',
    'tarif',
    'user',
    'date',
    'service',
    'bill',
    'name',
    'advance',
    'admin',
    'unpayed'
];

foreach (classes as $class) {
    require_once "classes/class.$class.php";
}

class Main
{

    function __construct($param = null) {
        if($param != null && !is_object($param)) {
            $this->set($param);
        }
    }

    /**
     * Get method called
     * @param  string $method method name
     * @param  array $param  all the params
     * @return mixed
     */
    public function __call ($method, $param)
    {
        $className = array_map('ucfirst', array_filter(classes, function ($a) use ($method) {
            return strtolower($method) == $a;
        }));

        $className = self::arrayOut($className);
        $param = self::arrayOut($param);

        return $this->call($className, $method, $param);
    }


    /**
     * if the array contains one element, extract it
     * @param  mixed $value parametres
     * @return mixed        cleaned parametres
     */
    public static function arrayOut($value)
    {
        if (is_array($value) && count($value) == 1) {
            $value = array_values($value)[0];
        }

        return $value;
    }

    /**
     * set value
     * @param mixed $value
     * @param string|null $var
     */
    public function set ($value, $var = null)
    {
        $var = $this->className($var);

        if (isset($this->$var) && !is_array($this->$var)) {
            $this->$var = [$this->$var, $value];
        } else if (isset($this->$var) && is_array($this->$var)) {
            $this->$var[] = $value;
        } else {
            $this->$var = $value;
        }

        return $this;
    }

    /**
     * get dynamic value from class
     * @return string
     */
    public function get ($var = null)
    {
        $var = $this->className($var);

        if (isset($this->$var)) {
            return $this->$var;
        } else {
            return null;
        }
    }

    /**
     * get called className if $value is null
     * @param  mixed $value called clas name
     * @return string       class name
     */
    protected function className ($value = null)
    {
        return is_null($value) ? strtolower(get_called_class()) : $value;
    }

    /**
     * call the right class
     * @param  string $class Class
     * @param  string $var   stored name
     * @param  mixed $param params
     * @return mixed
     */
    private function call ($class, $var, $param = null)
    {
        if ($param == null) {
            return $this->get($var);
        }

        if (is_object($param) && get_class($param) == $class) {
            $Obj = $param;
        } else {
            $Obj = new $class($param);
        }

        return $this->set($Obj, $var);
    }

    /**
     * specifique behevior
     * @param  array $param parameters
     * @param  string $var  var name
     */
    public function date ($param = null, $var = 'date')
    {
        if ($param == null) {
            return $this->get($var);
        }
        $this->call('Date', $var, $param);
        return $this;
    }

    /**
     * check all dynamic var
     * @param  Object|array $vars     method name
     * @param  callback $callback
     * @return void
     */
    public static function each($vars, $callback) {
        if (!is_null($vars)) {
            if (is_array($vars)) {
                foreach ($vars as $var) {
                    if (is_callable($callback)) {
                        $callback($var);
                    }
                }
            } else {
                if (is_callable($callback)) {
                    $callback($vars);
                }
            }
        }
    }



}
