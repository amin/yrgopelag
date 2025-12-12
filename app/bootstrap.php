<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad();

foreach (glob(__DIR__ . '/functions/*.php') as $file) {
    require_once $file;
}
