<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();


if (($_ENV['DEV'] ?? '') === '1') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

define('AVAILABLE_FEATURES', json_decode(file_get_contents(__DIR__ . '/../database/features.json'), true));

foreach (glob(__DIR__ . '/utils/*.php') as $file) {
    require_once $file;
}

foreach (glob(__DIR__ . '/services/*.php') as $file) {
    require_once $file;
}
