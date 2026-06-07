<?php
require_once __DIR__ . '/../asean/includes/config.php';

$country = $_POST['country'] ?? '';
$name = $_POST['name'] ?? '';
$description = $_POST['description'] ?? '';
$imageName = '';

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
  $uploadDir = '../images/';
  $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
  $newName = uniqid('service_', true) . '.' . $ext;

  if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
  }

  if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $newName)) {
    $imageName = $newName;
  }
}

$stmt = $conn->prepare("INSERT INTO services (country, name, description, image) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $country, $name, $description, $imageName);
$stmt->execute();
