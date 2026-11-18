<?php
require 'common.php';

// автовход: токен в cookie уже есть и верный
if (currentUser($pdo)) {
    header('Location: index.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE login = :login');
    $stmt->execute([':login' => $login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $token = bin2hex(random_bytes(32));
        $pdo->prepare('UPDATE users SET token = :t WHERE id = :id')
            ->execute([':t' => $token, ':id' => $user['id']]);

        setcookie('token', $token, [
            'expires'  => time() + 60 * 60 * 24 * 30,
            'path'     => '/',
            'httponly' => true,
        ]);
        setSettingsCookies($user['bg_color'], $user['font_color']);

        header('Location: index.php');
        exit;
    }
    $message = 'Неверный логин или пароль';
}
?>
<h1>Вход</h1>
<p><?= $message ?></p>
<form method="post">
    Логин: <input type="text" name="login"><br>
    Пароль: <input type="password" name="password"><br>
    <button type="submit">Войти</button>
</form>
<a href="register.php">Регистрация</a>