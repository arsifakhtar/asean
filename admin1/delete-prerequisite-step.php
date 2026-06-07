<?php
// Connect to database
require_once __DIR__ . '/../asean/includes/config.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo "Invalid request.";
    exit;
}

$id = (int) $_GET['id'];

// Delete the step using a prepared statement
$stmt = $conn->prepare("DELETE FROM prerequisite_steps WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Step deleted successfully.";
} else {
    http_response_code(500);
    echo "Failed to delete step.";
}
?>
