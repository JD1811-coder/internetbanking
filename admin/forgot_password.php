<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password</title>
</head>
<body>
    <h2>Forgot Password</h2>
    <form action="send_otp.php" method="post">
        <input type="text" name="phone" placeholder="Enter your phone number" required>
        <button type="submit" name="send_otp">Send OTP</button>
    </form>
</body>
</html>
