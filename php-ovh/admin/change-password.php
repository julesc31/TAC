<?php
/**
 * Utilitaire pour changer le mot de passe admin
 * Accéder une fois via : https://votresite.fr/admin/change-password.php
 * Supprimer ce fichier après utilisation !
 */
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();
requireAdmin();

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $current  = $_POST['current_password'] ?? '';
    $new      = $_POST['new_password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (strlen($new) < 8) {
        $err = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif ($new !== $confirm) {
        $err = 'Les mots de passe ne correspondent pas.';
    } else {
        $stmt = db()->prepare("SELECT password_hash FROM admin_users WHERE id=?");
        $stmt->execute([$_SESSION['admin_id']]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($current, $user['password_hash'])) {
            $err = 'Mot de passe actuel incorrect.';
        } else {
            $hash = password_hash($new, PASSWORD_BCRYPT, ['cost' => 12]);
            db()->prepare("UPDATE admin_users SET password_hash=? WHERE id=?")->execute([$hash, $_SESSION['admin_id']]);
            $msg = 'Mot de passe modifié avec succès.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Changer le mot de passe — Admin</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem">
<div style="width:100%;max-width:26rem">
  <h1 style="color:#fff;font-size:1.5rem;font-weight:700;margin-bottom:1.5rem;text-align:center">Changer le mot de passe</h1>

  <?php if ($msg): ?><div class="alert alert-success"><?= h($msg) ?></div><?php endif; ?>
  <?php if ($err): ?><div class="alert alert-error"><?= h($err) ?></div><?php endif; ?>

  <form method="POST" style="background:var(--white10);border:1px solid var(--white10);border-radius:1.25rem;padding:2rem;display:flex;flex-direction:column;gap:1rem">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <div><label class="admin-label" style="font-size:.875rem;color:var(--stone2)">Mot de passe actuel</label><input type="password" name="current_password" required class="admin-input" style="margin-top:.375rem"></div>
    <div><label class="admin-label" style="font-size:.875rem;color:var(--stone2)">Nouveau mot de passe</label><input type="password" name="new_password" required minlength="8" class="admin-input" style="margin-top:.375rem"></div>
    <div><label class="admin-label" style="font-size:.875rem;color:var(--stone2)">Confirmer le nouveau mot de passe</label><input type="password" name="confirm_password" required class="admin-input" style="margin-top:.375rem"></div>
    <button type="submit" class="btn btn-primary" style="margin-top:.5rem;justify-content:center">Changer le mot de passe</button>
  </form>
  <div style="text-align:center;margin-top:1rem"><a href="/admin/" style="color:var(--stone5);font-size:.8rem">← Retour à l'administration</a></div>
</div>
</body>
</html>
