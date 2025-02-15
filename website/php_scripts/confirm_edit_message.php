<?php
// Edits a message after user has confirmed it
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $message = htmlspecialchars($_POST['message']);
    $message_id = htmlspecialchars($_POST['message_id']);
    $tablename = $_SESSION['current_table'];

    // Check if message that user is trying to edit was actually sent by them
    $conn -> select_db("gamehub_messages");
    $stmt = $conn->prepare("SELECT * FROM $tablename WHERE message_id = ? AND user_id = ?");
    $stmt->bind_param("ss", $message_id, $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0){
        // User didnt send the message or message doesnt exist
        echo "error";
        $stmt->close();
        exit;
    } else {
        $row = mysqli_fetch_assoc($result);
    }

    // Check if input is empty
    if ($message === null || strlen($message) == 0 || ctype_space($message)) {
        // Input is empty
        $empty = true;
    }

    // Check if lenght of message exceeds 500 charachters
    if (strlen($message) > 500) {
        echo "toolong";
        exit;
    }

    // Archive message
    $conn -> select_db("gamehub_messages_deleted_edited");
    $stmt = $conn->prepare("INSERT INTO edited_messages (message_id, user_id, message, file, timestamp, edited, reply) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $message_id, $row['user_id'], $row['message'], $row['file'], $row['timestamp'], $row['edited'], $row['reply']);
    $stmt->execute();
    $stmt->close();

    // Edit the message
    $conn -> select_db("gamehub_messages");
    $stmt = $conn->prepare("UPDATE $tablename SET message = ?, edited = 1 WHERE message_id = ?");
    $stmt->bind_param("ss", $message, $message_id);
    $stmt->execute();
    $stmt->close();

    // Unsets session variable
    unset($_SESSION['editmessage_id']);
} else {
    header("Location: ../index.php");
}