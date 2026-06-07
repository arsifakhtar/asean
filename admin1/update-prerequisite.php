<?php
include "../asean/includes/config.php";
$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];
$stmt = $conn->prepare("UPDATE prerequisites SET title = ?, description = ? WHERE id = ?");
$stmt->bind_param("ssi", $title, $description, $id);
$stmt->execute();
?>
