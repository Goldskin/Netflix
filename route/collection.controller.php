<?php
require_once ROUTE . 'route.controller.php';
/**
 * all the route collection
 */
class RouteCollection
{
    private $route = [];
    private $url;
    function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * search url
     * @param  string $url url given to match
     * @return array  param and Route
     */
    private function search ($url) {
        // sort all url
        usort($this->route ,'Self::sort');

        foreach ($this->route as $key => $route) {
            $preg = preg_quote($route->path, '/').'(?:\/|$)';

            // check for a match with url
            if (preg_match_all("/".$preg."/", $url, $test)) {
                // get param
                $param = substr($url, (strlen($route->path) +1));
                $paramExploded = array_filter(explode('/', $param), function ($val) {
                    return !empty($val);
                });

                return [
                    $paramExploded,
                    $route
                ];
            }
        }
        throw new Exception('No route 404');
    }

    /**
     * sort chemmin
     */
    static private function sort($a, $b){
        return strlen($b->path) - strlen($b->path);
    }

    /**
     * add new route
     * @param Route $Route
     */
    public function add (Route $Route) {
        $this->route[] = $Route;
        return $this;
    }

    /**
     * redirect to new view (where all the magic happens)
     * @return mixed
     */
    public function redirect () {
        $result     = $this->search($this->url);
        $controller = $result[1]->getClass();
        $action     = $result[1]->getMethod();

        if (empty($controller)) {
            throw new Exception('No controller');
        }

        if (empty($action)) {
            throw new Exception('No action');
        }

        $path = CONTROLLERS_ROOT . strtolower($controller) . '.controller.php';

        if (file_exists($path)) {
            $classCalled = $controller . 'Controller';


            require_once $path;


            $controller = new $classCalled ($controller);

            if (method_exists($controller, 'index')) {
                return call_user_func_array([$controller, $action], $result[0]);
            } else {
                throw new Exception('No method 404');
           }
        } else {
            throw new Exception('No file 404');
        }
    }
}
