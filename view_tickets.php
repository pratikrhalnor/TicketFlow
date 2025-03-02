<?php
include 'config.php';

$sql = "SELECT * FROM tickets ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Support Tickets</h2>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['subject']; ?></td>
                    <td><?php echo $row['message']; ?></td>
                    <td>
                        <span class="badge status-label 
                            <?php 
                            echo ($row['status'] == 'Closed') ? 'bg-success' : 
                                (($row['status'] == 'In Progress') ? 'bg-warning text-dark' : 'bg-danger'); ?>"
                            data-ticket-id="<?php echo $row['id']; ?>">
                            <?php echo $row['status']; ?>
                        </span>

                        <select class="form-select status-select mt-1" data-ticket-id="<?php echo $row['id']; ?>">
                            <option value="Open" <?php if ($row['status'] == 'Open') echo 'selected'; ?>>Open</option>
                            <option value="In Progress" <?php if ($row['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                            <option value="Closed" <?php if ($row['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
                        </select>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
$(document).ready(function() {
    $(".status-select").change(function() {
        var ticket_id = $(this).data("ticket-id");
        var new_status = $(this).val();
        var statusLabel = $(this).closest("td").find(".status-label");

        $.ajax({
            url: 'update_status.php',
            type: 'POST',
            data: { ticket_id: ticket_id, status: new_status },
            dataType: 'json',
            success: function(response) {
                console.log("Server Response:", response); // Debugging

                if (response.success) {
                    statusLabel.text(new_status);

                    // Change badge color
                    statusLabel.removeClass("bg-danger bg-warning bg-success");
                    if (new_status === "Closed") {
                        statusLabel.addClass("bg-success");
                    } else if (new_status === "In Progress") {
                        statusLabel.addClass("bg-warning text-dark");
                    } else {
                        statusLabel.addClass("bg-danger");
                    }
                } else {
                    alert("Error updating status: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", error);
                console.log("Server Response:", xhr.responseText);
                alert("AJAX request failed! Check console for details.");
            }
        });
    });
});

    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
