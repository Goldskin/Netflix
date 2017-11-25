<?php
/**
 * controller redirection
 */
class Controller
{
    protected $class;
    protected $vars = [];
    protected $layout = false;
    protected $templates = [];

    function __construct($class)
    {
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

    public function add ($action) {
        $this->templates = array_merge($this->templates, [$action]);
        return $this;
    }

    /**
     * render view
     * @param  string $filename [description]
     * @return [type]           [description]
     */
    public function render ()
    {
        extract($this->vars);

        // header
        require_once VIEWS_ROOT . 'header.view.php';

        ob_start();
        foreach ($this->templates as $templates) {
            require_once VIEWS_ROOT . strtolower($this->class) . '/' . $templates . '.php';
        }
        $content = ob_get_clean();

        // render body
        if ($this->layout == false) {
            echo $content;
        } else {
            require VIEWS_ROOT . 'layout/' . $this->layout . '.php';
        }

        // footer
        require_once VIEWS_ROOT . 'footer.view.php';
        return $this;
    }

    /**
     * get 404 page
     * @return void
     */
    public static function fourOFour()
    {
        require_once VIEWS_ROOT . 'header.view.php';
        require_once VIEWS_ROOT . '404/index.php';
        require_once VIEWS_ROOT . 'footer.view.php';

        die;
    }
}
