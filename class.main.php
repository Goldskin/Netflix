<?php

const classes = [
    'interval',
    'payment',
    'price',
    'tarif',
    'user',
    'date',
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
     * @param  [type] $param  [description]
     * @return [type]         [description]
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

    
    public static function arrayOut($value) 
    {
        if (is_array($value)) {
            $value = array_values($value)[0];
        }
        
        return $value;
    }
    
    
    public function set ($value) 
    {
        $var = strtolower(get_called_class());
        $this->$var = $value;
        
        return $this;
    }
    
    public function get () 
    {
        $var = strtolower(get_class());
        return $this->$var;
    }

    private function call ($class, $var, $param)
    {
        
        if (is_object($param) && get_class($param) == $class) {
            $Obj = $param;
        } else {
            $Obj = new $class($param);
        }

        if (isset($this->$var) && is_array($this->$var)) {
             $this->$var[] = $Obj;
        } else {
            $this->$var = $Obj;
        }
        

        return $this;
    }

    public function date ($param, $var = 'date')
    {
        $this->call('Date', $var, $param);
        return $this;
    }

}
