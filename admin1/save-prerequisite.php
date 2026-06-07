<?php
include "../asean/includes/config.php";

$country = $_POST['country'] ?? '';
$service = $_POST['service'] ?? '';
$title = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';

if ($country && $service && $title && $description) {
    $stmt = $conn->prepare("INSERT INTO prerequisites (country, service, title, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $country, $service, $title, $description);
    $stmt->execute();
}
?>
