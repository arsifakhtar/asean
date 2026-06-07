<?php
require_once __DIR__ . '/../asean/includes/config.php';
$country = $_GET['country'];
$stmt = $conn->prepare("SELECT name FROM services WHERE country = ?");
$stmt->bind_param("s", $country);
$stmt->execute();
$result = $stmt->get_result();
$services = [];
while ($row = $result->fetch_assoc()) $services[] = $row;
echo json_encode($services);
