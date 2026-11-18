<?php
require 'common.php';

$user = currentUser($pdo);
if ($user) {
    $pdo->prepare('UPDATE users SET token = NULL WHERE id = :id')
        ->execute([':id' => $user['id']]);
}

// удаляем cookie: ставим срок в прошлом
setcookie('token', '', time() - 3600, '/');
setcookie('bg_color', '', time() - 3600, '/');
setcookie('font_color', '', time() - 3600, '/');

header('Location: login.php');
exit;