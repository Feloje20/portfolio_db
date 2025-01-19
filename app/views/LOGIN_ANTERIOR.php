<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <div class="tittle-box" onclick="location.href='/'" style="cursor: pointer;">
            <h1 class="site-title">Portfolio Manager</h1>
    </div>
    <h2>INICIO DE SESIÓN</h2>
    <form action="" method="post">
        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" value="<?php echo $data["email"]?>" required><?php echo $data["msjErrorEmail"]?><br><br>
        
        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" value="<?php echo $data["password"]?>" required><?php echo $data["msjErrorPassword"]?><br><br>
        
        <?php echo $data["msjErrorMissmatch"] . "<br/>"?>
        <input type="submit" value="submit">
    </form>
</body>
</html>