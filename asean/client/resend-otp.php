<?php
session_start();
require 'db.php';
require 'send_mail.php';

$email = $_SESSION['pending_email'] ?? null;
if (!$email) {
    http_response_code(400);
    exit("Session expired or email not found.");
}

// Generate new OTP
$newOtp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
$expiry = date('Y-m-d H:i:s', time() + 300); // 5 minutes from now

try {
    // Update OTP in database
    $stmt = $pdo->prepare("UPDATE client SET otp_code = ?, otp_expires = ? WHERE email = ?");
    $stmt->execute([$newOtp, $expiry, $email]);

    // Send email
    if (sendOTPEmail($email, $newOtp)) {
        echo "OTP resent successfully!";
    } else {
        http_response_code(500);
        echo "Failed to send OTP. Please try again.";
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo "Database error: " . $e->getMessage();
}
?>
