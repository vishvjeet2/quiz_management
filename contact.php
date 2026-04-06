<?php

session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';


$success = false;
$error = "";

if (isset($_POST['submit'])) {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    if ($name == "" || $email == "" || $message == "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {

        $mail = new PHPMailer(true);

        try {
            // SMTP settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vishvjeet771@gmail.com';   // YOUR email
            $mail->Password   = 'cxlykoqlhykwffjy';        // Gmail App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Email headers
            $mail->setFrom($email, $name);
            $mail->addAddress('vishvjeet771@gmail.com');
            $mail->addReplyTo($email, $name);

            // Email content
            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

            $mail->send();
            $success = true;

        } catch (Exception $e) {
            $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Contact Us</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Contact Us</span>

                    <a href="javascript:history.back()" class="btn btn-sm btn-light">
                        ← Back
                    </a>
                </div>


                <div class="card-body">

                    <?php if ($error) { ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php } ?>

                    <?php if ($success) { ?>
                        <div class="alert alert-success">
                            ✅ Your message has been sent successfully!
                        </div>
                    <?php } ?>

                    <form method="post">
                        <div class="form-group">
                            <label>Your Name</label>
                            <input type="text" name="name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Your Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Subject</label>
                            <input type="text" name="subject" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Message</label>
                            <textarea name="message" class="form-control" rows="4"></textarea>
                        </div>

                        <button type="submit" name="submit" class="btn btn-primary btn-block">
                            Send Message
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
