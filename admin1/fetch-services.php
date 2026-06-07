<?php
require_once __DIR__ . '/../asean/includes/config.php';

$result = $conn->query("SELECT * FROM services");
$i = 1;

while ($row = $result->fetch_assoc()) {
  echo "<tr>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>{$i}</td>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>" . htmlspecialchars($row['country']) . "</td>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>" . htmlspecialchars($row['name']) . "</td>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>" . htmlspecialchars($row['description']) . "</td>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>";
  
  if (!empty($row['image'])) {
    echo "<img src='../images/{$row['image']}' alt='Service Image' class='w-16 h-16 object-cover rounded'>";
  } else {
    echo "No image";
  }

  echo "</td>
    <td class='px-5 py-3 border-b dark:border-darkmode-300'>
      <button onclick='deleteService({$row['id']})' class='bg-primary text-white px-4 py-2 rounded-md hover:bg-opacity-90 transition'>Delete</button>
    </td>
  </tr>";

  $i++;
}
?>
