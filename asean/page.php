<?php
include "includes/config.php";

$country = $_GET['country'] ?? '';
$stmt = $conn->prepare("SELECT * FROM countries WHERE name = ?");
$stmt->bind_param("s", $country);
$stmt->execute();
$result = $stmt->get_result();

if (!$row = $result->fetch_assoc()) {
    echo "<h1>Country not found.</h1>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($row['name']) ?> Services</title>
  <link href="./output.css" rel="stylesheet" />
</head>
<body>
  <div class="container mx-auto px-4 py-8">
    <!-- Country Header -->
    <div class="rounded-lg p-6 mb-8">
      <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
        <div class="w-full md:w-64">
          <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="w-full" />
        </div>
        <div class="flex-1">
          <h1 class="text-3xl font-bold mb-4"><?= htmlspecialchars($row['name']) ?></h1>
          <p class="text-gray-600">
            Unleash your brand's potential with MetaUpSpace: Results-driven solutions for your business growth and success. We provide comprehensive strategies tailored to your needs.
          </p>
        </div>
        <div class="hidden md:block">
          <img src="images/logo.png" alt="ASEAN Logo" class="w-24 mt-[-100px] h-24 object-contain"/>
        </div>
      </div>
    </div>

    <!-- Services Section -->
    <div class="rounded-lg p-6">
      <h2 class="text-2xl font-bold mb-6">Services we offer to our clients</h2>
      <p class="text-gray-600 mb-8">
        This program brings tech education to students all over the country. It covers important topics like cybersecurity, AI, and web development. Students can join workshops and hackathons and get advice from industry experts.
      </p>

      <!-- Services Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php
        $services_stmt = $conn->prepare("SELECT name, description, image FROM services WHERE country = ?");
        $services_stmt->bind_param("s", $row['name']);
        $services_stmt->execute();
        $services_result = $services_stmt->get_result();

        while ($service = $services_result->fetch_assoc()):
        ?>
        <a href="services.php?country=<?= urlencode($row['name']) ?>&service=<?= urlencode($service['name']) ?>">
          <div class="flex justify-between items-center border rounded-lg p-4 hover:shadow-md transition bg-white gap-4">
            <div class="flex-1">
              <h3 class="text-lg font-semibold mb-2"><?= htmlspecialchars($service['name']) ?></h3>
              <p class="text-gray-600 text-sm"><?= htmlspecialchars($service['description']) ?></p>
            </div>
            <?php if (!empty($service['image'])): ?>
              <div class="w-24 h-24 flex-shrink-0">
                <img src="../images/<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['name']) ?>" class="w-full h-full object-cover rounded" />
              </div>
            <?php endif; ?>
          </div>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</body>
</html>
