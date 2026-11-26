
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configuração SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'smallmarcio48@gmail.com';
    $mail->Password = 'slug xjpi tujq svep';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Remetente
    $mail->setFrom('smallmarcio48@gmail.com', 'Meu Site');

    // Destinatário
    $mail->addAddress('smallmarcio48@gmail.com');

    // Conteúdo do email
    $mail->isHTML(true);
    $mail->Subject = 'Teste de email';
    $mail->Body = '<h1>Olá!</h1><p>Este email foi enviado com PHP e XAMPP.</p>';

    // Enviar
    $mail->send();

    echo 'Email enviado com sucesso!';

} catch (Exception $e) {
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}
