<?php
require_once 'config.php';

// Проверяем, передан ли ID товара для удаления
if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];

    if (!empty($_SESSION['basket'])) {
        // Ищем индекс товара в массиве корзины
        $key = array_search($product_id, $_SESSION['basket']);
        
        // Если товар найден, удаляем его из массива
        if ($key !== false) {
            unset($_SESSION['basket'][$key]);
            
            // Сбрасываем индексы массива, чтобы они шли по порядку (0, 1, 2...)
            $_SESSION['basket'] = array_values($_SESSION['basket']);
        }
    }
}

// Возвращаем пользователя обратно на страницу корзины (или откуда он пришел)
$return_url = $_SERVER['HTTP_REFERER'] ?? '../basket.php';
header("Location: " . $return_url);
exit;