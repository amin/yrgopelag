<?php

define('AVAILABLE_FEATURES', json_decode(file_get_contents(__DIR__ . '/../database/features.json'), true));

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

foreach (glob(__DIR__ . '/services/*.php') as $file) {
    require_once $file;
}
