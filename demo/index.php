<?php 
// Подключаем конфигурационный файл (наш провод к базе данных и сессиям)
// require_once нужен, чтобы случайно не подключить файл дважды
require_once 'controllers/config.php'; 
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>COMFY - Интернет-магазин сувениров</title>
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
            <?php 
            // Проверяем через сессию, залогинен ли пользователь
            if(isset($_SESSION['user'])): 
            ?>
                <a href="profile.php" class="btn-black"><?= htmlspecialchars($_SESSION['user']['email']) ?></a>
                <a href="logout.php" class="btn-black">Выйти</a>
            <?php else: ?>
                <a href="login.php" class="btn-black">Вход</a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <section id="about" class="promo-banner">
            <h1>Краткая информация</h1>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum impedit fugiat quasi repellat animi expedita, itaque dignissimos culpa, unde minima voluptate quibusdam earum laborum voluptas dicta placeat!</p>
        </section>

        <section>
            <h2 class="section-title">Категории товаров</h2>
            <div class="grid-products">
                <?php
                // Отправляем запрос в БД: выбрать все товары, но ограничить вывод первыми шестью (LIMIT 6)
                $stmt = $pdo->query("SELECT * FROM products LIMIT 6");
                
                // Цикл while работает как конвейер: fetch() достает один товар за другим,
                // пока они не кончатся в переменной $stmt
                while($product = $stmt->fetch()):
                ?>
                <div class="product-card">
                    <div class="product-image-placeholder">СУВЕНИР</div>
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <div class="product-info">
                        <span class="product-price"><?= $product['price'] ?> тг.</span>
                        <a href="controllers/action_add_product.php?id=<?= $product['id'] ?>" class="btn-black">В корзину</a>
                    </div>
                </div>
                <?php endwhile; // Конец цикла вывода товаров ?>
            </div>
        </section>
    </main>

    <footer>
        <div class="social-links">
            <a href="#">Facebook</a> <a href="#">Instagram</a> <a href="#">Pinterest</a> <a href="#">Telegram</a>
        </div>
        <div>+1(111)111-11-11</div>
    </footer>
</body>
</html>