<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';

header('Content-Type: Application/json');
echo json_encode(getHotelCalendar());
