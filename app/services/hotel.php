<?php

function checkRoomAvailability(int $roomId, string $arrivalDate, string $departureDate): bool
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


function calculateBookingPrice(int $roomId, string $arrivalDate, string $departureDate, array $features): int
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

function createBooking(int $roomId, string $guestName, string $arrivalDate, string $departureDate, int $totalCost, array $features): int
{
    $pdo = getDb();

    // Insert booking
    $stmt = $pdo->prepare("
        INSERT INTO bookings (room_id, guest_name, arrival_date, departure_date, total_cost)
        VALUES (:room_id, :guest_name, :arrival_date, :departure_date, :total_cost)
    ");
    $stmt->execute([
        ':room_id' => $roomId,
        ':guest_name' => $guestName,
        ':arrival_date' => $arrivalDate,
        ':departure_date' => $departureDate,
        ':total_cost' => $totalCost
    ]);

    $bookingId = (int) $pdo->lastInsertId();

    // Insert booking features
    $arrival = new DateTime($arrivalDate);
    $departure = new DateTime($departureDate);
    $nights = $arrival->diff($departure)->days;

    foreach ($features as $feature) {
        $stmt = $pdo->prepare("SELECT price FROM tier_pricing WHERE tier = :tier");
        $stmt->execute([':tier' => $feature['tier']]);
        $tierPrice = (int) $stmt->fetchColumn();

        $stmt = $pdo->prepare("
            INSERT INTO booking_features (booking_id, activity, tier, price)
            VALUES (:booking_id, :activity, :tier, :price)
        ");
        $stmt->execute([
            ':booking_id' => $bookingId,
            ':activity' => $feature['activity'],
            ':tier' => $feature['tier'],
            ':price' => $tierPrice * $nights
        ]);
    }

    return $bookingId;
}
