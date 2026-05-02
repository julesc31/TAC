<?php
require_once __DIR__ . '/db.php';

startSession();

$action = $_GET['action'] ?? '';

if ($action === 'check') {
    jsonOut(['loggedIn' => isAdmin()]);
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $email    = trim($input['email'] ?? '');
    $password = $input['password'] ?? '';

    if ($email === ADMIN_EMAIL && password_verify($password, ADMIN_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['admin'] = true;
        jsonOut(['success' => true]);
    } else {
        jsonOut(['success' => false, 'error' => 'Email ou mot de passe incorrect.'], 401);
    }
}

if ($action === 'logout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION = [];
    session_destroy();
    jsonOut(['success' => true]);
}

jsonOut(['error' => 'Action invalide.'], 400);
