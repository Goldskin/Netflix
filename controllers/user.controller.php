<?php

/**
 *
 */
class User extends Controller
{

    function index () {
        $d = [
            'test' => [
                'title' => 'hi',
                'text' => 'this is my text'
            ]
        ];
        $this->set($d)->render('user');
    }
}
