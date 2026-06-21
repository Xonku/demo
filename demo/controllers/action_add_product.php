<?php
// Подключаем конфигурацию, чтобы иметь доступ к сессии ($_SESSION)
require_once 'config.php';

// Проверяем, пришел ли ID товара через GET-запрос (например: action_add_product.php?id=5)
if (isset($_GET['id'])) {
    // Принудительно превращаем ID в целое число (int), чтобы никто не подкинул текст вместо цифры
    $product_id = (int)$_GET['id'];
    
    // Если в сессии еще нет ячейки для корзины, создаем её как пустой массив
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
    // Добавляем ID товара в конец массива корзины
    $_SESSION['basket'][] = $product_id;
}

// Узнаем, с какой страницы юзер нажал кнопку "В корзину", чтобы вернуть его туда же.
// Если страница неизвестна (HTTP_REFERER пустой), кидаем на главную index.php
$return_url = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: " . $return_url);
exit; // Завершаем работу скрипта бэкенда