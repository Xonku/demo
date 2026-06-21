<?php
require_once 'config.php';

if (isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    
    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }
    
    $_SESSION['basket'][] = $product_id;
}

$return_url = $_SERVER['HTTP_REFERER'] ?? '../index.php';
header("Location: " . $return_url);
exit;