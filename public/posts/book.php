<?php

declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $errors = [];

    $guestName = trim($_POST['guest_name'] ?? '');
    $apiKey = trim($_POST['api_key'] ?? '');
    $roomId = filter_var($_POST['room_id'], FILTER_VALIDATE_INT);
    $arrivalDate = trim($_POST['arrival_date'] ?? '');
    $departureDate = trim($_POST['departure_date'] ?? '');

    if (empty($guestName)) $errors[] = 'Guest name is required';
    if (empty($apiKey)) $errors[] = 'API key is required';
    if (empty($arrivalDate)) $errors[] = 'Arrival date is required';
    if (empty($departureDate)) $errors[] = 'Departure date is required';
    if ($roomId === false) $errors[] = 'Invalid room ID';

    if (!empty($arrivalDate) && !empty($departureDate) && strtotime($departureDate) <= strtotime($arrivalDate)) {
        $errors[] = 'Departure date must be after arrival date';
    }

    $features = [];

    if (!empty($_POST['features']) && is_array($_POST['features'])) {
        foreach ($_POST['features'] as $feature) {
            $parts = explode('|', $feature);
            if (count($parts) === 2) {
                $activity = trim($parts[0]);
                $tier = trim($parts[1]);

                $features[] = [
                    'activity' => $activity,
                    'tier' => $tier
                ];
            }
        }
    }

    if (empty($errors)) {
        createBookingRequest($guestName, $apiKey, $roomId, $arrivalDate, $departureDate, $features);
    }
}

function createBookingRequest(string $guestName, string $apiKey, int $roomId, string $arrivalDate, string $departureDate, array $features = [])
{
    if (!checkRoomAvailability($roomId, $arrivalDate, $departureDate)) {
        $_SESSION['errors'][] = 'Room not available.';
        header('Location: /');
        exit;
    }

    try {
        $bookingPrice = calculateBookingPrice($roomId, $arrivalDate, $departureDate, $features);
        $transferCode = createTransferCode($guestName, $apiKey, $bookingPrice)['transferCode'];
        depositTransferCode($transferCode);
        $receipt = createReceipt($guestName, $arrivalDate, $departureDate, $features);

        $_SESSION['receipt'] = array_merge($receipt, [
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'features' => $features,
            'room_type' => getRoomNameById($roomId)
        ]);

        createBooking($roomId, $guestName, $arrivalDate, $departureDate, $bookingPrice, $features);

        header('Location: /');
        exit;
    } catch (PDOException $e) {
        $_SESSION['errors'][] = "Something went wrong. Please try again.";
        header('Location: /');
        exit;
    } catch (Exception $e) {
        $_SESSION['errors'][] = $e->getMessage();
        header('Location: /');
        exit;
    }
}
