<?php
session_start();
require 'vendor/autoload.php'; // PHPMailer
require 'send_mail.php';          // your custom email function
require 'db.php';                 // your DB connection using $pdo

function generateUID() {
    return uniqid('client_', true); // e.g., client_664789ce0da33.12345
}

function generateOTP($length = 6) {
    return str_pad(rand(0, 999999), $length, '0', STR_PAD_LEFT);
}

// Sanitize inputs
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm = $_POST['confirm_password'];

// Basic validations
if (!$username || !$email || !$password || !$confirm) {
    die("All fields are required.");
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format.");
}
if (strlen($password) < 6) {
    die("Password must be at least 6 characters.");
}
if ($password !== $confirm) {
    die("Passwords do not match.");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$user_uid = generateUID();
$otp = generateOTP();
$expires = date('Y-m-d H:i:s', time() + 300); // 5 mins from now

try {
    $stmt = $pdo->prepare("INSERT INTO client (user_uid, username, email, password, otp_code, otp_expires) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_uid, $username, $email, $hashedPassword, $otp, $expires]);

    if (sendOTPEmail($email, $otp)) {
        $_SESSION['pending_email'] = $email;
        header("Location: verify-otp.html");
        exit;
    } else {
        die("Failed to send OTP. Please try again later.");
    }
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        die("Email is already registered.");
    } else {
        die("Database error: " . $e->getMessage());
    }
}
?>
