<?php
require_once 'config.php';

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

// Проверяем, передан ли ID заказа для отмены
if (isset($_GET['id'])) {
    $order_id = (int)$_GET['id'];
    $user_id = $_SESSION['user']['id'];

    // Удаляем заказ, только если он принадлежит текущему пользователю и имеет статус 'open'
    $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ? AND user_id = ? AND status = 'open'");
    $stmt->execute([$order_id, $user_id]);
}

// Возвращаем пользователя обратно в профиль
header('Location: ../profile.php');
exit;