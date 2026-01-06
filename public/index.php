<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/bootstrap.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="/app/">
        <label for="name">Name</label>
        <input type="text" id="name" value="name">

        <label for="api-key">Api-key:</label>
        <input type="text" id="api-key" name="api-key" required>

        <label for="room-type">Select room type:</label>
        <select id="room-type" name="room-type" required>
            <option value='1'>Budget</option>
            <option value='2'>Standard</option>
            <option value='3'>Luxury</option>
            <option value='0'>No room</option>
        </select>

        <div class="date-wrapper">
            <div class="arrival-date-wrapper">
                <label for="arrival-date">Select arrival date:</label>
                <input type="date" id="arrival-date" name="arrival-date" min="2026-01-01" max="2026-01-31" required>
            </div>
            <div class="departure-date-wrapper">
                <label for="departure-date">Select departure date:</label>
                <input type="date" id="departure-date" name="departure-date" min="2026-01-02" max="2026-01-31" required>
            </div>
        </div>

        <button type="submit">Book Now</button>
    </form>
</body>

</html>