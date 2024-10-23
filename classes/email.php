<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    //Atributos
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon');
        $mail->Subject = 'Confirma tu cuenta';

        //SET HTML
        $mail->isHTML(TRUE);
        $mail->CharSet =  'UFT-8';

        //contenido del email
        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> para poder contunuar con tu registra, 
                        favor de confirmar tu cuenta.</p>";
        $contenido .= "<p>Has click en el siguente enlace para confirmar: 
                        <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token . "'>Confirmar</a> </p>";
        $contenido .= "Si tu no solicitaste esta cuenta puedes ignorar este correo.";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send();
    }

    public function enviarInstrucciones()
    {
        //Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon');
        $mail->Subject = 'Restablecer tu contraseña';

        //SET HTML
        $mail->isHTML(TRUE);
        $mail->CharSet =  'UFT-8';

        //contenido del email
        $contenido = "<html>";
        $contenido .= "<p>Hola <strong>" . $this->nombre . "</strong> para poder contunuar con el restablecimiento
                        de tu cuenta.</p>";
        $contenido .= "<p>Has click en el siguente enlace para poder restablecer tu contraseña: 
                        <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" . $this->token . "'>Restablecer contraseña</a> </p>";
        $contenido .= "Si tu no solicitaste el restablecimiento de tu contraseña, favor de ignorar.";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        //Enviar email
        $mail->send();
    }
}
