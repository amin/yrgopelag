<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';


createBookingRequest('Rune', 'f40f9feb-ae8f-4cdd-983b-d222e94bfd3d', 1, '2026-01-10', '2026-01-12',  [['activity' => 'dining', 'tier' => 'superior']]);


function createBookingRequest(string $guestName, string $apiKey, int $roomId, string $arrivalDate, string $departureDate, array $features = [])
{
    $pdo = getDb();

    if (!_checkRoomAvailability($roomId, $arrivalDate, $departureDate)) {
        echo json_encode(['error' => 'Room is not available']);
        return null;
    }

    $bookingPrice = _calculateBookingPrice($roomId, $arrivalDate, $departureDate, $features);
    $guestAccountBalance = getAccountBalance($guestName, $apiKey);

    if ($bookingPrice > $guestAccountBalance) {
        echo json_encode(['Error' => 'Your balance is too low to complete this booking.']);
        return null;
    }

    // $transferCode = createTransferCode($guestName, $apiKey, $bookingPrice)['transferCode'];
    // $checkTransfer = depositTransferCode($transferCode);

    // if ($checkTransfer['status'] !== 'success') {
    //     echo json_encode(['Error' => 'Something went wrong.']);
    //     return null;
    // }

    echo 'Room is available';
}
