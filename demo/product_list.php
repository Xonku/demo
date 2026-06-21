<?php 
require_once 'controllers/config.php'; 

// Проверяем, передал ли пользователь тип сортировки в ссылке. 
// Если ничего не передано, по умолчанию ставим сортировку по 'id' (?? 'id')
$sort = $_GET['sort'] ?? 'id';

// Создаем "белый список" разрешенных сортировок, чтобы хакеры не подкинули свой SQL-код
$allowed_sorts = ['price' => 'price ASC', 'popularity' => 'id DESC'];

// Если переданная строка есть в нашем списке, используем её, иначе сбрасываем на дефолтный 'id'
$order_by = $allowed_sorts[$sort] ?? 'id';

// Подставляем безопасную строку сортировки прямо в SQL-запрос
$stmt = $pdo->query("SELECT * FROM products ORDER BY $order_by");

// fetchAll() загребает сразу ВСЕ найденные строчки товаров в один массив
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Товары - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header>
        <div class="logo"><a href="index.php">COMFY.</a></div>
        <nav><a href="index.php">Главная</a><a href="index.php#about">Описание</a><a href="product_list.php">Категории</a><a href="basket.php">Корзина</a></nav>
        <div class="header-right">
            <a href="basket.php" class="btn-black">Корзина <?= isset($_SESSION['basket']) ? count($_SESSION['basket']) : 0 ?></a>
        </div>
    </header>

    <main>
        <h2 class="section-title">Категории товаров</h2>
        <div style="margin-bottom: 20px;">
            Сортировать: 
            <a href="product_list.php?sort=price" class="btn-black">По цене</a> | 
            <a href="product_list.php?sort=popularity" class="btn-black">По популярности</a>
        </div>

        <div class="grid-products">
            <?php 
            // Разворачиваем полученный массив товаров через foreach
            foreach($products as $product): 
            ?>
            <div class="product-card">
                <div class="product-image-placeholder">СУВЕНИР</div>
                <h3><?= htmlspecialchars($product['name']) ?></h3>
                <div class="product-info">
                    <span class="product-price"><?= $product['price'] ?> тг</span>
                    <a href="controllers/action_add_product.php?id=<?= $product['id'] ?>" class="btn-black">В корзину</a>
                </div>
            </div>
            <?php endforeach; ?>
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