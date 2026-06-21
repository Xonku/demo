<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация - COMFY</title>
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
    <header><div class="logo"><a href="index.php">COMFY.</a></div></header>
    <main>
        <form action="controllers/action_register.php" method="POST" class="form-container">
            <h2>Регистрация</h2><br>
            <?php if(isset($_GET['error']) && $_GET['error'] == 'email_exists'): ?>
                <p style="color:red;">Этот Email уже зарегистрирован!</p><br>
            <?php endif; ?>
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Пароль:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn-black" style="width:100%;">Продолжить</button>
            <br><br>
            <a href="login.php" class="btn-reg">У вас уже есть аккаунт? Войти</a>
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