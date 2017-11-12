<?php
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Netflix</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="https:cdn.jsdelivr.net/foundation/6.2.4/foundation.min.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,700" rel="stylesheet">
        <link rel="stylesheet" href="dist/css/style.min.css">
        <meta name="theme-color" content="#141414" />
    </head>
    <body class="container">
        <div class="row">
            <div class="columns">
                <a href="<?= '//' . $_SERVER['HTTP_HOST'] . $uri_parts[0] ?>"><img src="dist/img/netflix-logo.svg" alt="Netflix"></a>
            </div>
            <div class="columns">
