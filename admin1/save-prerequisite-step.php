<?php
include "../asean/includes/config.php";

$country = $_POST['country'] ?? '';
$service = $_POST['service'] ?? '';
$title = $_POST['title'] ?? '';
$step = $_POST['step'] ?? '';

if ($country && $service && $title && $step) {
  $stmt = $conn->prepare("INSERT INTO prerequisite_steps (country, service, prerequisite, step_text) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $country, $service, $title, $step);
  $stmt->execute();
}
?>
