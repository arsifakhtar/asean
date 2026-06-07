<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function sendOTPEmail($to, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'arsifakhtar012@gmail.com';
        $mail->Password = 'qmlu mdqu qgmf nqli'; // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('arsifakhtar012@gmail.com', 'ASEAN Portal');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = '<p>Your OTP code is: <strong>' . $otp . '</strong></p>';

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log('Mailer Error: ' . $mail->ErrorInfo);
        return false;
    }
}
