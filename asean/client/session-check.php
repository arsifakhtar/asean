<?php
session_start();

if (isset($_SESSION['client_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'type' => 'client',
        'name' => $_SESSION['client_name'],
        'email' => $_SESSION['client_email'] ?? ''  // ✅ Include email
    ]);
} elseif (isset($_SESSION['partner_id'])) {
    echo json_encode([
        'loggedIn' => true,
        'type' => 'partner',
        'name' => $_SESSION['partner_name']
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>
