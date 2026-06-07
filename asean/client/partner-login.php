<?php
// ✅ Extend session lifetime to 7 days
ini_set('session.gc_maxlifetime', 604800);        // 7 days in seconds
ini_set('session.cookie_lifetime', 604800);       // for the cookie lifetime
session_set_cookie_params(604800);                // apply to current session
session_start();

require_once __DIR__ . '/../includes/config.php';

// ❌ If client is logged in, log them out
if (isset($_SESSION['client_id'])) {
    unset($_SESSION['client_id']);
    unset($_SESSION['client_name']);
}

header('Content-Type: application/json');

function clean($data) {
    return htmlspecialchars(trim($data));
}

$email = clean($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(['success' => false, 'message' => 'Email and password are required.']);
    exit;
}

$stmt = $conn->prepare("SELECT id, password, approved, name, partner_uid FROM partners WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Account not found.']);
    exit;
}

$user = $result->fetch_assoc();

if (!$user['approved']) {
    echo json_encode(['success' => false, 'message' => 'Account not approved by admin.']);
    exit;
}

if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid password.']);
    exit;
}

// ✅ Set session
$_SESSION['partner_uid'] = $user['partner_uid'];
$_SESSION['partner_name'] = $user['name'];

echo json_encode(['success' => true, 'uid' => $user['partner_uid']]);
?>
