<?php
// Loads a grid of cards that are stored in database that match with search
require "avoid_errors.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If sort is not set, set it to battle points
    if (!isset($_SESSION["carddex_sort"])) {
        $_SESSION["carddex_sort"] = "bp";
    }

    $sort_column = $_SESSION["carddex_sort"];

    // Check if sort column is valid to avoid sql injection
    $columns = array("card_name", "bp", "element", "rarity_int");
    if (!in_array($sort_column, $columns)) {
        echo "<p class='no-cards-found'>Invalid</p>";
        exit;
    }

    // Decide wheter it needs to be sorted desc or asc
    if ($sort_column == "card_name" || $sort_column == "element") {
        $sort_column = $sort_column . " ASC";
    } else {
        $sort_column = $sort_column . " DESC";
    }

    // Get the search query
    $json = json_decode(file_get_contents('php://input'), true);
    $search = htmlspecialchars($json["search"]);

    // Get cards with the name or tag
    $stmt = $conn->prepare("SELECT * FROM cards WHERE lower(card_name) LIKE CONCAT('%', ?, '%') OR lower(tag) LIKE CONCAT('%', ?, '%') OR lower(credit) LIKE CONCAT('%', ?, '%') ORDER BY $sort_column");
    $stmt->bind_param("sss", $search, $search, $search);
    $stmt->execute();
    $result = $stmt->get_result();
    if ((mysqli_num_rows($result) <= 0)) {
        echo "<p class='no-cards-found'>No cards found containing '" . $search . "'</p>";
    } else {
        while ($row = $result->fetch_assoc()) {
            // For each card
            $name = $row["card_name"];
            $id = $row["card_id"];
            $texture = $row["texture"];
            $bp = $row["bp"];
            $rarity = $row["rarity"];

            if ($bp == 0) {
                $bp = "";
            }

            echo "<div class='card-grid-section'><div class='card-container'><img style='background-image: var(--Common), var(--$rarity);' onclick='viewCard($id)' class='card' title='See details' src='./img/cards/$texture' alt='$name'><span>$bp</span></div></div>";
        }
    }
    $stmt->close();
} else {
    header("Location: ../index.php");
    exit;
}
