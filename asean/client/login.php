<?php
// ✅ Extend session lifetime to 7 days
ini_set('session.gc_maxlifetime', 604800);        // 7 days
ini_set('session.cookie_lifetime', 604800);
session_set_cookie_params(604800);
session_start();

require_once '../includes/config.php';

// ❌ If partner is logged in, log them out
if (isset($_SESSION['partner_uid'])) {
    unset($_SESSION['partner_uid']);
    unset($_SESSION['partner_name']);
}

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if ($email && $password) {
    $stmt = $conn->prepare("SELECT id, username, password FROM client WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['client_id'] = $row['id'];
            $_SESSION['client_name'] = $row['username'];
            
            echo json_encode(['success' => true]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
            exit;
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}
