<?php
// Example passwords
$passwords = ['password1', 'password2'];

foreach ($passwords as $password) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    echo "Plain Password: $password | Hashed Password: $hashed_password\n";
}
?>
