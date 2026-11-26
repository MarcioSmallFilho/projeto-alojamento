
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function enviarEmail($Remetente, $Destinatario ,$titulo, $conteudo){

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
        $mail->setFrom($Remetente[0], $Remetente[1]);

        // Destinatário
        $mail->addAddress($Destinatario);

        // Conteúdo do email
        $mail->isHTML(true);
        $mail->Subject = $titulo;
        $mail->Body = $conteudo;

        // Enviar
        $mail->send();

    } catch (Exception $e) {
        echo "Erro ao enviar email: {$mail->ErrorInfo}";
    }
}