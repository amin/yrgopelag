<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/bootstrap.php";

$hotelProperties = getIslandProperties();
$hotelName = h($hotelProperties['island']['hotelName'] ?? 'Hotel');
$receipt = flashReceipt();
$errors = flashErrors();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $hotelName ?></title>
    <link rel="stylesheet" href="assets/stylesheet.css">
</head>

<body>
    <header class="hero">
        <div class="container">
            <h1><?= $hotelName ?></h1>
            <div class="stars">★★</div>
        </div>
    </header>

    <main class="container">

        <?php if ($receipt): ?>
            <div class="alert alert-success" role="alert">
                <h2>Booking successful!</h2>
                <p><strong>Receipt ID:</strong> <?= h($receipt['receipt_id']) ?></p>
                <p><strong>Room:</strong> <?= h(ucfirst($receipt['room_type'])) ?></p>
                <p><strong>Arrival:</strong> <?= h($receipt['arrival_date']) ?></p>
                <p><strong>Departure:</strong> <?= h($receipt['departure_date']) ?></p>
            </div>
        <?php endif; ?>

        <?php foreach ($errors as $error): ?>
            <div class="alert alert-failure" role="alert">
                <p><?= h($error) ?></p>
            </div>
        <?php endforeach; ?>

        <section class="rooms">
            <?php foreach (getRooms() as $room): ?>
                <article class="room">
                    <img src="assets/images/<?= h($room['type']) ?>.jpg" class="room-image" ?>
                    <div class="room-copy">
                        <h3 class="room-title"><?= h(ucfirst($room['type'])); ?></h3>
                        <p class="room-description"><?= h($room['description']) ?></p>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <section class="book">
            <header class="book__heading">
                <h2>Book your stay</h2>
            </header>

            <div class="book__group">
                <form class="book__form" method="post" action="posts/book.php">
                    <label for="room_id">Room Type</label>
                    <select id="room_id" name="room_id" required>
                        <?php $rooms = getRoomPricing(); ?>
                        <?php foreach ($rooms as $room): ?>
                            <option data-price="<?= h($room['price']) ?>" value="<?= h($room['id']) ?>">
                                <?= h(ucfirst($room['type'])) ?> - <?= h($room['price']) ?>c / night
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label for="guest_name">Guest Name</label>
                    <input type="text" id="guest_name" name="guest_name" placeholder="Guest" required>

                    <label for="api_key">API Key</label>
                    <input type="text" id="api_key" name="api_key" placeholder="API Key" required>

                    <label for="arrival_date">Arrival Date</label>
                    <input type="date" id="arrival_date" name="arrival_date" min="2026-01-01" max="2026-01-31" value="<?php echo date('Y-m-d'); ?>" required>

                    <label for="departure_date">Departure Date</label>
                    <input type="date" id="departure_date" name="departure_date" min="2026-01-02" max="2026-01-31" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>

                    <?php $features = $hotelProperties['features'] ?? []; ?>

                    <fieldset>
                        <legend>Features</legend>

                        <?php if (empty($features)): ?>
                            <p>No features available.</p>
                        <?php else: ?>
                            <?php $pricing = getFeaturePricing(); ?>
                            <?php foreach ($features as $f): ?>
                                <div>
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="features[]"
                                            data-price="<?= h($pricing[$f['tier']]) ?>"
                                            data-tier="<?= h($f['tier']) ?>"
                                            value="<?= h($f['activity'] . '|' . $f['tier']) ?>">
                                        <?= sprintf('%s %sc / night', h($f['feature']), h($pricing[$f['tier']])) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </fieldset>

                    <output class="total-price" for="room_id arrival_date departure_date">
                        <span>Total Price:</span>
                        <span id="total_price">0 credits</span>
                    </output>

                    <button type="submit">Book Now</button>
                </form>
                <div class="calendar__guide">

                    <table class="book__calendar" aria-label="January 2026 availability calendar">
                        <caption>January 2026</caption>
                        <thead>
                            <tr>
                                <?php foreach (["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"] as $day): ?>
                                    <th scope="col" abbr="<?= h($day) ?>"><?= h(substr($day, 0, 1)) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cells = array_merge(array_fill(0, 4, ""), range(1, 31));
                            foreach (array_chunk($cells, 7) as $week): ?>
                                <tr>
                                    <?php foreach ($week as $day): ?>
                                        <td data-date="2026-01-<?= h(str_pad($day, 2, '0', STR_PAD_LEFT)) ?>"><?= h($day) ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="guide">
                        <div class="block"></div>
                        <div class="message">Room already booked</div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="assets/app.js"></script>
</body>

</html>