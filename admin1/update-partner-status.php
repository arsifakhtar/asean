<?php
require_once "../asean/includes/config.php";

$id = $_POST['id'] ?? 0;
$approved = $_POST['approved'] ?? 0;

$stmt = $conn->prepare("UPDATE partners SET approved = ? WHERE id = ?");
$stmt->bind_param("ii", $approved, $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
