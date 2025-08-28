<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';// PHPMailer को composer से install करना होगा

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = htmlspecialchars(trim($_POST["name"]));
    $email   = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $mail = new PHPMailer(true);

    try {
        // ==============================
        // SMTP setup
        // ==============================
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chakrabortysandipan862@gmail.com';   // आपका Gmail
        $mail->Password   = 'agpyqtzseealyxgl';                   // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ============================
        // 1️⃣ SEND MAIL TO ADMIN (YOU)
        // ============================
        $mail->setFrom('chakrabortysandipan862@gmail.com', 'Website');
        $mail->addAddress('chakrabortysandipan862@gmail.com'); // Admin mail

        $mail->isHTML(true);
        $mail->Subject = "New Client Request: $subject";
        $mail->Body = "
            <h3>New Contact Request</h3>
            <p><b>Name:</b> $name</p>
            <p><b>Email:</b> $email</p>
            <p><b>Message:</b><br>$message</p>
        ";

        $mail->send();

        // ============================
        // 2️⃣ AUTO-REPLY TO CLIENT
        // ============================
        $reply = new PHPMailer(true);
        $reply->isSMTP();
        $reply->Host       = 'smtp.gmail.com';
        $reply->SMTPAuth   = true;
        $reply->Username   = 'chakrabortysandipan862@gmail.com';
        $reply->Password   = 'agpyqtzseealyxgl';
        $reply->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $reply->Port       = 587;

        $reply->setFrom('chakrabortysandipan862@gmail.com', 'Your Company');
        $reply->addAddress($email, $name);

        $reply->isHTML(true);
        $reply->Subject = "Thank you for contacting us!";
        $reply->Body = "
            <p>Hello <b>$name</b>,</p>
            <p>Thank you for reaching out. We have received your message:</p>
            <blockquote>$message</blockquote>
            <p>Our team will get back to you soon.</p>
            <p>Best Regards,<br>Your Company</p>
        ";

        $reply->send();

        echo "success";
    } catch (Exception $e) {
        echo "error: {$mail->ErrorInfo}";
    }
}
?>
