<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>INICIO DE SESIÓN</h1>
    <form action="" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo $data["email"]?>" required><?php echo $data["msjErrorEmail"]?><br><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" value="<?php echo $data["password"]?>" required><?php echo $data["msjErrorPassword"]?><br><br>
        
        <?php echo $data["msjErrorMissmatch"] . "<br/>"?>
        <input type="submit" value="submit">
</body>
</html>