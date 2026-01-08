<?php

declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $guestName = $_POST['guest_name'] ?? '';
    $apiKey = $_POST['api_key'] ?? '';
    $roomId = (int) ($_POST['room_id'] ?? 0);
    $arrivalDate = $_POST['arrival_date'] ?? '';
    $departureDate = $_POST['departure_date'] ?? '';

    $features = [];
    if (!empty($_POST['features'])) {
        foreach ($_POST['features'] as $feature) {
            $parts = explode('|', $feature);
            if (count($parts) === 2) {
                $features[] = [
                    'activity' => $parts[0],
                    'tier' => $parts[1]
                ];
            }
        }
    }

    createBookingRequest($guestName, $apiKey, $roomId, $arrivalDate, $departureDate, $features);
}

function createBookingRequest(string $guestName, string $apiKey, int $roomId, string $arrivalDate, string $departureDate, array $features = [])
{
    if (!checkRoomAvailability($roomId, $arrivalDate, $departureDate)) {
        header('Location: /?error=not_available');
        exit;
    }

    $bookingPrice = calculateBookingPrice($roomId, $arrivalDate, $departureDate, $features);
    $guestAccountBalance = getAccountBalance($guestName, $apiKey);

    if ($bookingPrice > $guestAccountBalance) {
        header('Location: /?error=low_balance');
        exit;
    }

    // $transferCode = createTransferCode($guestName, $apiKey, $bookingPrice)['transferCode'];
    // $checkTransfer = depositTransferCode($transferCode);

    // if ($checkTransfer['status'] !== 'success') {
    //     header('Location: /?error=1');
    //     exit;
    // }

    $bookingId = createBooking($roomId, $guestName, $arrivalDate, $departureDate, $bookingPrice, $features);

    header('Location: /?success=1');
    exit;
}
