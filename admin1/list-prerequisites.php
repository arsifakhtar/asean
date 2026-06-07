<?php
require_once __DIR__ . '/../asean/includes/config.php';

$country = $_GET['country'] ?? '';
$service = $_GET['service'] ?? '';

$stmt = $conn->prepare("SELECT * FROM prerequisites WHERE country = ? AND service = ?");
$stmt->bind_param("ss", $country, $service);
$stmt->execute();
$prereqRes = $stmt->get_result();

while ($prereq = $prereqRes->fetch_assoc()):
    $prereqID = $prereq['id'];
    $prereqTitle = $prereq['title'];
    $prereqDesc = $prereq['description'];

    // Fetch associated steps
    $stmtSteps = $conn->prepare("SELECT id, step_text FROM prerequisite_steps WHERE country = ? AND service = ? AND prerequisite = ?");
    $stmtSteps->bind_param("sss", $country, $service, $prereqTitle);
    $stmtSteps->execute();
    $stepRes = $stmtSteps->get_result();
?>
  <div class="border border-gray-300 rounded p-4 bg-gray-50 mb-6">
    <div class="flex justify-between items-center mb-2">
      <h3 class="text-lg font-semibold text-purple-700">Prerequisite: <?= htmlspecialchars($prereqTitle) ?></h3>
      <button onclick="deletePrerequisite(<?= $prereqID ?>)" class="text-red-600 text-sm">Delete Prerequisite</button>
    </div>
    <p class="text-sm text-gray-700 mb-3"><?= nl2br(htmlspecialchars($prereqDesc)) ?></p>

    <div class="ml-4">
      <h4 class="text-md font-medium text-gray-800 mb-2">Steps:</h4>
      <?php if ($stepRes->num_rows > 0): ?>
        <ul class="space-y-2">
          <?php while ($step = $stepRes->fetch_assoc()): ?>
            <li class="flex justify-between items-center bg-white border p-2 rounded">
              <span><?= htmlspecialchars($step['step_text']) ?></span>
              <button onclick="deleteStep(<?= $step['id'] ?>)" class="text-red-600 text-xs ml-3">Delete Step</button>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-sm text-gray-500">No steps added yet for this prerequisite.</p>
      <?php endif; ?>
    </div>
  </div>
<?php endwhile; ?>
