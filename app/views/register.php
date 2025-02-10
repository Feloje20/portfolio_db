<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuario</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Registro de usuario</h2>
    <form class="registerForm" action="" method="post" enctype="multipart/form-data">
        <label for="first_name">Nombre</label>
        <input type="text" id="first_name" name="first_name" placeholder="Nombre..." value="<?php echo $data["nombre"]?>" class="<?php echo !empty($data["msjErrorNombre"]) ? 'errorInput' : '';?>"><?php echo "<p class='error'>" .  $data["msjErrorNombre"] . '</p>'?><br>

        <label for="last_name">Apellidos</label>
        <input type="text" id="last_name" name="last_name" placeholder="Apellidos..." value="<?php echo $data["apellidos"]?>" class="<?php echo !empty($data["msjErrorApellidos"]) ? 'errorInput' : '';?>"><?php echo "<p class='error'>" .  $data["msjErrorApellidos"] . '</p>'?><br>
        
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" placeholder="Correo..." value="<?php echo $data["email"]?>" class="<?php echo !empty($data["msjErrorEmail"]) ? 'errorInput' : '';?>"><?php echo "<p class='error'>" .  $data["msjErrorEmail"] . '</p>'?><br>
        
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Contraseña..." value="<?php echo $data["password"]?>" class="<?php echo !empty($data["msjErrorPassword"]) ? 'errorInput' : '';?>"><?php echo "<p class='error'>" .  $data["msjErrorPassword"] . '</p>'?><br>

        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" id="password_confirmation" placeholder="Confirme la contraseña..." name="password_confirmation" value="<?php echo $data["password_confirmation"]?>" class="<?php echo !empty($data["msjErrorPassword2"]) ? 'errorInput' : '';?>"><?php echo "<p class='error'>" .  $data["msjErrorPassword2"] . '</p>'?><br>

        <label for="profile_picture">Imagen de Perfil</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" class="<?php echo !empty($data["msjErrorImagen"]) ? 'errorInput' : '';?>"><?php echo $data["msjErrorImagen"]?><br>

        <label for="profile_summary">Resumen del Perfil</label>
        <textarea id="profile_summary" name="profile_summary" maxlength="255" rows="4" cols="50" class="<?php echo !empty($data["msjErrorProfileSummary"]) ? 'errorInput' : '';?>"><?php echo $data["profile_summary"]?></textarea><br>
        
        <input type="submit" value="Registrarse">
        <button type="submit" class="btnCancelarEdit" name="cancelar" formnovalidate>Cancelar</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>