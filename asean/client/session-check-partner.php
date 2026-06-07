<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['partner_uid'])) {
    echo json_encode([
        'loggedIn' => true,
        'name' => $_SESSION['partner_name'] ?? 'Partner'
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
