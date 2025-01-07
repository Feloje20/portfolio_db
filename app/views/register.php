<?php

// Requisitos para el envío de correos
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;

/*
// Ejemplo de envío de correo
$transport = Transport::fromDsn('smtp://a22feloje@iesgrancapitan.org:abhcgxgE!681@smtp://smtp.gmail.com:587?encryption=tls&auth_mode=login');
$mailer = new Mailer($transport);

$email = (new Email())
    ->from('a22feloje@iesgrancapitan.org')
    ->to('a22feloje@iesgrancapitan.org')
    //->cc('cc@example.com')
    //->bcc('bcc@example.com')
    //->replyTo('fabien@example.com')
    //->priority(Email::PRIORITY_HIGH)
    ->subject('Time for Symfony Mailer!')
    ->text('Esto es una prueba')
    ->html('<p>See Twig integration for better HTML integration!</p>');

$mailer->send($email);
*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Registro de usuario</h1>
    <form action="" method="post">
        <label for="first_name">Nombre:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo $data["apellidos"]?>" required><?php echo $data["msjErrorNombre"]?><br><br>

        <label for="last_name">Apellido:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo $data["apellidos"]?>" required><?php echo $data["msjErrorApellidos"]?><br><br>
        
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo $data["email"]?>" required><?php echo $data["msjErrorEmail"]?><br><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" value="<?php echo $data["password"]?>" required><?php echo $data["msjErrorPassword"]?><br><br>

        <label for="password_confirmation">Confirmar Contraseña:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" value="<?php echo $data["password_confirmation"]?>" required><?php echo $data["msjErrorPassword2"]?><br><br>

        <!--
        <label for="profile_picture">Foto de Perfil:</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><br><br>
        -->

        <label for="profile_summary">Resumen del Perfil:</label>
        <textarea id="profile_summary" name="profile_summary" maxlength="255" rows="4" cols="50"><?php echo $data["profile_summary"]?></textarea><br><br>

        
        <input type="submit" value="submit">
    </form>
</body>
</html>