<?php
require_once __DIR__ . '/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$id     = isset($_GET['id']) ? (int) $_GET['id'] : null;

// GET — public
if ($method === 'GET') {
    $db    = getDB();
    $limit = isset($_GET['limit']) ? max(1, (int) $_GET['limit']) : null;
    $sql   = 'SELECT id, tag, title, body, published_at, created_at FROM news ORDER BY published_at DESC';
    if ($limit !== null) $sql .= ' LIMIT ' . $limit;
    $rows = $db->query($sql)->fetchAll();
    foreach ($rows as &$row) {
        $row['id'] = (string) $row['id'];
    }
    jsonOut(['data' => $rows]);
}

requireAdmin();

if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $db    = getDB();
    $stmt  = $db->prepare('INSERT INTO news (tag, title, body, published_at) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $input['tag']          ?? '',
        $input['title']        ?? '',
        $input['body']         ?? '',
        $input['published_at'] ?? date('Y-m-d'),
    ]);
    jsonOut(['success' => true, 'id' => (string) $db->lastInsertId()]);
}

if ($method === 'PUT' && $id) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $db    = getDB();
    $stmt  = $db->prepare('UPDATE news SET tag=?, title=?, body=?, published_at=? WHERE id=?');
    $stmt->execute([
        $input['tag']          ?? '',
        $input['title']        ?? '',
        $input['body']         ?? '',
        $input['published_at'] ?? date('Y-m-d'),
        $id,
    ]);
    jsonOut(['success' => true]);
}

if ($method === 'DELETE' && $id) {
    $db = getDB();
    $db->prepare('DELETE FROM news WHERE id=?')->execute([$id]);
    jsonOut(['success' => true]);
}

jsonOut(['error' => 'Requête invalide.'], 400);
