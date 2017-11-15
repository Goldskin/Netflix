<?php
/**
 * space
 */
class Route
{

    protected $param;
    protected $class;
    protected $method;

    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * add path
     * @param  string $path path needed
     * @return object
     */
    public function class ($class)
    {
        $this->class = ucfirst(strtolower($class));
        return $this;
    }

    public function getClass ()
    {
        return $this->class;
    }

    public function getMethod ()
    {
        return $this->method;
    }

    /**
     * add param
     * @param  string $path path needed
     * @return object
     */
    public function param ($param)
    {
        $this->param = $param;
        return $this;
    }

    /**
     * add param
     * @param  string $path path needed
     * @return object
     */
    public function method ($method)
    {
        $this->method = $method;
        return $this;
    }

}
