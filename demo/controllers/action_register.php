<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Проверяем, что все поля формы заполнены и не пустые
    if (!empty($name) && !empty($email) && !empty($password)) {
        // Хешируем пароль встроенным безопасным алгоритмом BCRYPT. 
        // В базу никогда нельзя сохранять пароли в чистом виде!
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Готовим запрос на добавление нового пользователя
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashedPassword]);
            
            // Сразу после успешной регистрации автоматически авторизуем пользователя.
            // $pdo->lastInsertId() выдает ID, который база только что сгенерировала для этого юзера
            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'name' => $name,
                'email' => $email
            ];
            // Перенаправляем в созданный профиль
            header('Location: ../profile.php');
            exit;
        } catch (PDOException $e) {
            // Если база выкинула ошибку (например, сработал UNIQUE на поле email),
            // значит, такой email уже занят. Возвращаем назад с ошибкой.
            header('Location: ../registration.php?error=email_exists');
            exit;
        }
    }
}
// Если кто-то зашел на этот файл напрямую без отправки формы, просто выкидываем на регистрацию
header('Location: ../registration.php');