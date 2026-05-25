<?php
// mailer.php - Email Configuration

require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function getMailer() {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $_ENV['SMTP_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['SMTP_USER'];
        $mail->Password   = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = $_ENV['SMTP_PORT'];

        //Recipients
        $mail->setFrom('no-reply@etheatro.com', 'ETHEATRO Audition System');

        return $mail;
    } catch (Exception $e) {
        // Handle exception, maybe log it
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return null;
    }
}
?>