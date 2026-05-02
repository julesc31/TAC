<?php
require_once __DIR__ . '/inc/config.php';

$newPassword = 'TACadmin2026!';
$hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

try {
    $stmt = db()->prepare("UPDATE admin_users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, 'pechbonnieu.tiralarc@gmail.com']);
    echo "OK — Connecte-toi avec : pechbonnieu.tiralarc@gmail.com / " . $newPassword;
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
}
