<?php
session_start();
if (!isset($_SESSION['isadmin']) || $_SESSION['isadmin'] != 1) {
    header("Location: ../admin_login.php?error=unauth");
};
?>
<style>
    #dark-container {
        display: block;
    }
</style>
<style><?php include "../css/change-text-info.css" ?></style>
<div class="textchange-container">
    <h1>Change username of uID <?php echo $_SESSION['displayprofile_userid'] ?>.</h1>
    <input id="username-input-change" type="text" placeholder="New username" maxlength="25">
    <button onclick="saveUsername(<?php echo $_SESSION['displayprofile_userid'] ?>)">Save</button> <br>
    <button onclick="removeDarkContainer()" id="cancel-button">Cancel</button>
</div>