<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header("Location: client/login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-6 rounded shadow text-center">
    <h1 class="text-2xl font-bold mb-4">Welcome, <?= htmlspecialchars($_SESSION['client_name']) ?>!</h1>
    <a href="logout.php" class="text-red-500 hover:underline">Logout</a>
  </div>
<!-- for partner -->
  <form action="logout-partner.php" method="POST" style="display:inline;">
  <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">Logout</button>
</form>

</body>
</html>
