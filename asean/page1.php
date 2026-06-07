<?php
include "includes/config.php";

$country = $_GET['country'] ?? '';

$stmt = $conn->prepare("SELECT * FROM countries WHERE name = ?");
$stmt->bind_param("s", $country);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()):
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($row['name']) ?></title>
    <link href="./output.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold"><?= htmlspecialchars($row['name']) ?></h1>
        <img src="./images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-96 my-4">
        <p class="text-gray-700"><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    </div>
</body>
</html>
<?php else: ?>
    <p>Country not found.</p>
<?php endif; ?>
