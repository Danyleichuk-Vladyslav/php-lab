<?php

declare(strict_types=1);
use Faker\Factory;

require_once 'vendor/autoload.php';
require_once 'app/Services/EntityService.php';
require_once 'app/Services/DataProcessor.php';

$faker = Factory::create();
$viewCounts = [];

for ($i = 0; $i < 10; $i++) {
    $viewCounts[] = $faker->numberBetween(0, 2000);
}

$minThreshold = 500;
$popularViews = array_filter(
    $viewCounts,
    function (int $views) use ($minThreshold): bool {
        return $views > $minThreshold;
    }
);

$viewsResult = countViews(120, 5);

$discountMessage = '';
$discountFinished = '';

try {
    applyDiscount(1000, 150);
} catch (Exception $e) {
    $discountMessage = $e->getMessage();
} finally {
    $discountFinished = 'Завершення спроби розрахунку.';
}

$numbersInput = '4 8 15 16 23';
$numbersSum = sumNaturalNumbers($numbersInput);

$edgesText = 'кіт так сир рис стіл';
$edgeWords = wordsWithMatchingEdges($edgesText);

$shuffleText = 'автор пише новий допис про подорож';
$shuffledWords = shuffleWords($shuffleText);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>Інтернет-блог. Лабораторна 3</title>
</head>
<body>
    <h1>Перегляди допису</h1>
    <p>Було 120 переглядів, додалося 5. Стало <?php echo $viewsResult; ?>.</p>

    <h1>Перегляди, більші за <?php echo $minThreshold; ?></h1>
    <p>Усі згенеровані: <?php echo htmlspecialchars(implode(', ', $viewCounts), ENT_QUOTES, 'UTF-8'); ?></p>
    <ul>
        <?php foreach ($popularViews as $views) { ?>
            <li><?php echo $views; ?></li>
        <?php } ?>
    </ul>

    <h1>Знижка</h1>
    <p>Помилка: <?php echo htmlspecialchars($discountMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    <p><?php echo htmlspecialchars($discountFinished, ENT_QUOTES, 'UTF-8'); ?></p>

    <h1>Сума натуральних чисел</h1>
    <p>Вхід: <?php echo htmlspecialchars($numbersInput, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Сума: <?php echo $numbersSum; ?></p>

    <h1>Слова, у яких перша літера збігається з останньою літерою наступного</h1>
    <p>Текст: <?php echo htmlspecialchars($edgesText, ENT_QUOTES, 'UTF-8'); ?></p>
    <ul>
        <?php foreach ($edgeWords as $pair) { ?>
            <li><?php echo htmlspecialchars($pair, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ul>

    <h1>Слова у випадковому порядку</h1>
    <p>Було: <?php echo htmlspecialchars($shuffleText, ENT_QUOTES, 'UTF-8'); ?></p>
    <p>Стало: <?php echo htmlspecialchars($shuffledWords, ENT_QUOTES, 'UTF-8'); ?></p>
</body>
</html>
