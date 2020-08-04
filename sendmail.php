<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';
$postData = $_POST;
if (isset($postData['email']) && $postData['email'] != '') {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->Mailer = "smtp";

    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = TRUE;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Host = "smtp.gmail.com";
    $mail->Username = "info.weathervn@gmail.com";
    $mail->Password = "Su@hue3108";
    $mail->IsHTML(true);
    $mail->AddAddress("info.weathervn@gmail.com", "recipient-name");
    $mail->SetFrom("info.weathervn@gmail.com", "from-name");
    $mail->AddReplyTo("info.weathervn@gmail.com", "reply-to-name");
    $mail->AddCC("cc-recipient-email@domain", "cc-recipient-name");
    $mail->Subject = "Test is Test Email sent via Gmail SMTP Server using PHP Mailer";
    $content = "<p><b>Email send from astronomie</b></p><p>email: " . $postData['email'] . "</p> <p>name: " . $postData['name'] . "</p><p>inquiry: " . $postData['inquiry'] . "</p>";
    $mail->MsgHTML($content);
    $mail->Send();
}
?>