<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=php_KARNOPOV;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Ошибка подключения: ' . $e->getMessage());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';
    $bg = $_POST['bg_color'] ?? '#ffffff';
    $font = $_POST['font_color'] ?? '#000000';

    if ($login === '' || $password === '') {
        $message = 'Заполните логин и пароль';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO users (login, password, bg_color, font_color)
                 VALUES (:login, :password, :bg, :font)'
            );
            $stmt->execute([
                ':login' => $login,
                ':password' => password_hash($password, PASSWORD_DEFAULT),
                ':bg' => $bg,
                ':font' => $font,
            ]);
            $message = 'Регистрация успешна. <a href="login.php">Войти</a>';
        } catch (PDOException $e) {
            $message = 'Такой логин уже занят';
        }
    }
}
?>
<h1>Регистрация</h1>
<p><?= $message ?></p>
<form method="post">
    Логин: <input type="text" name="login"><br>
    Пароль: <input type="password" name="password"><br>
    Цвет фона: <input type="color" name="bg_color" value="#ffffff"><br>
    Цвет шрифта: <input type="color" name="font_color" value="#000000"><br>
    <button type="submit">Зарегистрироваться</button>
</form>