<?php


if (($_ENV['DEV'] ?? '') === '1') {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}


define('AVAILABLE_FEATURES', json_decode(file_get_contents(__DIR__ . '/../database/features.json'), true));

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

require_once __DIR__ . '/db.php';

foreach (glob(__DIR__ . '/helpers/*.php') as $file) {
    require_once $file;
}

foreach (glob(__DIR__ . '/posts/*.php') as $file) {
    require_once $file;
}
