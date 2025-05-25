<?php

// Register the autoloader
spl_autoload_register(function ($class) {
    include 'classes/' . $class . '.php';
});