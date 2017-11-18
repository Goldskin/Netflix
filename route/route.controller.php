<?php
/**
 * space
 */
class Route
{

    protected $param = 0;
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
    public function controller ($class)
    {
        $this->class = ucfirst(strtolower($class));
        return $this;
    }

    /**
     * get class
     * @return string
     */
    public function getController ()
    {
        return $this->class;
    }

    /**
     * get method
     * @return string
     */
    public function getAction ()
    {
        return $this->method;
    }

    /**
     * get method
     * @return string
     */
    public function getParam ()
    {
        return $this->param;
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
    public function action ($method)
    {
        $this->method = $method;
        return $this;
    }

}
