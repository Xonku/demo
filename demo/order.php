<?php
require_once 'controllers/config.php';

// Логика №1: Обработка отправки формы (создание нового заказа)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['basket'])) {
    
    // Заставляем войти в аккаунт, если гость пытается оформить заказ без авторизации
    if (!isset($_SESSION['user'])) {
        header('Location: login.php?msg=auth_required');
        exit;
    }

    $user_id = $_SESSION['user']['id'];
    // Генерируем случайный 9-значный номер чека/заказа
    $order_number = rand(100000000, 999999999);
    $order_date = date('Y-m-d'); // Текущая дата
    // strtotime('+14 days') берет текущее время и прибавляет ровно две недели для даты доставки
    $delivery_date = date('Y-m-d', strtotime('+14 days'));
    $payment_method = $_POST['payment_method'];
    
    // Обновляем в таблице пользователей их телефон и адрес, которые они только что ввели в корзине
    $stmtUpdate = $pdo->prepare("UPDATE users SET phone = ?, address = ? WHERE id = ?");
    $stmtUpdate->execute([$_POST['phone'], $_POST['address'], $user_id]);

    // Вытаскиваем товары из базы, чтобы посчитать точную финальную сумму заказа
    $ids = implode(',', array_map('intval', $_SESSION['basket']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $products = $stmt->fetchAll();
    $total_price = 0;
    foreach($products as $p) { $total_price += $p['price']; }

    // Безопасно сохраняем сам заказ через подготовленный запрос (prepare) со знаками вопросов
    $stmtOrder = $pdo->prepare("INSERT INTO orders (user_id, order_number, order_date, delivery_date, payment_method, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtOrder->execute([$user_id, $order_number, $order_date, $delivery_date, $payment_method, $total_price]);
    
    // Получаем ID только что созданного заказа в базе данных
    $order_id = $pdo->lastInsertId();

    // Записываем каждый товар из корзины в связующую таблицу order_items
    $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, price) VALUES (?, ?, ?)");
    foreach($products as $p) {
        $stmtItem->execute([$order_id, $p['id'], $p['price']]);
    }

    // Полностью очищаем корзину в сессии, так как заказ оформлен
    $_SESSION['basket'] = [];
    
    // Перенаправляем на эту же страницу, но уже в режим отображения успешного чека (через GET)
    header("Location: order.php?success_id=" . $order_id);
    exit;
}

// Логика №2: Отображение созданного чека (Страница подтверждения заказа по макету)
if (isset($_GET['success_id'])):
    $order_id = (int)$_GET['success_id'];
    
    // Тянем данные созданного заказа
    $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();
    
    // Тянем данные покупателя
    $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmtUser->execute([$order['user_id']]);
    $u = $stmtUser->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Подтверждение заказа - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">COMFY.</a></div>
        <nav><a href="index.php">Главная</a><a href="index.php#about">Описание</a><a href="product_list.php">Категории</a><a href="basket.php">Корзина</a></nav>
        <div class="header-right">
            <?php if(isset($_SESSION['user'])): ?>
                <a href="profile.php" class="btn-black"><?= htmlspecialchars($_SESSION['user']['email']) ?></a>
                <a href="logout.php" class="btn-black">Выйти</a>
            <?php else: ?>
                <a href="login.php" class="btn-black">Вход</a>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <div class="order-info-box">
            <h2>Номер заказа: <?= $order['order_number'] ?></h2>
            <p>Дата заказа: <?= date('d.m.Y', strtotime($order['order_date'])) ?> | Дата доставки: <?= date('d.m.Y', strtotime($order['delivery_date'])) ?></p>
            <br>
            <div class="basket-layout">
                <div>
                    <h3>Оплата</h3>
                    <p>Метод: <?= htmlspecialchars($order['payment_method']) ?></p>
                </div>
                <div>
                    <h3>Доставка</h3>
                    <p>
                        <?= htmlspecialchars($u['name']) ?><br>
                        <?= htmlspecialchars($u['email']) ?><br>
                        <?= htmlspecialchars($u['address']) ?><br>
                        <?= htmlspecialchars($u['phone']) ?>
                    </p>
                </div>
            </div>
            <br>
            <div style="text-align: center;">
                <a href="profile.php" class="btn-black">Подтвердить заказ</a>
            </div>
        </div>
    </main>
    <footer>
        <div class="social-links">
            <a href="#">Facebook</a> <a href="#">Instagram</a> <a href="#">Pinterest</a> <a href="#">Telegram</a>
        </div>
        <div>+1(111)111-11-11</div>
    </footer>
</body>
</html>
<?php endif; ?>