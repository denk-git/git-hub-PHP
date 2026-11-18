<h1>  GitHub Сайт  </h1>

<?php
require 'vendor/autoload.php';
require 'common.php';

$user = currentUser($pdo);
if (!$user) {
    header('Location: login.php');
    exit;
}

// сохранение новых настроек
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bg = safeColor($_POST['bg_color'] ?? '', '#ffffff');
    $font = safeColor($_POST['font_color'] ?? '', '#000000');

    $pdo->prepare('UPDATE users SET bg_color = :bg, font_color = :font WHERE id = :id')
        ->execute([':bg' => $bg, ':font' => $font, ':id' => $user['id']]);
    setSettingsCookies($bg, $font);

    header('Location: index.php');
    exit;
}

// настройки берём из cookie, если их нет, то из БД
$bg = safeColor($_COOKIE['bg_color'] ?? '', $user['bg_color']);
$font = safeColor($_COOKIE['font_color'] ?? '', $user['font_color']);
?>
<body style="background: <?= $bg ?>; color: <?= $font ?>;">
    <h1>Привет, <?= htmlspecialchars($user['login']) ?>!</h1>

    <form method="post">
        Цвет фона: <input type="color" name="bg_color" value="<?= $bg ?>"><br>
        Цвет шрифта: <input type="color" name="font_color" value="<?= $font ?>"><br>
        <button type="submit">Сохранить настройки</button>
    </form>

    <p><a href="logout.php">Выйти</a></p>
</body>

?>