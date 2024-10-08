<?php
session_start();
include './config.php';
$conn = db_connect();

if (!isset($_SESSION['user_id'])) {
    die("You need to log in first.");
}

// Fetch the user's submitted enquiry
$stmt = $conn->prepare("SELECT * FROM enquiries WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Submitted Enquiry</title>
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

        .enquiry-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }

        .enquiry-detail {
            margin: 10px 0;
        }

        .enquiry-detail strong {
            color: #555;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-enquiry, .status-message {
            text-align: center;
            font-size: 18px;
            color: #777;
        }

        .accepted {
            color: green;
        }

        .rejected {
            color: red;
        }
    </style>
</head>
<body>

<div class="enquiry-container">
    <?php
    if ($result->num_rows > 0) {
        $enquiry = $result->fetch_assoc();
        
        echo "<h2>Your Submitted Enquiry</h2>";
        echo "<div class='enquiry-detail'><strong>Name:</strong> " . htmlspecialchars($enquiry['name']) . "</div>";
        echo "<div class='enquiry-detail'><strong>Email:</strong> " . htmlspecialchars($enquiry['email']) . "</div>";
        echo "<div class='enquiry-detail'><strong>Phone:</strong> " . htmlspecialchars($enquiry['phone']) . "</div>";
        echo "<div class='enquiry-detail'><strong>Subject:</strong> " . htmlspecialchars($enquiry['subject']) . "</div>";
        echo "<div class='enquiry-detail'><strong>Message:</strong><br>" . nl2br(htmlspecialchars($enquiry['message'])) . "</div>";
        echo "<div class='enquiry-detail'><strong>File:</strong> <a href='uploads/" . htmlspecialchars($enquiry['file']) . "'>Download</a></div>";
        echo "<div class='enquiry-detail'><strong>Submitted on:</strong> " . htmlspecialchars($enquiry['created_at']) . "</div>";
        
        // Display status
        $status = htmlspecialchars($enquiry['status']);
        if ($status === 'accepted') {
            echo "<div class='status-message accepted'>Your enquiry has been accepted.</div>";
        } elseif ($status === 'rejected') {
            echo "<div class='status-message rejected'>Your enquiry has been rejected.</div>";
        } else {
            echo "<div class='status-message'>Your enquiry is still pending.</div>";
        }

    } else {
        echo "<div class='no-enquiry'>No enquiry submitted.</div>";
    }
    ?>
</div>

</body>
</html>
