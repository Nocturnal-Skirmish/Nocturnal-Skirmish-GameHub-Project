<?php
// Loads messages from current table seechat table
require "../../php_scripts/avoid_errors.php";
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
    exit;
}
start:

if (!isset($_SESSION['current_admin_seechat'])) {
    // If a chat has not been selected
    echo "Error: no chat selected";
} else {
    $conn -> select_db("gamehub_messages");
    $tablename = $_SESSION['current_admin_seechat'];

    // Check if table exists
    $stmt = $conn->prepare("SELECT table_name FROM information_schema.tables WHERE table_schema = 'gamehub_messages' AND table_name = '$tablename';");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows === 0){
        //Table doesnt exist
        unset($_SESSION['current_admin_seechat']);
        echo "Error: no chat selected";
        exit;
    }
    $stmt->close();

    $stmt = $conn->prepare("SELECT *
FROM (
    SELECT *
    FROM $tablename
    ORDER BY message_id DESC
) AS latest_posts
ORDER BY message_id ASC;");
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows != 0){
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['user_id'] == 0) {
                // if the user id is 0, it is a notifier message, generated by the server
                echo "<div class='notifier-message'>" . $row['message'] . "</div>";
                goto end;
            }
            // Gets information about user who sent the current message
            $conn -> select_db("gamehub");
            $stmt2 = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt2->bind_param("s", $row['user_id']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $row2 = mysqli_fetch_assoc($result2);

            // if row has image attached, show it.
            if($row['file'] != NULL) {
                $mediaAttachment = "<a target='_blank' href='../img/chat_images/" . $row['file'] . "'><img class='message-media-attachment' src='../img/chat_images/" . $row['file'] . "'></a>";

                // If message has text, add a breakline to put the image under the text
                if (strlen($row['message']) > 0) {
                    $br = "<br>";
                } else {
                    $br = "";
                }
            } else {
                $mediaAttachment = "";
                $br = "";
            }

            // Prepares nickname to put inside replyToMessage parameter
            $nickname = '"' . $row2['nickname'] . '"';

            // Checks if message is reply to diffrent message. If it is, add reply-message div
            if ($row['reply'] != 0) {
                // Get information about message being replied to.
                $conn -> select_db("gamehub_messages");
                $stmt3 = $conn->prepare("SELECT message, user_id, file FROM $tablename WHERE message_id = ?");
                $stmt3->bind_param("s", $row['reply']);
                $stmt3->execute();
                $result3 = $stmt3->get_result();
                if($result3->num_rows === 0){
                    // If the message was deleted
                    $replyMessage = "<div class='reply-message-container' title='Message was deleted'>
                        <img src='../img/icons/reply_mirror.svg' class='reply-message-arrow'>
                        <div class='reply-message-profilepic' style='background-image: url(../img/profile_pictures/defaultprofile.svg);'>
                            <img src='../img/borders/defaultborder.webp'>
                        </div>
                        <div class='reply-message-name-container'>
                            <h1>Deleted Message</h1>
                            <div class='reply-message-content-container'>
                                <p>Deleted Message</p>
                            </div>
                        </div>
                    </div>";
                    goto reply_end;
                }
                $reply_message_row = mysqli_fetch_assoc($result3);

                // Get information about user who sent the message
                $conn -> select_db("gamehub");
                $stmt4 = $conn->prepare("SELECT nickname, profile_border, profile_picture FROM users WHERE user_id = ?");
                $stmt4->bind_param("s", $reply_message_row['user_id']);
                $stmt4->execute();
                $result4 = $stmt4->get_result();
                $reply_message_user_row = mysqli_fetch_assoc($result4);

                // If message has image attached, add image icon
                if ($reply_message_row['file'] != NULL) {
                    $replyImageAttachment = "<img src='../img/icons/image.svg'>";
                } else {
                    $replyImageAttachment = "";
                };

                $replyMessage = "<div class='reply-message-container'>
                        <img src='../img/icons/reply_mirror.svg' class='reply-message-arrow'>
                        <div class='reply-message-profilepic' style='background-image: url(../img/profile_pictures/" . $reply_message_user_row['profile_picture'] . ");'>
                            <img src='../img/borders/" . $reply_message_user_row['profile_border'] . "'>
                        </div>
                        <div class='reply-message-name-container'>
                            <h1>" . $reply_message_user_row['nickname'] . "</h1>
                            <div class='reply-message-content-container'>
                                <p>" . $reply_message_row['message'] . "</p>
                                $replyImageAttachment
                            </div>
                        </div>
                    </div>";
            } else {
                $replyMessage = "";
            }

            reply_end:

            // If message was edited, add (edited) after timestamp
            if ($row['edited'] == 1) {
                $edited = "(edited)";
            } else {
                $edited = "";
            }

            //If there is a link in the message, wrap it in <a> tag;
            $text = strip_tags($row['message']);
            $textWithLinks = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank" rel="nofollow">$1</a>', $text);
            $message = $textWithLinks;

            // Outputs the message to the screen
            echo "<div class='message-container' id='" . $row['message_id'] . "'>
                    $replyMessage
                    <div class='message-name-container'>
                        <a href='#'>
                            <div class='message-profilepic' style='background-image: url(../img/profile_pictures/" . $row2['profile_picture'] . ");'>
                                <img src='../img/borders/" . $row2['profile_border'] . "'>
                            </div>
                        </a>
                        <h1 class='message-nickname'>" . $row2['nickname'] . " - <i>" . $row['timestamp'] . " $edited</i></h1>
                    </div>
                    <div class='message-content'>
                        <p>$message</p>$br
                        $mediaAttachment
                    </div>
                </div>";
            end:
        }
    } else {
        echo "Chat is empty.";
    }
    $stmt->close();
    $conn -> select_db("gamehub");
}