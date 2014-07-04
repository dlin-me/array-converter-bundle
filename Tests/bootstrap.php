<?php

$file = __DIR__.'/../vendor/autoload.php';
if (!file_exists($file)) {
    throw new RuntimeException('Install dependencies to run test suite. "php composer.phar install --dev"');
}

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once $file;