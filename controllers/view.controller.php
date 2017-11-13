<?php
/**
 * get view
 * @param  string $path  get view path with keyword
 * @param  array  $views all the info
 * @return void
 */
function view($path, $views)
{
    require_once VIEWS_ROOT . '/header.view.php';
    require_once VIEWS_ROOT . '/' . $path . '.view.php';
    require_once VIEWS_ROOT . '/footer.view.php';
}
