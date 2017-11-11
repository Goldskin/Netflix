<?php

function view($path, $views) {
    require_once VIEWS_ROOT . '/view.header.php';
    require_once VIEWS_ROOT . '/view.' . $path . '.php';
    require_once VIEWS_ROOT . '/view.footer.php';
}