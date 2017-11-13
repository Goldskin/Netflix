<?php

const classes = [
    'interval',
    'price',
    'user',
    'date',
    'service'
];

foreach (classes as $class) {
    require_once CLASSES_ROOT . "/class.$class.php";
}

class Main
{

    public static $counter = 0;

    function __construct($param = null)
    {
        if($param != null && !is_object($param)) {
            $this->set($param);
        }
        self::$counter++;
    }

    /**
     * Get method called
     * @param  string $method method name
     * @param  array $param  all the params
     * @return mixed
     */
    public function __call ($method, $param)
    {
        $className = ucfirst(strtolower($method));
        // $className = self::arrayOut($className);
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
            $this->{$var}[] = $value;
        } else {
            $this->$var = $value;
        }

        return $this;
    }

    /**
     * reset data value
     */
    public function reset ()
    {
        $var = $this->className($var);

        if (isset($this->$var) && !is_array($this->$var)) {
            unset($this->$var);
        }

        return $this;
    }

    /**
     * get dynamic value from class
     * @param  string string
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
     * @param  mixed $value called class name
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

        if (is_object($param)) {
            $Obj = $param;
        } else if (class_exists($class)) {
            $Obj = new $class ($param);
        } else {
            $Obj = new Self ($param);
        }

        return $this->set($Obj, $var);
    }

    /**
     * specifique behevior
     * @param  array  $param parameters
     * @param  string $var  var name
     */
    public function date ($param = null, $var = 'date')
    {
        if ($param == null) {
            return $this->get($var);
        }

        return $this->call('Date', $var, $param);
    }

    /**
     * check all dynamic var
     * @param  object|array $vars     method name
     * @param  callback     $callback
     * @return void
     */
    public static function each($vars = null, $callback = null)
    {
        if (!is_null($vars)) {
            if (is_array($vars)) {
                foreach ($vars as $key => $var) {
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
