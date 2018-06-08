<?php

const classes = [
    'interval',
    'price',
    'user',
    'date',
    'service',
    'options'
];

foreach (classes as $class) {
    require_once CLASSES_ROOT . "$class.class.php";
}

class Main
{

    public static $counter = 0;
    public static $all = [];

    function __construct()
    {
        $this->setId();
        
        ksort(static::$all);
    }

    private function setId () {
        $this->id = Self::newId(); 
    }

    public static function newId () {
        $id = self::$counter;
        self::$counter++; 
        return $id;
    }

    public static function storeId($value, $id) {
        static::$all[$id] = $value;
        return $id;
    }

    /**
     * Get method called
     * @param  string $method method name
     * @param  array $param  all the params
     * @return mixed
     */
    public function __call($method, $param)
    {
        $className = ucfirst(strtolower($method));
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
     * @param mixed       $value can be anything
     * @param string|null $var   var name
     */
    public function set($value, $var = null)
    {
        // if is the same object, extract value and store it
        if (is_object($value) && get_class($value) == get_called_class()) {
            $id = $value->id;            
        } 

        $var = $this->className($var);
        

        if (is_object($value)) {
            // $this->{$var} = $value->id;
            if (isset($this->$var) && !is_array($this->$var)) {
                $this->{$var} = [$this->$var, $value->id];
            } else if (isset($this->$var) && is_array($this->$var)) {
                $this->{$var}[] = $value->id;
            } else {
                $this->{$var} = $value->id;
            }
        } else {
            $this->value = $value;
        }


        
        Static::storeId($this, $this->id);        

        return $this;
    }

    /**
     * reset data value
     * @return object
     */
    public function reset()
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
        if (isset($this->{$var})) {
            if (is_array($this->{$var})) {
                return array_filter(Main::$all, function ($object) use ($var) {
                    return isset(array_flip($this->{$var})[$object->id]);
                });
            } 
            return static::$all[$this->$var];
        } else if (isset($this->value)) {
            return $this->value;
        }

        return null;
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
     * @param  mixed  $param params
     * @return mixed
     */
    private function call ($class, $var, $param = null)
    {
        
        if ($param == null && $param != 0) {
            return $this->get($var);
        }

        if (is_object($param)) {
            $Obj = $param;
        } else if (class_exists($class)) {
            $Obj = (new $class())->set($param);
        } else {
            $Obj = (new Self())->set($param);
        }

        return $this->set($Obj, $var);
    }

    /**
     * specifique behevior
     * @param array  $param parameters
     * @param string $var  var name
     * @return mixed
     */
    public function date ($param = null, $var = 'date')
    {
        if ($param == null) {
            return $this->get($var);
        }
        if (is_a($param, 'Date')) {
            $date = $param;
        } else {
            $date = new Date($param);
        }

        

        return $this->set($date, $var);
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
        
        /**
     * get object id based on name
     * @param  string $name name
     * @return User
     */
    public function getId($id)
    {        
        if (!$id) {
            throw new Exception('No id');
        } else if (!isset(static::$all[$id])) {
            throw new Exception('No match');
        }

        return static::$all[$id];
    }

    public function getMultipleIds ($ids) {

        if (count($ids) === 1) {
            return $this->getId(array_shift($ids));
        }

        $returnValue = [];
        foreach ($ids as $id) {
            $returnValue[] = $this->getId($id);
        }
        return $returnValue;
    }

    /**
     * get last element
     * @param  string $object
     * @return object
     */
    public function getLast($object = 'user')
    {
        if (is_array($this->{$object})) {
            return Main::$all[end($this->{$object})];
        }
        return Main::$all[$this->{$object}];
    }

    /**
     * get last element
     * @param  string $object
     * @return object
     */
    public function getFirst($object = 'user') {
        if (is_array($this->{$object})) {
            return $this->{$object}[0];
        }
        return $this->{$object};
    }
}
