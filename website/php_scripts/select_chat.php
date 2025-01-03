<?php
// Selects a chat to display messages from
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    require "avoid_errors.php";
    $tablename = htmlspecialchars($_POST['tablename']);

    if ($tablename == "public") {
        // Set the current table to public
        $_SESSION['current_table'] = 'public';

        // Reset amount of messages loaded in
        $_SESSION['message_amount'] = 15;

        // Sets the current messenger to public chat
        $_SESSION['current_messenger'] = "public";
    } else {
        // Checks if logged in user has access to the table
        $stmt = $conn->prepare("SELECT * FROM chats WHERE user_id = ? AND tablename = '$tablename'");
        $stmt->bind_param("s", $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ((mysqli_num_rows($result) <= 0)) {
            echo "error";
            exit;
        }
        $row = mysqli_fetch_assoc($result);
        $stmt->close();

        if ($row['type'] == "two_user") {
            // Get user id of other user
            $stmt = $conn->prepare("SELECT user_id FROM chats WHERE user_id <> ? AND tablename = '$tablename'");
            $stmt->bind_param("s", $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);

            $_SESSION['current_messenger'] = $row['user_id'];

            // Set the type to two user
            $_SESSION['current_messenger_type'] = "two_user";
        } else if ($row['type'] == "groupchat") {

            $_SESSION['current_messenger'] = $tablename;

            // Set the type to groupchat
            $_SESSION['current_messenger_type'] = "groupchat";
        }

        // Set the current table to the tablename
        $_SESSION['current_table'] = $tablename;

        // Reset amount of messages loaded in
        $_SESSION['message_amount'] = 15;

        $unix_timestamp = time();

        // Update last_accessed column in chats table
        $current_table = $_SESSION['current_table'];
        $stmt = $conn->prepare("UPDATE chats SET last_accessed = ? WHERE user_id = ? AND tablename = ?");
        $stmt->bind_param("sss", $unix_timestamp, $_SESSION['user_id'], $current_table );
        $stmt->execute();
        $stmt->close();
    }
} else {
    header("Location: ../index.php");
}