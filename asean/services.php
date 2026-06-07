<?php
include "includes/config.php";

$country = $_GET['country'] ?? '';
$service = $_GET['service'] ?? '';

if (!$country || !$service) {
    echo "<h1>Invalid parameters.</h1>";
    exit;
}

// Validate country
$stmt = $conn->prepare("SELECT * FROM countries WHERE name = ?");
$stmt->bind_param("s", $country);
$stmt->execute();
$result = $stmt->get_result();
if (!$row = $result->fetch_assoc()) {
    echo "<h1>Country not found.</h1>";
    exit;
}

// Validate service
$stmt2 = $conn->prepare("SELECT * FROM services WHERE country = ? AND name = ?");
$stmt2->bind_param("ss", $country, $service);
$stmt2->execute();
$serviceRes = $stmt2->get_result();
if (!$serviceRow = $serviceRes->fetch_assoc()) {
    echo "<h1>Service not found.</h1>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($country) ?> - <?= htmlspecialchars($service) ?></title>
  <link href="./output.css" rel="stylesheet"/>
</head>
<body class="">
  <div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="rounded-lg p-6 mb-8">
      <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
        <div class="w-full md:w-64">
          <img src="./images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($country) ?>" class="w-full">
        </div>
        <div class="flex-1">
          <h1 class="mb-7 text-3xl font-bold"><?= htmlspecialchars($serviceRow['name']) ?></h1>
          <h2 class="text-2xl mb-4"><?= htmlspecialchars($country) ?></h2>
          <p class="text-gray-600"><?= nl2br(htmlspecialchars($serviceRow['description'])) ?></p>
        </div>
        <div class="hidden md:block">
          <img src="./images/logo.png" alt="ASEAN Logo" class="w-24 mt-[-170px] h-24 object-contain"/>
        </div>
      </div>
    </div>

    <!-- Service Details Header -->
    <div class="rounded-lg p-6">
      <div class="text-center mb-8 mt-[-50px]">
        <p class="px-8 py-3 rounded-full text-lg lg:text-2xl font-bold transition-colors">
          <?= htmlspecialchars($service) ?> SERVICE DETAILS
        </p>
      </div>

      <p class="text-gray-700 mb-8">
        Registering this service offers legal acceptance, brand credibility, and strategic benefits tailored for the region of <?= htmlspecialchars($country) ?>.
      </p>

      <!-- Dynamic Steps -->
      <div class="space-y-4">
        <?php
        $stmt3 = $conn->prepare("SELECT * FROM steps WHERE country = ? AND service = ?");
        $stmt3->bind_param("ss", $country, $service);
        $stmt3->execute();
        $res3 = $stmt3->get_result();
        $stepCount = 1;

        if ($res3->num_rows === 0): ?>
          <p class='text-gray-600'>No steps added yet for this service.</p>
        <?php endif;

        while ($step = $res3->fetch_assoc()): ?>
          <div class="bg-gray-100 hover:shadow-lg hover:scale-105 transition border border-black rounded-lg p-4">
            <p class="text-gray-800">
              <span class="font-semibold"><?= $stepCount++ ?>.</span> <?= htmlspecialchars($step['step_text']) ?>
            </p>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

    <!-- Read More Button -->
    <div class="flex justify-center mb-8">
      <button class="bg-black text-white px-5 py-2 rounded-full text-sm font-semibold transition-colors">
        Read More
      </button>
    </div>

    <!-- Prerequisite Section -->
    <div class="flex justify-center mb-8">
      <button class="bg-purple-600 text-white px-8 py-3 rounded-full text-lg font-semibold hover:bg-purple-700 transition-colors">
        PRE - REQUISITES
      </button>
    </div>

    <!-- Prerequisite Cards -->
    <?php
    $preq = $conn->prepare("SELECT title, description FROM prerequisites WHERE country = ? AND service = ?");
    $preq->bind_param("ss", $country, $service);
    $preq->execute();
    $prereqResult = $preq->get_result();
    ?>

    <?php if ($prereqResult->num_rows > 0): ?>
    <div class="rounded-lg p-6 mt-8">
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3 lg:grid-cols-2">
        <?php while ($pr = $prereqResult->fetch_assoc()): ?>
        <a href="prerequisite.php?country=<?= urlencode($country) ?>&service=<?= urlencode($service) ?>&prerequisite=<?= urlencode($pr['title']) ?>" class="block">
          <div class="bg-gray-50 p-6 border border-black rounded-lg hover:shadow-lg hover:scale-105 hover:bg-white flex flex-col justify-between">
            <div>
              <div class="flex justify-between items-start mb-4">
                <h3 class="font-semibold"><?= htmlspecialchars($pr['title']) ?></h3>
                <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                </div>
              </div>
              <p class="text-gray-600 text-sm"><?= htmlspecialchars($pr['description']) ?></p>
            </div>
            <div class="flex justify-end mt-6">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </div>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
    <?php else: ?>
      <p class="text-gray-600 px-6">No prerequisites added yet for this service.</p>
    <?php endif; ?>

    <!-- Team Section -->
    <div class="rounded-lg p-6 mt-8">
      <h2 class="text-3xl font-bold text-center mb-10">Team of Expert Consultants</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <?php for ($i = 1; $i <= 5; $i++): ?>
        <div class="text-center shadow-lg border border-black p-5">
          <img src="./images/image (1).png" alt="Kunal Deb" class="w-full h-48 object-cover rounded-lg mb-4"/>
          <h3 class="font-semibold mb-2">Kunal Deb</h3>
          <p class="text-gray-600 text-sm mb-4">
            Results-driven solutions for tech, marketing and production.
          </p>
          <button class="bg-black text-white px-6 py-2 rounded-full text-sm hover:bg-gray-800 transition-colors">Read More →</button>
        </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>
</body>
</html>
