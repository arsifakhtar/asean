=<?php
session_start();
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['partner_uid'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$uid = $_SESSION['partner_uid'];

$stmt = $conn->prepare("SELECT name, designation, country, state, phone, about, expertise, profile_image, public_email, address FROM partners WHERE partner_uid = ?");
$stmt->bind_param("s", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(['success' => true, 'profile' => $row]);
} else {
    echo json_encode(['success' => false, 'message' => 'Profile not found']);
}
