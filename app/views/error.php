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
    <h2><?php echo $data["error"]?></h2>
    <?php include 'footer.php'; ?>
</body>
</html>