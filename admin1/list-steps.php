<?php
require_once __DIR__ . '/../asean/includes/config.php';
$country = $_GET['country'];
$service = $_GET['service'];
$stmt = $conn->prepare("SELECT id, step_text FROM steps WHERE country = ? AND service = ?");
$stmt->bind_param("ss", $country, $service);
$stmt->execute();
$res = $stmt->get_result();
$i = 1;
while ($row = $res->fetch_assoc()) {
  echo "<div class='flex justify-between items-center border p-3 rounded'>
          <span><strong>{$i}.</strong> " . htmlspecialchars($row['step_text']) . "</span>
          <button onclick='deleteStep({$row['id']})' class='text-red-600 font-semibold'>Delete</button>
        </div>";
  $i++;
}
