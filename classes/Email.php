<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    //Variables a usar
    public $email;
    public $nombre;
    public $apellido;
    public $token;

    //Creamos el constructor para usar las variables
    public function __construct($email, $nombre, $apellido, $token)
    {
        //asignamos las variables
        $this -> email = $email;
        $this -> nombre = $nombre;
        $this -> apellido = $apellido;
        $this -> token = $token;
        //Ya puede recibir desde logincontroller los datos especificados.
    }

    //No toma ningun valor por que todo ya esta en la clase
    public function enviarConfirmacion(){
        //Se crea el objeto de $email
        $mail = new PHPMailer();
        $mail ->isSMTP();                               //Protocolo de envio de emails
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];


        $mail -> setFrom('cuentas@appsalon.com');
        $mail -> addAddress('cuentas@appsalon.com', 'AppSalon.com'); 
        $mail -> Subject = 'Confirmacion de cuenta';

        //Set html
        $mail ->isHTML(TRUE);
        $mail ->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this -> nombre . " " . $this -> apellido . ", </strong>
        haz creado tu cuenta en App Salon.</p>";
        $contenido .= "<p>Muchas gracias por confiar en nosotros para el cuidado de su estilo.</p>";
        $contenido .= "<p>Favor de confirmar su cuenta haciendo click en el enlace de abajo.</p>";
        $contenido .= "<a href ='". $_ENV['APP_URL'] ."/confirm-account?token=" . $this -> token . "'>Confirmar Cuenta</a>";
        $contenido .= "<p>Si tu no realizaste alguna de las acciones anteriormente mencionadas, puedes ignorar este mensaje.</p>";
        $contenido .= '</html>';

        $mail ->Body = $contenido;

        //Enviamos el email
        $mail -> send();
    }

    public function enviarInstrucciones() {
        //Se crea el objeto de $email
        $mail = new PHPMailer();
        $mail ->isSMTP();                               //Protocolo de envio de emails
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];


        $mail -> setFrom('cuentas@appsalon.com');
        $mail -> addAddress('cuentas@appsalon.com', 'AppSalon.com'); 
        $mail -> Subject = 'Restablece tu Contraseña';

        //Set html
        $mail ->isHTML(TRUE);
        $mail ->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= "<p><strong>Hola " . $this -> nombre . " " . $this -> apellido . " </strong>
        , haz solicitado restablecer tu cuenta en App Salon.</p>";
        $contenido .= "<p>Para poder restablecer tu contraseña, haz click en el siguiente enlace:</p>";
        $contenido .= "<a href ='". $_ENV['APP_URL'] ."/recover?token=" . $this -> token . "'>Restablecer contraseña</a>";
        $contenido .= "<p> <strong>Recuerda que la contraseña debe tener como minimo 8 caracteres y no debe contener espacios.</strong></p>";
        $contenido .= "<p>Si tu no solicitaste el restablecimiento de tu contraseña, puedes hacer caso omiso a este mensaje.</p>";
        $contenido .= '</html>';

        $mail ->Body = $contenido;

        //Enviamos el email
        $mail -> send();

    }
}