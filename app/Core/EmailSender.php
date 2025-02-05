<?php 
namespace App\Core;

// Inclusión de clases necesarias
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;


// Clase para enviar correos electrónicos
class EmailSender {

    // Propiedades de la clase 
    // Transporte de correo
    private $transport;
    
    // Instancia de la clase Mailer
    private $mailer;

    // Constructor de la clase
    function __construct() {
        // Configura el transporte de correo
        $this->transport = Transport::fromDsn("smtp://{$_ENV['SMTP_USER']}:{$_ENV['SMTP_PASS']}@{$_ENV['SMTP_SERVER']}:{$_ENV['SMTP_PORT']}");
        $this->mailer = new Mailer($this->transport);
    }

    // Método para enviar un correo de confirmación
    public function sendConfirmationMail($name, $surname, $email, $token) {
        // Crea una instancia de la clase Email
        $email = (new Email())
            ->from('creacionportfolio@gmail.com')
            ->to($email)
            ->priority(Email::PRIORITY_HIGH)
            ->subject('Verificacion de creacion de portfolio')
            // ->text('')
            ->html('<p>Para poder loguearte en el porfolio de Creaciones, necesitamos que valides tu correo. Date prisa, tienes 24 horas!</p><br><a href="http://portfoliodb.local/usuarios/verificar/'.$token.'">VALIDA TU CORREO</a>');

        $this->mailer->send($email);
    }
}

?>