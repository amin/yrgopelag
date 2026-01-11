<?php
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/bootstrap.php";

$hotelProperties = listIslandProperties();
$hotelName = htmlspecialchars($hotelProperties['island']['hotelName'] ?? 'Hotel');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $hotelName ?></title>
    <link rel="stylesheet" href="/assets/stylesheet.css">
</head>

<body>
    <div class="hero">
        <div class="container">
            <h1><?= $hotelName ?></h1>
        </div>
    </div>
    <div class="container">

        <section class="receipt">
            <div class="container">
                <?php $receipt = flashReceipt(); ?>
                <?php if ($receipt): ?>
                    <div class="receipt">
                        <?= var_dump($receipt); ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="rooms">
            <?php foreach (["Budget", "Standard", "Luxury"] as $room): ?>
                <div class="room">
                    <img src="https://placehold.co/150x150" class="room-image">
                    <div class="room-copy">
                        <h3 class="room-title"><?= $room ?></h3>
                        <div class="room-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa quasi accusantium ipsam exercitationem! Exercitationem, ex.</div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <section class="book">

            <div class="errors">
                <?php
                foreach (flashErrors() as $error) {
                    echo "<p>" . htmlspecialchars($error) . "</p>";
                }
                ?>
            </div>

            <div class="book__heading">
                <h2>Book your stay</h2>
                <h3>January 2026</h3>
            </div>
            <div class="book__group">
                <table class="book__calendar">
                    <thead>
                        <tr>
                            <?php foreach (["S", "M", "T", "W", "T", "F", "S"] as $day): ?>
                                <th><?= $day ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $cells = array_merge(array_fill(0, 4, ""), range(1, 31));
                        foreach (array_chunk($cells, 7) as $week): ?>
                            <tr>
                                <?php foreach ($week as $day): ?>
                                    <td data-date="<?= str_pad($day, 2, '0', STR_PAD_LEFT) ?>"><?= $day ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <form class="book__form" method="post" action="/posts/book.php">
                    <label for="room_id">Room Type</label>
                    <select id="room_id" name="room_id">
                        <option value="1">Budget</option>
                        <option value="2">Standard</option>
                        <option value="3">Luxury</option>
                    </select>

                    <label for="guest_name">Guest Name</label>
                    <input type="text" id="guest_name" name="guest_name" placeholder="Guest">

                    <label for="api_key">API Key</label>
                    <input type="text" id="api_key" name="api_key" placeholder="API Key">

                    <label for="arrival_date">Arrival Date</label>
                    <input type="date" id="arrival_date" name="arrival_date" min="2024-01-01" max="2026-01-31">

                    <label for="departure_date">Departure Date</label>
                    <input type="date" id="departure_date" name="departure_date" min="2024-01-01" max="2026-01-31">


                    <?php
                    $features = $hotelProperties['features'] ?? [];
                    ?>

                    <fieldset>
                        <legend>Select Features for <?= $hotelName ?></legend>

                        <?php if (empty($features)): ?>
                            <p>No features available.</p>
                        <?php else: ?>
                            <?php foreach ($features as $f): ?>
                                <div>
                                    <label>
                                        <input
                                            type="checkbox"
                                            name="features[]"
                                            data-tier="<?= $f['tier'] ?>"
                                            value="<?= htmlspecialchars($f['activity'] . '|' . $f['tier']) ?>">
                                        <?= htmlspecialchars($f['feature']) ?>
                                        (<?= ucwords(str_replace('-', ' ', $f['activity'])) ?> - <?= ucfirst($f['tier']) ?>)
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </fieldset>

                    <div class="total-price">
                        <span>Total Price:</span>
                        <span id="total_price">$0.00</span>
                    </div>

                    <button type="submit">Book Now</button>
                </form>
            </div>
        </section>
    </div>
    <script src="/assets/app.js"></script>
</body>

</html>