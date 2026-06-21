<?php
require_once 'config.php';

// Безопасность: если неавторизованный гость как-то вызвал этот файл, перенаправляем на вход
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Проверяем, какой ID заказа нам передали для отмены
if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    $user_id = $_SESSION['user']['id']; // Берем ID текущего залогиненного юзера

    // Используем безопасный подготовленный запрос (prepare).
    // Удаляем заказ только если: его ID совпадает, он принадлежит ЭТОМУ юзеру и его статус всё еще 'open'
    // (админ или архивные закрытые заказы удалить отсюда нельзя)
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ? AND user_id = ? AND status = 'open'");
    $stmt->execute([$order_id, $user_id]);
}

// Перенаправляем пользователя обратно в его личный кабинет (профиль)
header('Location: ../profile.php');
exit;