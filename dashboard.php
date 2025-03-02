<?php
session_start();
include 'config.php';

// Ensure only logged-in admins can access
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Prevent unwanted output
ob_clean();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Admin Dashboard</h2>

        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Tickets</h5>
                        <h2 id="total_tickets">Loading...</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Open Tickets</h5>
                        <h2 id="open_tickets">Loading...</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Closed Tickets</h5>
                        <h2 id="closed_tickets">Loading...</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="view_tickets.php" class="btn btn-primary">View All Tickets</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </div>

    <script>
        function fetchTicketData() {
            $.ajax({
                url: 'fetch_ticket_data.php?nocache=' + new Date().getTime(),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log("AJAX Response:", data);
                    if (data.error) {
                        console.error("Error from Server: " + data.error);
                        $('#total_tickets').text("Error");
                        $('#open_tickets').text("Error");
                        $('#closed_tickets').text("Error");
                    } else {
                        $('#total_tickets').text(data.total_tickets);
                        $('#open_tickets').text(data.open_tickets);
                        $('#closed_tickets').text(data.closed_tickets);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    console.error("Server Response:", xhr.responseText);
                    $('#total_tickets').text("AJAX Error");
                    $('#open_tickets').text("AJAX Error");
                    $('#closed_tickets').text("AJAX Error");
                }
            });
        }

        $(document).ready(function() {
            fetchTicketData();
            setInterval(fetchTicketData, 5000);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
