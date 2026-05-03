<?php
$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['action'] ?? '';

    if ($action === 'add_email') {
        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $err = 'Adresse email invalide.';
        } else {
            try {
                db()->prepare("INSERT IGNORE INTO notification_emails (email) VALUES (?)")->execute([$email]);
                $msg = 'Email ajouté.';
            } catch (Exception $e) { $err = $e->getMessage(); }
        }
    }

    if ($action === 'delete_email') {
        $id = intval($_POST['id'] ?? 0);
        if ($id) { db()->prepare("DELETE FROM notification_emails WHERE id=?")->execute([$id]); $msg = 'Email supprimé.'; }
    }
}

$emails = db()->query("SELECT * FROM notification_emails ORDER BY id")->fetchAll();
?>

<?php if ($msg): ?><div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="alert alert-error" style="margin-bottom:1rem"><?= h($err) ?></div><?php endif; ?>

<div class="admin-card" style="margin-bottom:1.5rem">
  <div class="admin-card-body">
    <h2 style="color:#fff;font-size:1rem;font-weight:600;margin-bottom:1rem">Emails de notification</h2>
    <p style="color:var(--stone4);font-size:.8rem;margin-bottom:1.25rem">Ces adresses reçoivent un email à chaque nouveau message de contact ou préinscription.</p>

    <?php if (empty($emails)): ?>
      <p style="color:var(--stone5);font-size:.85rem;margin-bottom:1rem">Aucun email configuré.</p>
    <?php else: ?>
      <div style="display:flex;flex-direction:column;gap:.5rem;margin-bottom:1.25rem">
        <?php foreach ($emails as $e): ?>
          <div style="display:flex;align-items:center;justify-content:space-between;background:var(--white10);border:1px solid var(--white10);border-radius:.75rem;padding:.625rem 1rem">
            <span style="color:var(--stone2);font-size:.875rem"><?= h($e['email']) ?></span>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet email ?')">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action" value="delete_email">
              <input type="hidden" name="id" value="<?= $e['id'] ?>">
              <button type="submit" class="btn-delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" style="display:flex;gap:.75rem;align-items:flex-end">
      <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
      <input type="hidden" name="action" value="add_email">
      <div style="flex:1">
        <label style="font-size:.8rem;color:var(--stone4);display:block;margin-bottom:.35rem">Ajouter une adresse</label>
        <input type="email" name="email" required placeholder="email@exemple.fr" class="admin-input" style="padding:.625rem .875rem;border-radius:.75rem">
      </div>
      <button type="submit" class="btn-admin-save" style="padding:.625rem 1rem">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Ajouter
      </button>
    </form>
  </div>
</div>
