<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/send_mail.php';

$email = $_SESSION['pending_email'] ?? null;

if (!$email) {
    echo "<script>alert('Session expired. Please sign up again.'); window.location.href = 'signup-partner.html';</script>";
    exit;
}

$newOtp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiry = date('Y-m-d H:i:s', time() + 300); // 5 minutes

// Update OTP in database
$stmt = $conn->prepare("UPDATE partners SET otp_code = ?, otp_expires = ? WHERE email = ?");
$stmt->bind_param("sss", $newOtp, $expiry, $email);

if ($stmt->execute()) {
    $sent = sendOTPEmail($email, $newOtp);

    if ($sent) {
        echo "<script>alert('A new OTP has been sent to your email.'); window.location.href = 'verify-otp-partner.html';</script>";
        error_log("Partner OTP failed for $email");
    } else {
        echo "<script>alert('Failed to send OTP email. Check your email settings.'); window.location.href = 'verify-otp-partner.html';</script>";
    }
} else {
    echo "<script>alert('Database update failed.'); history.back();</script>";
}
?>
