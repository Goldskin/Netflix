<?php

function getHeader ($Model) {
    $options = [];
    $service = $Model->options()->name() ? $Model->options()->name()->get() : 'Netflix';
    if ($Model->options()->name()) $options['name'] = $Model->options()->name()->get();
    if ($Model->options()->url()) $options['url'] = $Model->options()->url()->get();

    $options['css'] = URL . '/dist/css/' . strtolower($service) . '.min.css';
    $options['logo'] = URL . '/dist/img/' . strtolower($service) . '-logo.svg';

    $actual_link = "//$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $home = '//' . $_SERVER['HTTP_HOST'] . WEBROOT;

    if ($home != $actual_link) {
        $options['back'] = $home;
    }
    return $options;
}
