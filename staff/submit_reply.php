<?php
session_start();
include('conf/config.php');
include('conf/checklogin.php');
check_login();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["feedback_id"]) && isset($_POST["reply"])) {
    $feedback_id = intval($_POST["feedback_id"]);
    $reply = $mysqli->real_escape_string($_POST["reply"]);

    // Update the reply in the database
    $query = "UPDATE client_feedback SET reply = '$reply' WHERE id = $feedback_id";
    
    if ($mysqli->query($query)) {
        $_SESSION['success'] = "Reply submitted successfully!";
    } else {
        $_SESSION['error'] = "Failed to submit reply: " . $mysqli->error;
    }
}

header("Location: pages_feedback_view.php");
exit();
?>
