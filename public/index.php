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
    <link rel="stylesheet" href="/assets/stylesheet.css">
</head>

<body>
    <div class="hero">
        <div class="container">
            <h1>Oceanview Resort</h1>
        </div>
    </div>
    <div class="container">
        <div class="rooms">
            <div class="room">
                <img src="https://placehold.co/150x150" class="room-image">
                <div class="room-copy">
                    <h3 class="room-title">Budget</h3>
                    <div class="room-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa quasi accusantium ipsam exercitationem! Exercitationem, ex.</div>
                </div>
            </div>
            <div class="room">
                <img src="https://placehold.co/150x150" class="room-image">
                <div class="room-copy">
                    <h3 class="room-title">Standard</h3>
                    <div class="room-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa quasi accusantium ipsam exercitationem! Exercitationem, ex.</div>
                </div>
            </div>
            <div class="room">
                <img src="https://placehold.co/150x150" class="room-image">
                <div class="room-copy">
                    <h3 class="room-title">Luxury</h3>
                    <div class="room-description">Lorem ipsum, dolor sit amet consectetur adipisicing elit. Culpa quasi accusantium ipsam exercitationem! Exercitationem, ex.</div>
                </div>
            </div>
        </div>

        <div class="book">
            <h2>Book your stay</h2>
        </div>
    </div>
    </div>
</body>

</html>