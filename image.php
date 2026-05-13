<?php
session_start();

$user_id = $_SESSION["user_id"] ?? false;
$photo_id = intval($_GET["id"]);

require "vendor/autoload.php";

$db = new \Photos\DB();

$photo = $db->get_photo_by_id($photo_id);
$comments = $db->get_photo_comments($photo_id);
var_dump($comments);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Фото</title>
</head>
<body>

<?php include "header.php"; ?>

<div class="image">
    <?php if ($photo): ?>

        <img src="<?= htmlspecialchars($photo['image']) ?>" alt="">
        <h1><?= htmlspecialchars($photo['text']) ?></h1>
        <p><?= htmlspecialchars($photo['Name']) ?></p>

        <div class="comments">

            <?php if ($user_id): ?>
                <div class="form">
                    <textarea id="text" rows="5"></textarea>
                    <button id="add_comment">Добавить</button>
                </div>
            <?php endif; ?>

            <h2>Комментарии:</h2>

            <?php foreach ($comments as $comment): ?>
                <div class="comment">
                    <p class="author"><?= htmlspecialchars($comment["Name"]) ?></p>
                    <p class="text"><?= htmlspecialchars($comment["text"]) ?></p>
                    <p class="date"><?= htmlspecialchars($comment["Post_date"]) ?></p>
                </div>
            <?php endforeach; ?>

        </div>

    <?php else: ?>
        <p>Фото не найдено</p>
    <?php endif; ?>
</div>
<script src="image.js"></script>
</body>
</html>