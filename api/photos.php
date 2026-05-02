<?php
require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id'])     ? (int) $_GET['id'] : null;
$action = $_GET['action'] ?? '';

// GET — public
if ($method === 'GET') {
    $db   = getDB();
    $rows = $db->query('SELECT id, url, caption, category, position, created_at FROM photos ORDER BY position ASC')->fetchAll();
    foreach ($rows as &$row) {
        $row['id']       = (string) $row['id'];
        $row['position'] = (int)    $row['position'];
    }
    jsonOut(['data' => $rows]);
}

requireAdmin();

// Upload de fichier
if ($method === 'POST' && $action === 'upload') {
    if (empty($_FILES['file'])) jsonOut(['error' => 'Aucun fichier fourni.'], 400);

    $file    = $_FILES['file'];
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo   = finfo_open(FILEINFO_MIME_TYPE);
    $mime    = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed, true))   jsonOut(['error' => 'Type de fichier non autorisé (jpg, png, gif, webp uniquement).'], 400);
    if ($file['size'] > 10 * 1024 * 1024)  jsonOut(['error' => 'Fichier trop volumineux (10 Mo maximum).'], 400);

    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
        default      => 'jpg',
    };

    $uploadDir = __DIR__ . '/../uploads/photos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $fileName = uniqid('photo_', true) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
        jsonOut(['error' => "Erreur lors de l'enregistrement du fichier."], 500);
    }

    jsonOut(['success' => true, 'url' => '/uploads/photos/' . $fileName]);
}

// Ajout par URL
if ($method === 'POST') {
    $input  = json_decode(file_get_contents('php://input'), true) ?? [];
    $db     = getDB();
    $maxPos = (int) $db->query('SELECT COALESCE(MAX(position), 0) FROM photos')->fetchColumn();
    $stmt   = $db->prepare('INSERT INTO photos (url, caption, category, position) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $input['url']      ?? '',
        $input['caption']  ?? '',
        $input['category'] ?? '',
        $maxPos + 1,
    ]);
    jsonOut(['success' => true, 'id' => (string) $db->lastInsertId()]);
}

// Échange de positions (moveUp / moveDown)
if ($method === 'PUT' && $action === 'swap') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $db    = getDB();
    $stmt  = $db->prepare('UPDATE photos SET position=? WHERE id=?');
    $stmt->execute([(int) $input['pos2'], (int) $input['id1']]);
    $stmt->execute([(int) $input['pos1'], (int) $input['id2']]);
    jsonOut(['success' => true]);
}

// Mise à jour légende / catégorie
if ($method === 'PUT' && $id) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $db    = getDB();
    $stmt  = $db->prepare('UPDATE photos SET caption=?, category=? WHERE id=?');
    $stmt->execute([$input['caption'] ?? '', $input['category'] ?? '', $id]);
    jsonOut(['success' => true]);
}

// Suppression
if ($method === 'DELETE' && $id) {
    $db   = getDB();
    $stmt = $db->prepare('SELECT url FROM photos WHERE id=?');
    $stmt->execute([$id]);
    $photo = $stmt->fetch();
    // Supprime le fichier local s'il a été uploadé sur le serveur
    if ($photo && str_starts_with($photo['url'], '/uploads/photos/')) {
        $path = __DIR__ . '/..' . $photo['url'];
        if (file_exists($path)) unlink($path);
    }
    $db->prepare('DELETE FROM photos WHERE id=?')->execute([$id]);
    jsonOut(['success' => true]);
}

jsonOut(['error' => 'Requête invalide.'], 400);
