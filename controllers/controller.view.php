<?php
/**
 * get view
 * @param  string $path  get view path with keyword
 * @param  array  $views all the info
 * @return void
 */
function view($path, $views) {
    require_once VIEWS_ROOT . '/view.header.php';
    require_once VIEWS_ROOT . '/view.' . $path . '.php';
    require_once VIEWS_ROOT . '/view.footer.php';
}