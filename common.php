<?php
$pdo = new PDO('mysql:host=localhost;dbname=php_KARNOPOV;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ищем пользователя по токену из cookie
function currentUser(PDO $pdo) {
    $token = $_COOKIE['token'] ?? '';
    if ($token === '') {
        return null;
    }
    $stmt = $pdo->prepare('SELECT * FROM users WHERE token = :t');
    $stmt->execute([':t' => $token]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// настройки уходят на клиент в cookie на 30 дней
function setSettingsCookies($bg, $font) {
    $exp = time() + 60 * 60 * 24 * 30;
    setcookie('bg_color', $bg, $exp, '/');
    setcookie('font_color', $font, $exp, '/');
}

// цвет должен быть вида #rrggbb, иначе берём запасной
function safeColor($value, $default) {
    return preg_match('/^#[0-9a-fA-F]{6}$/', (string)$value) ? $value : $default;
}