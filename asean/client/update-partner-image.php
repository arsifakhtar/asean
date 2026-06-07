<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['partner_uid'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$uid = $_SESSION['partner_uid'];

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $newFileName = uniqid() . '.' . $ext;
    $uploadPath = __DIR__ . '/../uploads/' . $newFileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
        $stmt = $conn->prepare("UPDATE partners SET profile_image = ? WHERE partner_uid = ?");
        $stmt->bind_param("ss", $newFileName, $uid);
        $stmt->execute();

        echo json_encode(['success' => true, 'image' => $newFileName]);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Upload failed']);
