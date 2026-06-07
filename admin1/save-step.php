<?php
require_once __DIR__ . '/../asean/includes/config.php';
if ($conn->connect_error) die("Connection failed");

$country = $_POST['country'] ?? '';
$service = $_POST['service'] ?? '';
$step = $_POST['step'] ?? '';

if ($country && $service && $step) {
  $stmt = $conn->prepare("INSERT INTO steps (country, service, step_text) VALUES (?, ?, ?)");
  $stmt->bind_param("sss", $country, $service, $step);
  $stmt->execute();
}
?>
