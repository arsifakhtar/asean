<?php
require_once __DIR__ . '/../includes/config.php';

$query = "SELECT * FROM partners WHERE approved = 0 ORDER BY created_at DESC";
$result = $conn->query($query);

$partners = [];

while ($row = $result->fetch_assoc()) {
  $partners[] = $row;
}

echo json_encode($partners);
?>
