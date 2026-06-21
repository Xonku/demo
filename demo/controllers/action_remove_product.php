<?php
// Проверяем, запущена ли уже сессия. Если нет (PHP_SESSION_NONE), то запускаем её.
// Сессия нужна, чтобы сервер помнил залогиненного юзера и его корзину при переходах по страницам
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Настройки подключения к нашей локальной базе данных
$host = 'localhost';
$db   = 'comfy_shop';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4'; // Поддержка любых символов и эмодзи

// Формируем DSN строку для PDO (стандартный формат для подключения к MySQL)
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Дополнительные важные настройки для работы с базой
$options = [
    // Включаем режим выброса ошибок (Exception). Если в SQL коде косяк — PHP сразу покажет где ошибка
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Настраиваем, чтобы база возвращала данные в виде удобных ассоциативных массивов ['name' => 'Значение']
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Отключаем эмуляцию подготовленных запросов, чтобы защита SQL-инъекций работала на уровне самой БД
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     // Пробуем создать объект PDO — это и есть наше активное подключение к базе данных
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // Если база отключена или пароль не подошел — глушим скрипт и выводим ошибку
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>