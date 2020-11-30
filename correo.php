
<?php
/*
Para quienes lo usen, la idea es que usen la funcion dentro sus respectivos PHPs
Como parametro solo esta enviando el correo destino, el titulo y el contenido del correo. 
Sin quieren cambiar algo mas haganlo sobre la version que usen en sus PHPs, la idea es no tocar este. 
*/
$destino = "spalaciosl1007@gmail.com";
$titulo = "Correo de prueba";
$contenido = "Este es un correo de prueba, podria estar diciendo que te vamos a reportar a Datacredito";
enviar_correo($destino, $titulo, $contenido);

function enviar_correo($destino, $titulo, $contenido)
{
    require_once('PHPMailer/PHPMailerAutoload.php');
    $mail = new PHPMailer;

    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //SI TIENEN PROBLEMAS, DESCOMENTAR ESTE LINEA PARA SABER QUE FALLA
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = 'mibancoproweb@gmail.com';                     // SMTP username
    $mail->Password   = 'Proweb2020';                               // SMTP password
    $mail->SMTPSecure = 'tsl';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 25;                 //SI NECESARIO, CAMBIAR EL PUERTO AQUI. 


    $mail->setFrom('mibancoproweb@gmail.com', 'Mailer');
    $mail->addAddress($destino);
    $mail->isHTML();/*
    $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
    $mail->addAddress('ellen@example.com');               // Name is optional
    $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('cc@example.com');
    $mail->addBCC('bcc@example.com');*/

    $mail->Subject = $titulo;
    $mail->Body = $contenido;

    /*// Attachments
    $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/

    if (!$mail->Send()) {
        echo "No se enviado correctamente. ERROR:  ";
        echo $mail->ErrorInfo;
    } else {
        echo "Se ha enviado correctamente";
    }
}
?>
