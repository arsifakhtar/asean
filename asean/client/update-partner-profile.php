<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['partner_uid'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$uid = $_SESSION['partner_uid'];
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';

$allowed_fields = ['name', 'designation', 'country', 'state', 'phone', 'about', 'expertise', 'public_email', 'address'];

if (!in_array($field, $allowed_fields)) {
    echo json_encode(['success' => false, 'message' => 'Invalid field']);
    exit;
}

$stmt = $conn->prepare("UPDATE partners SET `$field` = ? WHERE partner_uid = ?");
$stmt->bind_param("ss", $value, $uid);
$success = $stmt->execute();

echo json_encode(['success' => $success]);
