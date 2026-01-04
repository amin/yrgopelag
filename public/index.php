<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';


$stmt = $pdo->query('SELECT * FROM rooms');
$rooms = $stmt->fetchAll();

print_r($rooms);
