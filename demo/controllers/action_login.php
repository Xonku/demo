<?php
require_once 'config.php';

// Проверяем, что данные пришли именно через форму методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trim() обрезает случайные пробелы по бокам, которые юзер мог поставить при вводе email
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Ищем пользователя в базе по его Email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(); // Достаем строку с данными пользователя

    // Если пользователь найден и хеш пароля из базы совпадает с тем, что ввели (password_verify)
    if ($user && password_verify($password, $user['password'])) {
        // Записываем данные успешного юзера в сессию, чтобы сайт "узнавал" его на других страницах
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];
        // Уводим в личный кабинет
        header('Location: ../profile.php');
        exit;
    } else {
        // Если пароль или email не подошли, возвращаем на страницу входа с флагом ошибки в ссылке
        header('Location: ../login.php?error=wrong_credentials');
        exit;
    }
}