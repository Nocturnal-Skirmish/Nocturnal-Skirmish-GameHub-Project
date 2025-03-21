<?php
require "./php_scripts/avoid_errors.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nocturnal Skirmish - Card Dex</title>
    <link rel="icon" type=".image/x-icon" href="./img/favicon.png">
    <style> <?php include "./css/universal.css" ?> </style>
    <style> <?php include "./css/carddex.css" ?> </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Silkscreen:wght@400;700&display=swap" rel="stylesheet">
</head>
<body id="carddex-body" onload="prepareSFX(); ajaxGet('./php_scripts/update_login_time.php', 'hidden', 'no_sfx'); searchCard('');">
    <div class="confirmation-popup" id="confirmContainer"></div>
    <div id="dark-container" class="dark-container"></div>
    <div id="hidden" style="display: none;"></div>
    <button class="carddex-sidebar-open" id="carddex-sidebar-open"></button>
    <div class="content">
        <div class="card-grid" id="card-grid"></div>
        <div class="carddex-sidebar-container" id="carddex-sidebar-container">
            <button class="carddex-sidebar-close" id="carddex-sidebar-close"></button>
            <div class="carddex-headline-container">
                <h1 class="carddex-headline">Card Dex</h1>
                <button class="back-button" onclick="window.location.href = 'nocturnal-skirmish.php'">Back</button>
            </div>
            <div class="carddex-search-sidebar">
                <div class="carddex-search-bar">
                    <input type="text" id="searchInput" placeholder="Search for a card...">
                </div>
                <p class="sort-by-headline">Sort by:</p>
                <div class="carddex-sorting">
                    <div class="carddex-sort-radio">
                        <input type="radio" name="filterChoice" onclick="sortBy('card_name')"></input>
                        <p>Name ↓</p>
                    </div>
                    <div class="carddex-sort-radio">
                        <input type="radio" name="filterChoice" onclick="sortBy('bp')"></input>
                        <p>BP ↑</p>
                    </div>
                    <div class="carddex-sort-radio">
                        <input type="radio" name="filterChoice" onclick="sortBy('element')"></input>
                        <p>Element</p>
                    </div>
                    <div class="carddex-sort-radio">
                        <input type="radio" name="filterChoice" onclick="sortBy('rarity_int')"></input>
                        <p>Rarity ↑</p>
                    </div>
                </div>
            </div>
            <div class="carddex-additional-info">
                    <h4 class="carddex-info-item-title" style="text-decoration: underline;">Rarities</h4>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Common);">
                            <p>Common</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Uncommon);">
                            <p>Uncommon</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Rare);">
                            <p>Rare</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Epic);">
                            <p>Epic</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Mythic);">
                            <p>Mythic</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Legendary);">
                            <p>Legendary</p>
                        </div>
                    </div>
                    <div class="carddex-info-item">
                        <div class="carddex-info-item-box" style="background: var(--Exclusive);">
                            <p>Exclusive</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="carddex-sidebar-background" id="carddex-sidebar-background"></div>
    </div>
</body>
<script><?php include "./js/script.js" ?></script>
<!-- Autolooping audio background music (works only if user allows it) -->
<audio autoplay loop style="display: none;" id="musicAudio">
    <source src="./audio/music/IntermissionOST.mp3" type="audio/mpeg">
</audio>
<!-- hover audio temp -->
<audio id='hoverSFX'>
        <source src="audio/sfx/hover.mp3" type="audio/mpeg">
    </audio>
    <!-- click sfx temp -->
    <audio id='clickSFX'>
        <source src="audio/sfx/click1.mp3" type="audio/mpeg">
    </audio>
</html>
<script>
    <?php include "./js/carddex.js" ?>
</script>