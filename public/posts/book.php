<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';

function _checkRoomAvailability(string $roomType, string $arrivalDate, string $departureDate): bool
{
    $pdo = getDb();

    $sql = "
SELECT COUNT(*)
FROM bookings b
JOIN rooms r ON b.room_id = r.id
WHERE r.type = :room_type
AND b.arrival_date < :departure_date
    AND b.departure_date> :arrival_date
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':room_type' => $roomType,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate
    ]);

    return $stmt->fetchColumn() == 0;
}


function createBookingRequest(string $guestName, string $apiKey, string $roomType, string $arrivalDate, string $departureDate, array $features = [])
{
    $pdo = getDb();

    if (!_checkRoomAvailability($roomType, $arrivalDate, $departureDate)) {
        return null;
    }
}
