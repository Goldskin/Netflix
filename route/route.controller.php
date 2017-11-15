<?php

/**
 * space
 */
class Route
{

    protected $path = [];

    function __construct()
    {
        # code...
    }

    /**
     * add path
     * @param  string $path path needed
     * @return object
     */
    public function add ($path)
    {
        $this->path[] = $path;
        return $this;
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

    static private function sort($a,$b){
        return strlen($b)-strlen($a);
    }



    private function search ($url) {

        usort($this->path,'Self::sort');
        foreach ($this->path as $key => $path) {


            if ($url == $path){
                return [
                    [],
                    $key
                ];
            } else if (strpos($url, $path . '/') !== false) {
                $url = trim($url, '/');
                $param = substr($url, (strlen($path) + 1));
                $paramExploded = explode('/', $param);
                return [
                    $paramExploded,
                    $key
                ];
            }
        }
        throw new Exception('No route 404');
        die;

    }





    public function redirect () {
        $url = $_GET['p'];


        echo '<pre>', var_dump( $this->search($url) ), '</pre>';
        die;
        $controller = ucfirst(strtolower($param[0]));
        $action = isset($param[1]) ? $param[1] : 'index';
        $path = CONTROLLERS_ROOT . strtolower($controller) . '.controller.php';

        if (file_exists($path)) {
            require $path;
            $controller = new $controller ();
            if (method_exists($controller, $action)) {
                unset($param[0]);
                unset($param[1]);
                call_user_func_array([$controller, $action], $param);
            } else {
                throw new Exception('No method 404');
           }
        } else {
            throw new Exception('No file 404');
        }
    }
}
