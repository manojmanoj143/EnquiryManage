<?php
session_start();
include '../config.php';
$conn = db_connect();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    die("You need to log in as admin first.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Update status to rejected
    $stmt = $conn->prepare("UPDATE enquiries SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit;
    } else {
        echo "Error updating status: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
