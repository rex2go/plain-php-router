<?php
spl_autoload_register(function ($className) {
    $className = substr($className, 4);
    $className = str_replace('\\', '/', $className);
    $file = $className . '.php';

    if (file_exists(__DIR__ . '/' . $file)) {
        include $file;
    }
});