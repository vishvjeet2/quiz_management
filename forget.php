<?php
session_start();

/* ---------- DB CONNECTION ---------- */
$conn = new mysqli("localhost", "root", "", "test");
if ($conn->connect_error) {
    die("DB connection failed");
}

/* ---------- PHPMailer ---------- */
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$msg = "";

/* ---------- SEND OTP ---------- */
if (isset($_POST['send_otp'])) {

    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'vishvjeet771@gmail.com';          
            $mail->Password   = 'cxlykoqlhykwffjy';              
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('yourgmail@gmail.com', 'Password Reset');
            $mail->addAddress($email);

            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP for password reset is: $otp";

            $mail->send();
            $msg = "OTP has been sent to your email ✅";

        } catch (Exception $e) {
            $msg = "Mail error: " . $mail->ErrorInfo;
        }

    } else {
        $msg = "If email exists, OTP sent";
    }
}

/* ---------- RESET PASSWORD ---------- */
if (isset($_POST['reset'])) {

    if ($_POST['otp'] == $_SESSION['otp']) {

        $newPassword = $_POST['password'];
        $email = $_SESSION['email'];

        $update = $conn->prepare(
            "UPDATE users SET password=? WHERE email=?"
        );
        $update->bind_param("ss", $newPassword, $email);
        $update->execute();

        unset($_SESSION['otp'], $_SESSION['email']);
        $msg = "Password reset successfully ✅";

    } else {
        $msg = "Invalid OTP ❌";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body { font-family: Arial; background:#f2f2f2; }
        .box {
            width: 320px;
            background:#fff;
            padding:20px;
            margin:100px auto;
            border-radius:5px;
        }
        input, button {
            width:100%;
            padding:8px;
            margin:8px 0;
        }
    </style>
</head>
<body>

<div class="box">
    <h3>Forgot Password</h3>
    <p style="color:green;"><?php echo $msg; ?></p>

    <!-- SEND OTP -->
    <form method="post">
        <input type="email" name="email" placeholder="Registered Email" required>
        <button type="submit" name="send_otp">Send OTP</button>
    </form>

    <!-- RESET PASSWORD -->
    <form method="post">
        <input type="text" name="otp" placeholder="Enter OTP" required>
        <input type="password" name="password" placeholder="New Password" required>
        <button type="submit" name="reset">Reset Password</button>
    </form>
</div>

</body>
</html>
