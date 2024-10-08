<?php
require './vendor/autoload.php'; // Adjust the path if necessary

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Create an instance of PHPMailer
$mail = new PHPMailer(true); // Enable exceptions

try {
    //Server settings
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                 // Enable SMTP authentication
    $mail->Username   = 'kvincenzo391@gmail.com';          // Your new Gmail address
    $mail->Password   = 'egsi rmxd gpfq jqnb';           // Your new Gmail password or app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;      // Enable TLS encryption
    $mail->Port       = 587;                                  // TCP port to connect to

    // Recipients
    $mail->setFrom('your_new_email@gmail.com', 'Mailer');    // Your new email address
    $mail->addAddress('manoj.k88680@gmail.com', 'Manoj');     // Add a recipient

    // Content
    $mail->isHTML(true);                                      // Set email format to HTML
    $mail->Subject = 'New Enquiry Submitted';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    // Send the email
    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
