<?php
require_once __DIR__ . '/../asean/includes/config.php';
$id = $_GET['id'];
$conn->query("DELETE FROM services WHERE id = $id");
