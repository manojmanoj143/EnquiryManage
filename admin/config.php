<?php
function db_connect() {
    $conn = new mysqli("localhost", "root", "", "ems_admin");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}
?>
