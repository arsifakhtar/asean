<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/send_mail.php';
session_start();

function clean($data) {
    return htmlspecialchars(trim($data));
}

// Get and sanitize input
$name = clean($_POST['name'] ?? '');
$designation = clean($_POST['designation'] ?? '');
$country = clean($_POST['country'] ?? '');
$state = clean($_POST['state'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$about = clean($_POST['about'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';
$expertise = implode(', ', array_map('clean', $_POST['expertise'] ?? []));

// Validate passwords
if ($password !== $confirm) {
    echo "<script>alert('Passwords do not match.'); history.back();</script>";
    exit;
}
if (strlen($password) < 6) {
    echo "<script>alert('Password must be at least 6 characters.'); history.back();</script>";
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$uid = uniqid('partner_', true);
$otp = rand(100000, 999999);
$expires = date('Y-m-d H:i:s', time() + 300);

// Handle image upload
$image = '';
if (!empty($_FILES['profile_image']['name'])) {
    $target = "../uploads/";
    if (!file_exists($target)) mkdir($target, 0777, true);
    $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
    $image = uniqid("img_", true) . "." . $ext;
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $target . $image);
}

// Check if email already exists
$check = $conn->prepare("SELECT id FROM partners WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();
if ($check->num_rows > 0) {
    echo "<script>alert('Email already exists.'); history.back();</script>";
    exit;
}

// Insert new partner
$stmt = $conn->prepare("INSERT INTO partners (partner_uid, name, designation, country, state, email, password, phone, about, expertise, profile_image, otp_code, otp_expires) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssssss", $uid, $name, $designation, $country, $state, $email, $hashed, $phone, $about, $expertise, $image, $otp, $expires);

if ($stmt->execute()) {
    if (sendOTPEmail($email, $otp)) {
        $_SESSION['pending_partner_email'] = $email;
        echo "<script>alert('OTP sent to your email. Please verify.'); window.location.href='verify-otp-partner.html';</script>";
        exit;
    } else {
        echo "<script>alert('Failed to send OTP. Please try again.'); history.back();</script>";
    }
} else {
    echo "<script>alert('Something went wrong while signing up.'); history.back();</script>";
}
