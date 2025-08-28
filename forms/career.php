<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name       = $_POST['full_name'] ?? '';
    $email      = $_POST['email'] ?? '';
    $phone      = $_POST['phone'] ?? '';
    $position   = $_POST['position'] ?? '';
    $experience = $_POST['experience'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $current_company = $_POST['current_company'] ?? '';
    $expected_salary = $_POST['expected_salary'] ?? '';
    $cover_letter    = $_POST['cover_letter'] ?? '';
    $additional_info = $_POST['additional_info'] ?? '';

    // Validate required fields
    if(empty($name) || empty($email) || empty($phone) || empty($position)){
        echo json_encode([
            "status" => "error",
            "message" => "Please fill all required fields."
        ]);
        exit;
    }

    // Validate PDF upload
    if (!isset($_FILES['resume']) || $_FILES['resume']['error'] != 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Please upload your resume (PDF)."
        ]);
        exit;
    }

    $fileTmp  = $_FILES['resume']['tmp_name'];
    $fileName = $_FILES['resume']['name'];

    $mail = new PHPMailer(true);

    try {
        // SMTP setup
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chakrabortysandipan862@gmail.com';
        $mail->Password   = 'agpyqtzseealyxgl'; // App password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->isHTML(true);

        // 1️⃣ Send email to Admin
        $mail->setFrom('chakrabortysandipan862@gmail.com', 'Sai Survey &
Civil Engineering Pvt. Ltd.');
        $mail->addAddress('chakrabortysandipan862@gmail.com'); 
        $mail->Subject = "New Career Application - $name";

        $body = "<p><b>Name:</b> $name</p>
                 <p><b>Email:</b> $email</p>
                 <p><b>Phone:</b> $phone</p>
                 <p><b>Position:</b> $position</p>
                 <p><b>Experience:</b> $experience</p>
                 <p><b>Qualification:</b> $qualification</p>
                 <p><b>Current Company:</b> $current_company</p>
                 <p><b>Expected Salary:</b> $expected_salary</p>
                 <p><b>Cover Letter:</b> $cover_letter</p>
                 <p><b>Additional Info:</b> $additional_info</p>";

        $mail->Body = $body;

        // Attach uploaded PDF
        $mail->addAttachment($fileTmp, $fileName);

        $mail->send();

        // 2️⃣ Send confirmation email to User
        $mail->clearAddresses();
        $mail->addAddress($email);
        $mail->Subject = "Thank you for applying!";
        $mail->Body    = "<p>Hi $name,</p>
                          <p>Thank you for applying for the position of <b>$position</b>! We have received your application and will review it shortly.</p>
                          <p>— Team</p>";

        $mail->send();

        echo json_encode([
            "status" => "success",
            "message" => "Application submitted successfully! A confirmation email has been sent to you."
        ]);

    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Mailer Error: " . $mail->ErrorInfo
        ]);
    }
}
?>
