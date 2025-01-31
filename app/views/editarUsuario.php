<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo BASE_URL . "/" ?>css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <h2>Cambiar datos de cuenta</h2>
    <form class="registerForm" action="" method="post" enctype="multipart/form-data">
        <label for="first_name">Nombre</label>
        <input type="text" id="first_name" name="first_name" placeholder="Nombre..." value="<?php echo $data["nombre"]?>" required><?php echo $data["msjErrorNombre"]?><br><br>

        <label for="last_name">Apellidos</label>
        <input type="text" id="last_name" name="last_name" placeholder="Apellidos..." value="<?php echo $data["apellidos"]?>" required><?php echo $data["msjErrorApellidos"]?><br><br>
        
        <label for="email">Correo Electrónico</label>
        <input type="email" id="email" name="email" placeholder="Correo..." value="<?php echo $data["email"]?>" required><?php echo $data["msjErrorEmail"]?><br><br>
        
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Contraseña..." value="<?php echo $data["password"]?>" required><?php echo $data["msjErrorPassword"]?><br><br>

        <label for="password_confirmation">Confirmar Contraseña</label>
        <input type="password" id="password_confirmation" placeholder="Confirme la contraseña..." name="password_confirmation" value="<?php echo $data["password_confirmation"]?>" required><?php echo $data["msjErrorPassword2"]?><br><br>

        <!-- Campo en el que se sube una imagen-->
        <label for="profile_picture">Imagen de Perfil</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*"><?php echo $data["msjErrorImagen"]?><br><br>

        <label for="profile_summary">Resumen del Perfil</label>
        <textarea id="profile_summary" name="profile_summary" maxlength="255" rows="4" cols="50"><?php echo $data["profile_summary"]?></textarea><br><br>
        
        <input type="submit" value="Guardar">
        <button type="submit" class="btnCancelarEdit" name="cancelar" formnovalidate>Cancelar</button>
    </form>
    <?php include 'footer.php'; ?>
</body>
</html>