<?php
session_start();
include '../config.php';
$conn = db_connect();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    die("You need to log in as admin first.");
}

// Fetch all enquiries
$query = "SELECT * FROM enquiries";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$enquiries_message = ($result->num_rows === 0) ? "No enquiries found." : "";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style1.css">
    <style>
        /* Add styles as needed */
    </style>
</head>
<body>
    <h2>All Enquiries</h2>

    <?php if ($enquiries_message): ?>
        <div><?php echo $enquiries_message; ?></div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                        <td>
                            <a href="admin_accept_enquiry.php?id=<?php echo $row['id']; ?>">Accept</a> |
                            <a href="admin_reject_enquiry.php?id=<?php echo $row['id']; ?>">Reject</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
