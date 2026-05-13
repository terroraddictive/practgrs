<?php
    session_start();
    $user_id = $_SESSION["user_id"] ?? false;
    require "vendor/autoload.php";
    $db = new Photos\DB();
    $data = $db->get_all_photos();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Проект 12-11</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>    
    <?php include "header.php"; ?>
    <h1>Галерея</h1>
    <div id="grid">
        <?php foreach ($data as $photo): ?>
            <div class="photo">
                <?= (new Photos\Photo($photo["id"],$photo["image"], $photo["text"]))->get_html() ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php include "add_form.php"; ?>
    
    <div id="popup_photo">
        <img src="" alt="">
    </div>

    <script src="script.js"></script>
</body>
</html>