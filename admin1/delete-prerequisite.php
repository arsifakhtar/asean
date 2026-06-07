<?php
require_once __DIR__ . '/../asean/includes/config.php';

$id = $_GET['id'];

// Get prerequisite title to remove steps too
$stmt = $conn->prepare("SELECT title, country, service FROM prerequisites WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $title = $row['title'];
    $country = $row['country'];
    $service = $row['service'];

    // Delete steps first
    $stmtSteps = $conn->prepare("DELETE FROM prerequisite_steps WHERE country = ? AND service = ? AND prerequisite = ?");
    $stmtSteps->bind_param("sss", $country, $service, $title);
    $stmtSteps->execute();

    // Delete the prerequisite
    $conn->query("DELETE FROM prerequisites WHERE id = $id");
}
