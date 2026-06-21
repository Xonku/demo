<?php 
require_once 'controllers/config.php'; 

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user']['id'];

// --- АВТОМАТИЧЕСКИЙ ПЕРЕВОД В ЗАКРЫТЫЕ ЗАКАЗЫ ---
// Проверяем открытые заказы, у которых дата доставки уже наступила или прошла, и закрываем их
$current_date = date('Y-m-d');
$stmtAutoClose = $pdo->prepare("UPDATE orders SET status = 'closed' WHERE user_id = ? AND status = 'open' AND delivery_date <= ?");
$stmtAutoClose->execute([$user_id, $current_date]);
// ------------------------------------------------

// Получаем актуальную инфо о пользователе
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();

// Получаем обновленные открытые заказы
$stmtOpen = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'open'");
$stmtOpen->execute([$user_id]);
$openOrders = $stmtOpen->fetchAll();

// Получаем обновленные закрытые заказы
$stmtClosed = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? AND status = 'closed'");
$stmtClosed->execute([$user_id]);
$closedOrders = $stmtClosed->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">COMFY.</a></div>
        <nav><a href="index.php">Главная</a><a href="index.php#about">Описание</a><a href="product_list.php">Категории</a><a href="basket.php">Корзина</a></nav>
        <div class="header-right"><a href="logout.php" class="btn-black">Выйти</a></div>
    </header>

    <main>
        <div class="order-info-box">
            <h2>Профиль пользователя</h2><br>
            <p><strong>Имя:</strong> <?= htmlspecialchars($currentUser['name']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($currentUser['email']) ?></p>
            <p><strong>Телефон:</strong> <?= htmlspecialchars($currentUser['phone'] ?? 'Не указан') ?></p>
            <p><strong>Адрес доставки:</strong> <?= htmlspecialchars($currentUser['address'] ?? 'Не указан') ?></p>
        </div>

        <h2>Список заказов пользователя</h2><br>
        
        <h3>Открытые заказы</h3><br>
        <?php if(empty($openOrders)): ?><p>Нет открытых заказов</p><br><?php endif; ?>
        <?php foreach($openOrders as $order): ?>
            <div class="order-info-box flex-between">
                <div>
                    <strong>Номер заказа: <?= $order['order_number'] ?></strong><br>
                    <span>Дата заказа: <?= $order['order_date'] ?> | Доставка: <?= $order['delivery_date'] ?></span>
                </div>
                <div style="display: flex; gap: 20px;">
                    <strong><?= $order['total_price'] ?> тг.</strong>
                    <button class="btn-black" onclick="window.location.href='controllers/action_cancel_order.php?id=<?= $order['id'] ?>'">Отменить заказ</button>
                </div>
            </div>
        <?php endforeach; ?>

        <h3>Закрытые заказы</h3><br>
        <?php if(empty($closedOrders)): ?><p>Нет закрытых заказов</p><br><?php endif; ?>
        <?php foreach($closedOrders as $order): ?>
            <div class="order-info-box flex-between" style="opacity: 0.7;">
                <div>
                    <strong>Номер заказа: <?= $order['order_number'] ?></strong><br>
                    <span>Доставлен: <?= $order['delivery_date'] ?></span>
                </div>
                <div><strong><?= $order['total_price'] ?> тг.</strong></div>
            </div>
        <?php endforeach; ?>
    </main>
    <footer>
        <div class="social-links">
            <a href="#">Facebook</a> <a href="#">Instagram</a> <a href="#">Pinterest</a> <a href="#">Telegram</a>
        </div>
        <div>+1(111)111-11-11</div>
    </footer>
</body>
</html>