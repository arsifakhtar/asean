<?php
require_once __DIR__ . '/../asean/includes/config.php';
$res = $conn->query("SELECT name FROM countries");
$countries = [];
while ($row = $res->fetch_assoc()) $countries[] = $row;
echo json_encode($countries);
