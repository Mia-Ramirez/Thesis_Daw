<!DOCTYPE html>
<?php
    error_reporting(E_ALL); // Report all types of errors
    ini_set('display_errors', 1); // Display errors on the screen

    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    function send_email($to_email, $subject, $body){
        $mailer_app = new PHPMailer(true);

        $mailer_app->isSMTP();
        $mailer_app->Host = 'smtp.gmail.com';
        $mailer_app->SMTPAuth = true;
        $mailer_app->Username = 'pharmanest123@gmail.com';
        $mailer_app->Password = 'juxbprangwekveki ';
        $mailer_app->SMTPSecure = 'ssl';
        $mailer_app->Port = 465;

        $mailer_app->setFrom('pharmanest123@gmail.com');
        $mailer_app->addAddress($to_email);

        $mailer_app->isHTML(true);

        $mailer_app->Subject = $subject;
        $mailer_app->Body = $body;

        echo $mailer_app->send();
    }
?>