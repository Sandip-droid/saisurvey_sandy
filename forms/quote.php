<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';  // Autoload PHPMailer
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $phone   = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    if(empty($name) || empty($email) || empty($phone) || empty($message)){
        echo json_encode([
            "status" => "error",
            "message" => "All fields are required."
        ]);
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chakrabortysandipan862@gmail.com';
        $mail->Password   = 'agpyqtzseealyxgl';  // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->isHTML(true);

        // 1️⃣ Send email to admin
        $mail->setFrom('chakrabortysandipan862@gmail.com', 'Website');
        $mail->addAddress('chakrabortysandipan862@gmail.com'); 
        $mail->Subject = "New Contact Request";
        $mail->Body    = "<p><b>Name:</b> $name</p>
                          <p><b>Email:</b> $email</p>
                          <p><b>Phone:</b> $phone</p>
                          <p><b>Message:</b> $message</p>";
        $mail->send();

        // 2️⃣ Send confirmation email to user
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = "Thank you for contacting us!";
        $mail->Body    = "<p>Hi $name,</p>
                          <p>Thank you for reaching out! We have received your message and will get back to you shortly.</p>
                          <p><b>Your Message:</b> $message</p>
                          <p>— Team</p>";
        $mail->send();

        echo json_encode([
            "status" => "success",
            "message" => "Message sent successfully! A confirmation email has been sent to you."
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error", 
            "message" => "Mailer Error: " . $mail->ErrorInfo
        ]);
    }
}
?>
