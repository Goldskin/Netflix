<?php
/**
 * controller redirection
 */
class Controller
{
    protected $vars = [];
    protected $layout = false;

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
        ob_start();
        require VIEWS_ROOT . strtolower(get_class($this)) . '/' . $filename . '.php';
        $content = ob_get_clean();
        if ($this->layout == false) {
            echo $content;
        } else {
            // require VIEWS_ROOT . 'layout/' . $this->layout . '.php';
        }
        return $this;
    }
}
