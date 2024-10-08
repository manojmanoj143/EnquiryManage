<?php
session_start();
include 'config.php';
require './vendor/autoload.php'; // Make sure this path is correct

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    die("You need to log in first.");
}

// Check if the user has already submitted an enquiry
$stmt = $conn->prepare("SELECT * FROM enquiries WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User has already submitted an enquiry, redirect to view page
    header("Location: view_enquiries.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $file = $_FILES['file']['name'];

    // File upload handling
    if (move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $file)) {
        $stmt = $conn->prepare("INSERT INTO enquiries (user_id, name, email, phone, subject, message, file) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssss", $_SESSION['user_id'], $name, $email, $phone, $subject, $message, $file);
        
        if ($stmt->execute()) {

            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'kvincenzo391@gmail.com'; // Your Gmail
                $mail->Password   = 'egsi rmxd gpfq jqnb'; // Your app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom('kvincenzo391@gmail.com', 'Enquiry Notification');
                $mail->addAddress('kvincenzo391@gmail.com'); // Add your email address

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'New Enquiry Submitted';
                $mail->Body    = "<p>Name: $name</p>
                                  <p>Email: $email</p>
                                  <p>Phone: $phone</p>
                                  <p>Subject: $subject</p>
                                  <p>Message: $message</p>
                                  <p>File: <a href='uploads/$file'>Download</a></p>";

                $mail->send();
                
                // Redirect to the view page upon successful submission
                header("Location: view_enquiries.php");
                exit();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo "Error submitting enquiry: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "File upload failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Enquiry</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin: 10px 0;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Submit Your Enquiry</h2>
    <form method="POST" enctype="multipart/form-data">
        Name: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        Phone: <input type="text" name="phone" required><br>
        Subject: <input type="text" name="subject" required><br>
        Message: <textarea name="message" required></textarea><br>
        File: <input type="file" name="file" accept=".pdf,.docx" required><br>
        <input type="submit" value="Submit Enquiry">
    </form>
</body>
</html>
