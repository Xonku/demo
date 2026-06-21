<?php 
require_once 'controllers/config.php'; 

$basket_products = [];
$total_sum = 0;

if (!empty($_SESSION['basket'])) {
    $ids = implode(',', array_map('intval', $_SESSION['basket']));
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($ids)");
    $basket_products = $stmt->fetchAll();
    
    foreach($basket_products as $p) {
        $total_sum += $p['price'];
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Корзина - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">COMFY.</a></div>
        <nav>
            <a href="index.php">Главная</a>
            <a href="#about">Описание</a>
            <a href="product_list.php">Категории</a>
            <a href="basket.php">Корзина</a>
        </nav>
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
        <h2 class="section-title">Корзина</h2>
        <div class="basket-layout">
            <div>
                <?php if(empty($basket_products)): ?>
                    <p>Ваша корзина пуста</p>
                <?php else: ?>
                    <?php foreach($basket_products as $item): ?>
                        <div class="order-info-box flex-between" style="align-items: center;">
                            <div>
                                <strong><?= htmlspecialchars($item['name']) ?></strong>
                            </div>
        
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <div><?= $item['price'] ?> тг.</div>
                                <button class="btn-black" onclick="window.location.href='controllers/action_remove_product.php?id=<?= $item['id'] ?>'">Удалить</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="flex-between"><h3>Общая сумма:</h3><h3><?= $total_sum ?> тг.</h3></div>
                <?php endif; ?>
            </div>

            <?php if(!empty($basket_products)): ?>
            <div>
                <form action="order.php" method="POST" class="form-container" style="margin:0; width:100%;">
                    <h3>Оформление заказа</h3><br>
                    <div class="form-group">
                        <label>ФИО:</label>
                        <input type="text" name="fio" required value="<?= $_SESSION['user']['name'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Телефон:</label>
                        <input type="text" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" name="email" required value="<?= $_SESSION['user']['email'] ?? '' ?>">
                    </div>
                    <div class="form-group">
                        <label>Адрес:</label>
                        <textarea name="address" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Выберите метод оплаты:</label>
                        <select name="payment_method">
                            <option value="Visa **56">Visa **56</option>
                            <option value="Kaspi Gold">Kaspi Gold</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-black" style="width: 100%;">Отправить заказ</button>
                </form>
            </div>
            <?php endif; ?>
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