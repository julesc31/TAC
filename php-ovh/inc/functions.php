<?php
function h(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $url): never {
    header('Location: ' . $url);
    exit;
}

function isAdmin(): bool {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

function requireAdmin(): void {
    if (!isAdmin()) {
        redirect('/admin/login.php');
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Token CSRF invalide.');
    }
}

function formatDateFR(string $date): string {
    $months = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
               'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $ts = strtotime($date);
    return intval(date('j', $ts)) . ' ' . $months[intval(date('n', $ts))] . ' ' . date('Y', $ts);
}

function uploadPhoto(array $file): string {
    $allowedMime = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!in_array($file['type'], $allowedMime)) {
        throw new RuntimeException('Type de fichier non autorisé.');
    }
    if ($file['size'] > 10 * 1024 * 1024) {
        throw new RuntimeException('Fichier trop volumineux (max 10 Mo).');
    }
    $ext = match($file['type']) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    };
    $name = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = UPLOAD_DIR . $name;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Erreur lors de l\'enregistrement du fichier.');
    }
    return UPLOAD_URL . $name;
}
