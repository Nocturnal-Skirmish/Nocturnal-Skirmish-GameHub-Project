<?php
// Sets nickname in header to the correct messenger
require "avoid_errors.php";

// If a current messenger has not been set
if (!isset($_SESSION['current_messenger'])) {
    // Get the last chat you sent message in, and set that to the current chat
    $stmt3 = $conn->prepare("SELECT * FROM chats WHERE user_id = ? ORDER BY last_chat DESC LIMIT 1");
    $stmt3->bind_param("s", $_SESSION['user_id']);
    $stmt3->execute();
    $result3 = $stmt3->get_result();
    if ((mysqli_num_rows($result3) <= 0)) {
        $stmt3->close();
        goto end;
    }
    $row3 = mysqli_fetch_assoc($result3);
    $_SESSION['current_table'] = $row3['tablename'];

    $type = "";
    if ($row3['type'] == "groupchat") {
        $type = "groupchat";
    } else {
        $type = "two_user";
    }
    $stmt3->close();

    // Set the current messenger
    if ($type == "two_user") {
        $stmt3 = $conn->prepare("SELECT user_id FROM chats WHERE user_id <> ? AND tablename = ?");
        $stmt3->bind_param("ss", $_SESSION['user_id'], $row3['tablename']);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $row3 = mysqli_fetch_assoc($result3);

        $_SESSION['current_messenger'] = $row3['user_id'];
        $_SESSION['current_messenger_type'] = "two_user";
        $stmt3->close();
    } else if ($type == "groupchat") {
        $_SESSION['current_messenger'] = $row3['tablename'];
        $_SESSION['current_messenger_type'] = "groupchat";
    }
}

if ($_SESSION['current_messenger'] == "public") {
    echo "<p class='messages-public-headline'><img src='./img/icons/globe.svg' alt='Public Chat'>Public Chat</p>";
} else {
    // Echo the current messenger to screen
    if ($_SESSION['current_messenger_type'] == "two_user") {
        // If the chat is two user (private message)
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->bind_param("s", $_SESSION['current_messenger']);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows === 0){
            // if user doesnt exist
            echo "              <a href='#'>
                                    <div class='current-messenger-profilepic' style='background-image: url(./img/profile_pictures/defaultprofile.svg);'>
                                        <img src='./img/borders/defaultborder.webp'>
                                    </div>
                                </a>
                                <div class='current-messenger-name-container'>
                                    <p>Deleted User</p>
                                </div>";
        } else {
            $row = mysqli_fetch_assoc($result);
            echo "<a href='#' onclick='displayUserProfile(" . $row['user_id'] . ")'>
                                    <div class='current-messenger-profilepic' style='background-image: url(./img/profile_pictures/" . $row['profile_picture'] . ");'>
                                        <img src='./img/borders/" . $row['profile_border'] . "'>
                                    </div>
                                </a>
                                <div class='current-messenger-name-container'>
                                    <p>" . $row['nickname'] . "</p>
                                </div>";
        }
    } else if ($_SESSION['current_messenger_type'] == "groupchat") {
        // If the chat is a groupchat
        $stmt = $conn->prepare("SELECT * FROM groupchat_settings WHERE tablename = ?");
        $stmt->bind_param("s", $_SESSION['current_messenger']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        printf("
            <div class='current-messenger-groupchat-image' style='background-image: url(./img/groupchat_images/" . $row['groupchat_image'] . ");'></div>
            <div class='current-messenger-name-container'>
                <p>" . $row['groupchat_name'] . "</p>
            </div>
            <button class='groupchat-settings-button' onclick='openGroupchatSettings(%s)' title='Groupchat settings'></button>", '"' . $row['tablename'] . '"');
    }
    $stmt->close();
}
end: