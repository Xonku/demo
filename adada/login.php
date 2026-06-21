<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header><div class="logo"><a href="index.php">COMFY.</a></div></header>
    <main>
        <form action="controllers/action_login.php" method="POST" class="form-container">
            <h2>Вход</h2><br>
            <?php if(isset($_GET['msg']) && $_GET['msg'] == 'auth_required'): ?>
                <p style="color:orange;">Пожалуйста, войдите, чтобы оформить заказ.</p><br>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <p style="color:red;">Неверный логин или пароль!</p><br>
            <?php endif; ?>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-black" style="width:100%;">Отправить</button>
            <br><br>
            <a href="registration.php" class="btn-reg">У вас нет аккаунта? Регистрация</a>
        </form>
    </main>
    <footer>
        <div class="social-links">
            <a href="#">Facebook</a> <a href="#">Instagram</a> <a href="#">Pinterest</a> <a href="#">Telegram</a>
        </div>
        <div>+1(111)111-11-11</div>
    </footer>
</body>
</html>