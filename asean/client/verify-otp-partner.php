<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require 'send_mail.php';

$email = $_SESSION['pending_partner_email'] ?? null;

if (!$email) {
    echo "<script>alert('Session expired or invalid access.'); history.back();</script>";
    exit;
}

// Generate OTP and expiry
$newOtp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiry = date('Y-m-d H:i:s', time() + 300);

// Update database
$stmt = $conn->prepare("UPDATE partners SET otp_code = ?, otp_expires = ? WHERE email = ?");
$stmt->bind_param("sss", $newOtp, $expiry, $email);

if ($stmt->execute()) {
    if (sendOTPEmail($email, $newOtp)) {
        echo "<script>alert('OTP resent successfully!'); window.location.href='verify-otp-partner.html';</script>";
    } else {
        echo "<script>alert('Failed to send OTP.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Failed to update OTP.'); history.back();</script>";
}
?>
