<?php
session_start();
require 'db.php';

$email = $_SESSION['pending_email'] ?? null;
$enteredOtp = trim($_POST['otp']);

if (!$email || !$enteredOtp) {
    die("Invalid request.");
}

$stmt = $pdo->prepare("SELECT user_uid, otp_code, otp_expires FROM client WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

$now = time();
$otpExpiry = strtotime($user['otp_expires']);

if ($user['otp_code'] !== $enteredOtp) {
    die("Incorrect OTP.");
}

if ($otpExpiry < $now) {
    die("OTP has expired.");
}

// Mark the user as verified
$update = $pdo->prepare("UPDATE client SET is_verified = 1, otp_code = NULL, otp_expires = NULL WHERE email = ?");
$update->execute([$email]);

unset($_SESSION['pending_email']);

// Redirect to welcome page with unique user ID
// header("Location: welcome.php?uid=" . urlencode($user['user_uid']));
header("Location: login.html");
exit;
?>
