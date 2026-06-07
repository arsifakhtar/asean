<?php
include "../asean/includes/config.php";
$id = $_POST['id'];
$text = $_POST['step_text'];
$stmt = $conn->prepare("UPDATE prerequisite_steps SET step_text = ? WHERE id = ?");
$stmt->bind_param("si", $text, $id);
$stmt->execute();
?>
