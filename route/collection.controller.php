<?php
require_once ROUTE . 'route.controller.php';
require_once ROUTE . 'core.controller.php';
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
    private function search ($url)
    {
        // sort all url
        usort($this->route ,'Self::sort');

        foreach ($this->route as $key => $route) {

            $preg = preg_quote($route->path, '/').'(?:\/|$)';

            // check for a match with url
            if (
                preg_match_all("/".$preg."/", $url, $test)
                || ($route->path == '/' || $route->path == '') && ($url == '' || $url == '/')
            ) {
                // get param
                $param = $this->getParams($url, (strlen($route->path) +1));

                return [
                    $param,
                    $route
                ];
            }
        }
        return Controller::fourOFour();
    }

    /**
     * return the  good number of param
     * @param  string $url    url
     * @param  int    $length number of param needed
     * @return array          params
     */
    protected function getParams($url, $length)
    {
        $param = substr($url, $length);
        return array_filter(explode('/', $param), function ($val) {
            return !empty($val);
        });
    }

    /**
     * sort path
     * @param  Route  $a first route to check
     * @param  Route  $b second route to check
     * @return bool
     */
    private static function sort($a, $b)
    {
        return strlen($b->path) - strlen($b->path);
    }

    /**
     * add new route
     * @param Route $Route
     */
    public function add (Route $Route)
    {
        $this->route[] = $Route;
        return $this;
    }

    /**
     * throw
     * @param  string $error error message
     * @return void
     */
    protected function error($error)
    {
        throw new Exception($error);
        die;
    }

    protected function checkController ($controller)
    {

        if (empty($controller)) {
            return $this->error('No Controller');
        }

        $path = CONTROLLERS_ROOT . strtolower($controller) . '.controller.php';
        if (!file_exists($path)) {
            return $this->error('No file');
        }

        require_once $path;
        $classCalled = $controller . 'Controller';

        if (!class_exists($classCalled)) {
            return $this->error('No controller class');
        }

        return new $classCalled ($controller);
    }

    /**
     * get action and check it
     * @param  object $class
     * @param  string $method
     * @return string             method name
     */
    protected function checkAction ($class, $method)
    {

        if (empty($method)) {
            return $this->error('No action');
        }

        if (!method_exists($class, $method)) {
            return $this->error('No method 404');
        }

        return $method;
    }


    /**
     * get action and check it
     * @param  object $class
     * @param  string $method
     * @return string             method name
     */
    protected function checkParams ($param, $length)
    {
        if (count($param) != $length) {
            return Controller::fourOFour();
        }


        return $param;
    }

    /**
     * redirect to new view (where all the magic happens)
     * @return mixed
     */
    public function redirect ()
    {
        $result     = $this->search($this->url);
        $controller = $this->checkController($result[1]->getController());
        $action     = $this->checkAction($controller, $result[1]->getAction());
        $params     = $this->checkParams($result[0], $result[1]->getParam());
        $params     = $result[1]->getParam() ? array_chunk($result[0], $result[1]->getParam())[0] : [];
        try {
            call_user_func_array([$controller, $action], $params);
        } catch (Exception $e) {
            call_user_func_array([$controller, 'fourOFour'], []);
        }

    }
}
