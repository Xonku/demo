<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!empty($name) && !empty($email) && !empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$name, $email, $hashedPassword]);
            $_SESSION['user'] = [
                'id' => $pdo->lastInsertId(),
                'name' => $name,
                'email' => $email
            ];
            header('Location: ../profile.php');
            exit;
        } catch (PDOException $e) {
            header('Location: ../registration.php?error=email_exists');
            exit;
        }
    }
}
header('Location: ../registration.php');