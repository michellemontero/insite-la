<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mail = new PHPMailer(true);

    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $company = filter_var($_POST['company'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

    $recaptcha = $_POST["g-recaptcha-response"];


    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $_ENV['SECRET_CAPTCHA'],
        'response' => $recaptcha
    );
    $options = array(
        'http' => array(
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $verify = file_get_contents($url, false, $context);
    $captcha_success = json_decode($verify);

    if ($captcha_success->success) {
        $mailMessage = '
    <html lang="es">
    <head>
    <title>Formulario de Contacto - Insite Latina America</title>
    </head>
    <body>
    <p>Se ha enviado un mensaje a través del formulario de contacto</p>
    <table>
        <tr>
        <td><strong>Nombre y Apellido</strong></td>
        </tr>
        <tr>
        <td>' . $name . '</td>
        </tr>
        <tr>
        <td><strong>Mail</strong></td>
        </tr>
        <tr>
        <td>' . $email . '</td>
        </tr>
        <tr>
        <td><strong>Compañia</strong></td>
        </tr>
        <tr>
        <td>' . $company . '</td>
        </tr>
        <tr>
        <td><strong>Mensaje</strong></td>
        </tr>
        <tr>
        <td>' . $message . '</td>
        </tr>
    </table>
    </body>
    </html>
    ';

        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];
            $mail->setFrom($_ENV['MAIL_SETFROM']);
            $mail->Port = 587;
            $mail->SMTPSecure = 'tls';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['MAIL_USERNAME'];
            $mail->Password = $_ENV['MAIL_PASSWORD'];
            $mail->addAddress($_ENV['MAIL_ADD_ADDRESS']);
            $mail->Subject = 'Formulario de Contacto - Insite Latina America';
            $mail->Body = trim($mailMessage);
            $mail->isHTML(true);

            $mail->send();
            echo 'Se ha enviado el mensaje. Nos contactaremos a la brevedad.';
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
        }

    } else {
        echo "";
    }

}

