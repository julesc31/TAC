<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

if (isAdmin()) redirect('/admin/');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        try {
            $stmt = db()->prepare("SELECT id, password_hash FROM admin_users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            if ($user && password_verify($password, $user['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id']        = $user['id'];
                redirect('/admin/');
            }
        } catch (Exception $e) {}
    }
    $error = 'Email ou mot de passe incorrect.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Administration — Arc Club Pechbonnieu</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body" style="display:flex;align-items:center;justify-content:center;min-height:100vh;padding:1rem">

<div style="width:100%;max-width:26rem">
  <div style="text-align:center;margin-bottom:2rem">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:1rem;background:rgba(255,215,0,.1);border:1px solid rgba(255,215,0,.3);margin-bottom:1rem">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="1.5"><circle cx="12" cy="8" r="3"/><path d="m15 19h-6a3 3 0 0 1-3-3v-1a5 5 0 0 1 10 0v1a3 3 0 0 1-3 3z"/><circle cx="12" cy="12" r="10"/></svg>
    </div>
    <h1 style="font-size:1.5rem;font-weight:700;color:#fff">Espace administration</h1>
    <p style="color:var(--stone4);font-size:.875rem;margin-top:.25rem">Arc Club Pechbonnieu</p>
  </div>

  <form method="POST" style="background:var(--white10);border:1px solid var(--white10);border-radius:1.25rem;padding:2rem">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

    <?php if ($error): ?>
      <div class="alert alert-error" style="margin-bottom:1.25rem">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <?= h($error) ?>
      </div>
    <?php endif; ?>

    <div class="admin-form-group">
      <label for="email-input" class="admin-label" style="font-size:.875rem;margin-bottom:.5rem;color:var(--stone2)">Adresse e-mail</label>
      <input type="email" name="email" id="email-input" required placeholder="admin@example.com" value="<?= h($_POST['email'] ?? '') ?>"
             autocomplete="email" class="admin-input" style="padding:.75rem 1rem;border-radius:.75rem">
    </div>

    <div class="admin-form-group" style="position:relative">
      <label for="pwd-input" class="admin-label" style="font-size:.875rem;margin-bottom:.5rem;color:var(--stone2)">Mot de passe</label>
      <input type="password" name="password" required placeholder="••••••••" id="pwd-input"
             autocomplete="current-password" class="admin-input" style="padding:.75rem 1rem;padding-right:3rem;border-radius:.75rem">
      <button type="button" onclick="togglePwd()" style="position:absolute;right:.75rem;bottom:.75rem;background:none;border:none;color:var(--stone4);cursor:pointer;padding:.25rem;display:flex;align-items:center" title="Afficher/masquer">
        <svg id="eye-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1.25rem;padding:.875rem;border-radius:.875rem">
      Se connecter
    </button>
  </form>

  <div style="text-align:center;margin-top:1.5rem">
    <a href="/" style="color:var(--stone5);font-size:.8rem;transition:color .2s" onmouseover="this.style.color='var(--stone2)'" onmouseout="this.style.color='var(--stone5)'">← Retour au site</a>
  </div>
</div>

<script>
function togglePwd(){
  const i = document.getElementById('pwd-input');
  i.type = i.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
