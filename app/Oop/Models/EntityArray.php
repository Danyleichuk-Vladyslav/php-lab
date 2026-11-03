<?php

$categories = ['Технології', 'Кулінарія', 'Новини', 'Подорожі', 'Спорт'];
sort($categories);

$post = [
    'id' => 1,
    'title' => 'Перший допис',
    'body' => 'Короткий текст про подорож Карпатами.',
    'status' => 'published',
    'publishedAt' => '2026-10-27 12:00:00',
];
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>Масиви інтернет-блогу</title>
</head>
<body>
    <h1>Індексний масив категорій</h1>
    <ul>
        <?php foreach ($categories as $category) { ?>
            <li><?php echo htmlspecialchars($category, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ul>

    <h1>Асоціативний масив допису</h1>
    <ul>
        <?php foreach ($post as $key => $value) { ?>
            <li><?php echo htmlspecialchars($key.': '.$value, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ul>
</body>
</html>
