# Лабораторна 1. CI/CD для PHP

Навчальний проєкт `student/ci-cd-lab`: клас `Calculator`, тести PHPUnit і перевірка стилю Laravel Pint. Безперервна інтеграція описана в GitHub Actions.

PHP локально не обов’язковий. Пайплайн розрахований на PHP 8.2 у GitHub Actions.

![PHP CI](https://github.com/Danyleichuk-Vladyslav/php-lab/actions/workflows/php-ci.yml/badge.svg)

## Структура

```
├── .github/PULL_REQUEST_TEMPLATE.md
├── .github/workflows/php-ci.yml
├── app/Services/Calculator.php
├── tests/Unit/CalculatorTest.php
├── .gitignore
├── README.md
└── composer.json
```

`Calculator` додає і віднімає два числа. Стиль перевіряє Pint, самі операції — PHPUnit.

## Локальний запуск

```bash
composer install
vendor/bin/phpunit tests
vendor/bin/pint --test
vendor/bin/pint
```

Остання команда сама розставляє пробіли. Після неї `pint --test` має стати зеленим. Каталог `vendor/` у git не входить.

## Що робить CI

Файл `.github/workflows/php-ci.yml` запускається на `push` і `pull_request`:

1. PHP 8.2
2. `composer install`
3. `pint --test`
4. `phpunit`
5. крок **Demonstrate GitHub Secrets**

Секрет читається лише зі сховища GitHub (`secrets.MY_SUPER_SECRET_KEY`). У логах значення маскується. Якщо секрет не задано, крок завершується з помилкою. У код і в коміти його не кладуть.

## GitHub

Pull request відкривається за шаблоном з `.github/PULL_REQUEST_TEMPLATE.md`. Секрет `MY_SUPER_SECRET_KEY` лежить в Actions Secrets, не в репозиторії. Для `main` діє branch protection. Виправлення стилю Pint йде в гілці `feature-cicd-pipeline`. Задача закривається рядком `Fixes #N` або `Resolves #N`.

З чого складається робота:

### 1. Каркас

У репозиторії лежать файли проєкту і шаблон pull request. Першу зміну видно в історії комітів. Далі її дивляться два рев’юери в PR `initialize-repository`.

### 2. Перевірка і секрет

На кожен push GitHub Actions запускає Pint і PHPUnit. Ключ `MY_SUPER_SECRET_KEY` зберігається в GitHub Secrets, не у файлах. У логу крок Demonstrate GitHub Secrets показує, що ключ підтягнувся, а саме значення замасковане.

### 3. Злиття в main

Pint і PHPUnit зелені, бейдж CI стоїть у цьому README. Гілка `feature-cicd-pipeline` потрапляє в `main` через pull request. У описі PR є `Fixes #N` або `Resolves #N`, задачі розкладені на Kanban.
