<?php
header('Content-Type: application/json'); // Ensure JSON response
include 'config.php';

// Prevent accidental output before JSON
ob_clean();
$response = ["success" => false, "error" => "Invalid request"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["ticket_id"]) || !isset($_POST["status"])) {
        $response["error"] = "Missing parameters!";
        echo json_encode($response);
        exit;
    }

    $ticket_id = $_POST["ticket_id"];
    $new_status = $_POST["status"];

    // Validate ticket_id (Ensure it's a number)
    if (!is_numeric($ticket_id)) {
        $response["error"] = "Invalid ticket ID!";
        echo json_encode($response);
        exit;
    }

    // Update query
    $query = "UPDATE tickets SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_status, $ticket_id);

    if ($stmt->execute()) {
        $response = ["success" => true, "message" => "Status updated successfully!"];
    } else {
        $response["error"] = "Database error: " . $stmt->error;
    }

    $stmt->close();
}

echo json_encode($response);
exit;
?>
