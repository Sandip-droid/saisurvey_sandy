<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

// Always return JSON
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $company_name        = $_POST['company_name'] ?? '';
    $contact_person      = $_POST['contact_person'] ?? '';
    $email               = $_POST['email'] ?? '';   // client email
    $phone               = $_POST['phone'] ?? '';
    $company_address     = $_POST['company_address'] ?? '';
    $project_type        = $_POST['project_type'] ?? '';
    $budget_range        = $_POST['budget_range'] ?? '';
    $expected_start_date = $_POST['expected_start_date'] ?? '';
    $project_location    = $_POST['project_location'] ?? '';
    $project_description = $_POST['project_description'] ?? '';
    $special_requirements= $_POST['special_requirements'] ?? '';

    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chakrabortysandipan862@gmail.com';
        $mail->Password   = 'agpyqtzseealyxgl';  // Gmail App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // ============================
        // 1️⃣ SEND MAIL TO ADMIN (YOU)
        // ============================
        $mail->setFrom('chakrabortysandipan862@gmail.com', 'Website');
        $mail->addAddress('chakrabortysandipan862@gmail.com'); // your mail

        $mail->isHTML(true);
        $mail->Subject = "New Client Request";
        $mail->Body = "
            <h3>New Client Request</h3>
            <p><b>Company Name:</b> {$company_name}</p>
            <p><b>Contact Person:</b> {$contact_person}</p>
            <p><b>Email:</b> {$email}</p>
            <p><b>Phone:</b> {$phone}</p>
            <p><b>Address:</b> {$company_address}</p>
            <p><b>Project Type:</b> {$project_type}</p>
            <p><b>Budget Range:</b> {$budget_range}</p>
            <p><b>Expected Start Date:</b> {$expected_start_date}</p>
            <p><b>Project Location:</b> {$project_location}</p>
            <p><b>Project Description:</b> {$project_description}</p>
            <p><b>Special Requirements:</b> {$special_requirements}</p>
        ";
        $mail->send();

        // ============================
        // 2️⃣ SEND MAIL TO CLIENT (AUTO-REPLY)
        // ============================
        $mail->clearAddresses(); // remove previous recipients
        $mail->addAddress($email); // client email

        $mail->Subject = "Thank you for contacting us!";
        $mail->Body = "
            <h3>Hi {$contact_person},</h3>
            <p>Thank you for reaching out to us. We have received your request and our team will get back to you soon.</p>
            <br>
            <p>Best Regards,<br> Sai Survey & Civil Engineering Pvt. Ltd.</p>
        ";
        $mail->send();

        // ✅ Success response
        echo json_encode([
            "success" => true,
            "message" => "Your request has been submitted successfully!"
        ]);
        exit;

    } catch (Exception $e) {
        // ❌ Error response
        echo json_encode([
            "success" => false,
            "message" => "Message could not be sent. Error: {$mail->ErrorInfo}"
        ]);
        exit;
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}
