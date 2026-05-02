<?php
// ─── Configuration base de données ───────────────────────────────────────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'votre_base');       // À modifier sur OVH
define('DB_USER', 'votre_utilisateur'); // À modifier sur OVH
define('DB_PASS', 'votre_mot_de_passe'); // À modifier sur OVH
define('DB_CHARSET', 'utf8mb4');

// ─── Configuration site ───────────────────────────────────────────────────────
define('SITE_URL', 'https://www.votredomaine.fr'); // À modifier
define('SITE_NAME', 'Arc Club Pechbonnieu');
define('UPLOAD_DIR', __DIR__ . '/../uploads/photos/');
define('UPLOAD_URL', SITE_URL . '/uploads/photos/');

// ─── Google Calendar ──────────────────────────────────────────────────────────
define('GOOGLE_API_KEY', 'AIzaSyD4ESybVVkx5lMGtPkbjlJtol0YJB8fYkg');
define('GOOGLE_CALENDAR_ID', 'pechbonnieu.tiralarc@gmail.com');

// ─── Sécurité admin ───────────────────────────────────────────────────────────
define('ADMIN_SESSION_NAME', 'acp_session');
define('SESSION_LIFETIME', 3600); // 1 heure

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    }
    return $pdo;
}
