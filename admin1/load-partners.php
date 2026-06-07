<?php
require_once "../asean/includes/config.php";

$query = "SELECT * FROM partners WHERE approved = 0";
$result = $conn->query($query);

$partners = [];

while ($row = $result->fetch_assoc()) {
    $partners[] = [
        "id" => $row["id"],
        "name" => $row["name"],
        "designation" => $row["designation"],
        "country" => $row["country"],
        "state" => $row["state"],
        "email" => $row["email"],
        "phone" => $row["phone"],
        "about" => $row["about"],
        "expertise" => $row["expertise"],
        "profile_image" => $row["profile_image"]
    ];
}

header("Content-Type: application/json");
echo json_encode($partners);
?>
