<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; 
$conn = db_connect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT user_id, username, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $admin_username, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['admin_logged_in'] = true; // Set session variable for admin login
            $_SESSION['admin_username'] = $admin_username; // Store username
            $_SESSION['user_id'] = $user_id; // Store user ID

            // Redirect to the dashboard
            header("Location: admin_dashboard.php"); // Ensure this points to the correct file
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style1.css"> <!-- Ensure this path is correct -->
    <title>Admin Login</title>
</head>
<body>
    <form method="POST">
        <h2>Login</h2>
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
