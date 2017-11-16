<?php
/**
 * controller redirection
 */
class Controller
{
    protected $class;
    protected $vars = [];
    protected $layout = false;

    function __construct($class){
        $this->class = $class;
    }

    /**
     * add value to the view
     * @param array $data data to add
     * @return void
     */
    protected function set ($data)
    {
        $this->vars = array_merge($this->vars, $data);
        return $this;
    }

    protected function render ($filename)
    {
        extract($this->vars);

        require_once VIEWS_ROOT . 'header.view.php';
        ob_start();
        require_once VIEWS_ROOT . strtolower($this->class) . '/' . $filename . '.php';
        $content = ob_get_clean();

        if ($this->layout == false) {
            echo $content;
        } else {
            require VIEWS_ROOT . 'layout/' . $this->layout . '.php';
        }
        require_once VIEWS_ROOT . 'footer.view.php';
        return $this;
    }
}
