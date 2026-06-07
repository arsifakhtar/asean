<?php
include "../asean/includes/config.php";

$id = $_GET['id'] ?? '';
if ($id) {
    $stmt = $conn->prepare("DELETE FROM steps WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
?>
