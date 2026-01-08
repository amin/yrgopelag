<?php

function _checkRoomAvailability(int $roomId, string $arrivalDate, string $departureDate): bool
{
    $pdo = getDb();

    $sql = "
        SELECT COUNT(*)
        FROM bookings
        WHERE room_id = :room_id
        AND arrival_date < :departure_date
        AND departure_date > :arrival_date
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':room_id' => $roomId,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate
    ]);

    return $stmt->fetchColumn() == 0;
}


function _calculateBookingPrice(int $roomId, string $arrivalDate, string $departureDate, array $features): int
{
    $pdo = getDb();
    $totalPrice = 0;

    $stmt = $pdo->prepare("SELECT price FROM rooms WHERE id = :room_id");
    $stmt->execute([':room_id' => $roomId]);
    $roomPrice = $stmt->fetchColumn();

    $arrival = new DateTime($arrivalDate);
    $departure = new DateTime($departureDate);
    $nights = $arrival->diff($departure)->days;

    $totalPrice += $roomPrice * $nights;

    foreach ($features as $feature) {
        $stmt = $pdo->prepare("SELECT price FROM tier_pricing WHERE tier = :tier");
        $stmt->execute([':tier' => $feature['tier']]);
        $tierPrice = $stmt->fetchColumn();

        if ($tierPrice) {
            $totalPrice += $tierPrice * $nights;
        }
    }

    return $totalPrice;
}
