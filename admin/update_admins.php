<?php
include 'config.php'; // Include the database configuration file

$conn = db_connect(); // Establish database connection

// Step 1: Delete existing records
$delete_query = "DELETE FROM admins";
$conn->query($delete_query);

// Step 2: Insert new records
$admins = [
    ['username' => 'admin1', 'password' => 'password1', 'email' => 'admin1@example.com', 'phone' => '1234567890'],
    ['username' => 'admin2', 'password' => 'password2', 'email' => 'admin2@example.com', 'phone' => '0987654321'],
];

foreach ($admins as $admin) {
    // Hash the password
    $hashed_password = password_hash($admin['password'], PASSWORD_DEFAULT);

    // Prepare and execute insert statement
    $stmt = $conn->prepare("INSERT INTO admins (username, password, email, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $admin['username'], $hashed_password, $admin['email'], $admin['phone']);
    $stmt->execute();
}

$conn->close(); // Close the connection
echo "Admins updated successfully!";
?>
