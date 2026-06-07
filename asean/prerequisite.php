<?php
include "includes/config.php";

$country = $_GET['country'] ?? '';
$service = $_GET['service'] ?? '';
$prerequisite = $_GET['prerequisite'] ?? '';

if (!$country || !$service || !$prerequisite) {
    echo "<h1>Missing parameters.</h1>";
    exit;
}

// Get country info
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($prerequisite) ?> - <?= htmlspecialchars($country) ?></title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="">

<div class="container mx-auto px-4 py-8">
    <div class="rounded-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="w-full md:w-64">
                <!-- Country Image -->
                <img src="./images/<?= htmlspecialchars($row['image']) ?>" alt="Flag of <?= htmlspecialchars($country) ?>" class="w-full h-48 object-cover rounded-md">
            </div>
            
            <div class="flex-1">
                <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($prerequisite) ?></h1>
                <h2 class="text-2xl mb-4"><?= htmlspecialchars($country) ?></h2>
                <p class="text-gray-600 mb-4">
                    Discover the steps for completing the <?= htmlspecialchars($prerequisite) ?> for the service <strong><?= htmlspecialchars($service) ?></strong> in <?= htmlspecialchars($country) ?>. 
                    Our expert consultants guide you through every detail.
                </p>
            </div>
            
            <div class="hidden md:block">
                <img src="./images/logo.png" alt="ASEAN Logo" class="w-24 mt-[-170px] h-24 object-contain"/>
            </div>
        </div>
    </div>

    <div class="rounded-lg p-6">
        <div class="text-center mb-8 mt-[-50px]">
            <p class="px-8 py-3 rounded-full text-lg lg:text-2xl  font-bold transition-colors">
                <?= htmlspecialchars($service) ?> Registration Process
            </p>
        </div>

        <p class="text-gray-700 mb-8">
            Registering a <?= htmlspecialchars($service) ?> is crucial for businesses aiming for growth in <?= htmlspecialchars($country) ?>. Here's a step-by-step guide to help you complete this process.
        </p>

        <div class="space-y-4">
            <?php
            // Fetch prerequisite steps from the database
            $stmt = $conn->prepare("SELECT step_text FROM prerequisite_steps WHERE country = ? AND service = ? AND prerequisite = ?");
            $stmt->bind_param("sss", $country, $service, $prerequisite);
            $stmt->execute();
            $steps = $stmt->get_result();
            ?>

            <?php if ($steps->num_rows > 0): ?>
                <?php while ($step = $steps->fetch_assoc()): ?>
                    <div class="bg-gray-100 transition duration-300 ease-in-out hover:shadow-lg hover:scale-105 border border-black rounded-lg p-4">
                        <p class="text-gray-800">
                            <span class="font-semibold"><?= htmlspecialchars($step['step_text']) ?></span>
                        </p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-500 text-center">No steps added for this prerequisite yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="flex justify-center mb-8">
        <button class="bg-black text-white px-5 py-2 rounded-full text-sm font-semibold transition-colors">
            Read More
        </button>
    </div>

    <!-- Team Section -->
    <div class="rounded-lg p-6 mt-8">
        <h2 class="text-3xl font-bold text-center mb-10">Team of Expert Consultants</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
            <?php
            // Example loop to display team members
            for ($i = 0; $i < 5; $i++) {
                echo '
                    <div class="text-center shadow-lg border border-black p-5">
                        <img src="./images/image (1).png" alt="Consultant" class="w-full h-48 object-cover rounded-lg mb-4"/>
                        <h3 class="font-semibold mb-2">Consultant ' . ($i + 1) . '</h3>
                        <p class="text-gray-600 text-sm mb-4">Expert in business strategies, market growth, and legal processes.</p>
                        <button class="bg-black text-white px-6 py-2 rounded-full text-sm hover:bg-gray-800 transition-colors">Read More →</button>
                    </div>
                ';
            }
            ?>
        </div>
    </div>

</div>
</body>
</html>
