<?php
require_once ROUTE . 'route.controller.php';
/**
 *
 */
class RouteCollection
{
    private $route = [];
    private $url;
    function __construct($url)
    {
        $this->url = $url;
    }

    private function search ($url) {
        usort($this->route ,'Self::sort');
        foreach ($this->route as $key => $route) {
            if ($url == $route->path){
                return [
                    [],
                    $route
                ];
            } else if (strpos($url, $route->path . '/') !== false) {
                $url = trim($url, '/');
                $param = substr($url, (strlen($route->path) +1));
                $paramExploded = explode('/', $param);

                return [
                    $paramExploded,
                    $route
                ];
            }
        }
        throw new Exception('No route 404');
    }

    static private function sort($a,$b){
        return strlen($b->path)-strlen($b->path);
    }


    public function add (Route $Route) {
        $this->route[] = $Route;
        return $this;
    }

    public function redirect () {
        $result = $this->search($this->url);

        $controller = $result[1]->getClass();
        $action = $result[1]->getMethod();

        $path = CONTROLLERS_ROOT . strtolower($controller) . '.controller.php';

        if (file_exists($path)) {
            require_once $path;
            $controller = new $controller ();
            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $result[0]);
            } else {
                throw new Exception('No method 404');
           }
        } else {
            throw new Exception('No file 404');
        }
    }
}
