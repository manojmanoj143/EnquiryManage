<?php
session_start();
include 'config.php';
$conn = db_connect();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all enquiries
$enquiries = $conn->query("SELECT * FROM enquiries ORDER BY created_at DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Enquiries</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        .accept {
            background-color: #28a745; /* Green */
        }

        .reject {
            background-color: #dc3545; /* Red */
        }

        .no-enquiries {
            text-align: center;
            font-size: 18px;
            color: #777;
        }
    </style>
</head>
<body>

<h2>All Enquiries</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Status</th>
        <th>File</th>
        <th>Actions</th>
    </tr>
    <?php if ($enquiries->num_rows > 0): ?>
        <?php while ($row = $enquiries->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                <td><?php echo htmlspecialchars($row['message']); ?></td>
                <td><?php echo htmlspecialchars($row['status']); ?></td>
                <td>
                    <?php if ($row['file']): ?>
                        <a href="uploads/<?php echo htmlspecialchars($row['file']); ?>" download>Download</a>
                    <?php endif; ?>
                </td>
                <td>
                    <form method="POST" action="update_enquiry.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit" class="action-btn accept">Accept</button>
                    </form>
                    <form method="POST" action="update_enquiry.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="action-btn reject">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="9" class="no-enquiries">No enquiries found.</td>
        </tr>
    <?php endif; ?>
</table>

<a href="admin_dashboard.php">Back to Dashboard</a>

</body>
</html>
