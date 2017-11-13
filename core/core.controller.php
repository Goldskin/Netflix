<?php
/**
 * controller redirection
 */
class Controller
{
    protected $vars = [];
    protected $layout = 'default';

    /**
     * add value to the view
     * @param array $data data to add
     * @return void
     */
    protected function set ($data)
    {
        $this->vars = array_merge($this->vars, $data);
    }

    protected function render ($filename)
    {
        extract($this->vars);
        ob_start();
        require(ROOT . 'views/' . get_class($this) . '/' . $filename . '.php');
        $content = ob_get_clean();
        if ($this->$layout == false) {
            echo $content;
        } else {
            require(ROOT . 'views/layout/' . $this->$layout . '.php');
        }
    }
}
