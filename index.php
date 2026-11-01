<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\EntityFactory;
use App\Models\Post;
use App\Services\ConsoleLogger;
use App\Services\EntityService;
use App\Services\FileLogger;
use App\Tasks\Car;
use App\Tasks\ExpressTrain;
use App\Tasks\Group;
use App\Tasks\Rectangle;
use App\Tasks\Student;
use App\Tasks\Train;
use Faker\Factory;

require_once 'vendor/autoload.php';
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

$consoleLogger = new ConsoleLogger;
$entityService = new EntityService($consoleLogger);

ob_start();
$viewsResult = $entityService->countViews(120, 5);

$discountMessage = '';
$discountFinished = '';

try {
    $entityService->applyDiscount(1000, 150);
} catch (Exception $e) {
    $discountMessage = $e->getMessage();
} finally {
    $discountFinished = 'Завершення спроби розрахунку.';
}
$serviceLogs = ob_get_clean();

$numbersInput = '4 8 15 16 23';
$numbersSum = sumNaturalNumbers($numbersInput);

$edgesText = 'кіт так сир рис стіл';
$edgeWords = wordsWithMatchingEdges($edgesText);

$shuffleText = 'автор пише новий допис про подорож';
$shuffledWords = shuffleWords($shuffleText);

$factory = new EntityFactory;
$user = $factory->createEntity('user', [
    'id' => '1',
    'createdAt' => '2026-11-01 10:00:00',
    'name' => 'Данилейчук Владислав',
    'email' => 'vlad@blog.test',
]);
$post = $factory->createEntity('post', [
    'id' => '15',
    'createdAt' => '2026-11-01 12:00:00',
    'title' => 'Подорож Карпатами',
    'body' => 'Короткий текст про осінні гори.',
    'status' => 'published',
]);
$postJson = $post instanceof Post ? $post->exportToJson() : '';

$group = new Group([], 41, 'КН-41');
$group->addStudent(new Student(1, 'Іван', 'Петренко', '2006-04-12'));
$group->addStudent(new Student(2, 'Олена', 'Коваленко', '2006-07-02'));
$foundStudent = $group->searchStudent('Олена');
$group->removeStudent(2);

$distance = 180.0;
$car = new Car('Автомобіль', 90);
$train = new Train('Потяг', 120);
$express = new ExpressTrain('Потяг-експрес', 180);

$rectangle = new Rectangle(6, 4);
?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="utf-8">
    <title>Інтернет-блог</title>
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

    <h1>Підключення до бази</h1>
    <pre><?php
        $database = Database::getInstance();
$again = Database::getInstance();
echo 'Той самий об\'єкт: '.($database === $again ? 'так' : 'ні')."\n";
?></pre>

    <h1>Фабрика</h1>
    <p><?php echo htmlspecialchars($user->getDetails(), ENT_QUOTES, 'UTF-8'); ?></p>
    <p><?php echo htmlspecialchars($post->getDetails(), ENT_QUOTES, 'UTF-8'); ?></p>

    <h1>Експорт допису в JSON</h1>
    <pre><?php echo htmlspecialchars($postJson, ENT_QUOTES, 'UTF-8'); ?></pre>

    <h1>Впровадження залежності</h1>
    <pre><?php
    echo htmlspecialchars($serviceLogs, ENT_QUOTES, 'UTF-8');
$fileLogger = new FileLogger;
$fileLogger->log('Файл логера підключено окремо від консолі.');
$entityService->publish($post);
?></pre>

    <h1>Група і студенти</h1>
    <p>Група <?php echo htmlspecialchars($group->name, ENT_QUOTES, 'UTF-8'); ?>, номер <?php echo $group->group_id; ?>.</p>
    <p>Знайдено: <?php echo $foundStudent === null ? 'нікого' : htmlspecialchars($foundStudent->surname.' '.$foundStudent->name, ENT_QUOTES, 'UTF-8'); ?>.</p>
    <p>Після видалення студента з id 2 залишилось:</p>
    <ul>
        <?php foreach ($group->students() as $student) { ?>
            <li><?php echo htmlspecialchars($student->surname.' '.$student->name.', '.$student->birthday, ENT_QUOTES, 'UTF-8'); ?></li>
        <?php } ?>
    </ul>

    <h1>Час у дорозі на <?php echo $distance; ?> км</h1>
    <ul>
        <?php foreach ([$car, $train, $express] as $vehicle) { ?>
            <li><?php echo htmlspecialchars($vehicle->name(), ENT_QUOTES, 'UTF-8'); ?>: <?php echo $vehicle->calculateTravelTime($distance); ?> год</li>
        <?php } ?>
    </ul>

    <h1>Прямокутник</h1>
    <p>Сторони <?php echo $rectangle->width; ?> і <?php echo $rectangle->height; ?>.</p>
    <p>Периметр: <?php echo $rectangle->perimeter(); ?>. Площа: <?php echo $rectangle->area(); ?>.</p>
</body>
</html>
