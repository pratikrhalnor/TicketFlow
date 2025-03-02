<?php
session_start();
include 'config.php';

header('Content-Type: application/json');
ob_clean(); // Clear any unwanted output
ob_start(); // Start output buffering

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    echo json_encode(["error" => "Unauthorized access"]);
    exit();
}

// Check database connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Database Connection Failed: " . $conn->connect_error]);
    exit();
}

// Fetch total tickets
$total_query = "SELECT COUNT(*) as total FROM tickets";
$total_result = $conn->query($total_query);
$total_tickets = ($total_result && $total_result->num_rows > 0) ? $total_result->fetch_assoc()['total'] : 0;

// Fetch tickets by status
$status_counts = ['Open' => 0, 'In Progress' => 0, 'Closed' => 0];
$status_query = "SELECT status, COUNT(*) as count FROM tickets GROUP BY status";
$status_result = $conn->query($status_query);

if ($status_result) {
    while ($row = $status_result->fetch_assoc()) {
        if (isset($status_counts[$row['status']])) {
            $status_counts[$row['status']] = $row['count'];
        }
    }
}

// Return JSON response
echo json_encode([
    "total_tickets" => $total_tickets,
    "open_tickets" => $status_counts['Open'],
    "closed_tickets" => $status_counts['Closed']
]);

$conn->close();
ob_end_flush(); // Flush output buffer
?>
