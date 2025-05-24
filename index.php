<?php

// Register the autoloader
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});

$reporting = new Reporting();
$reporting->main();