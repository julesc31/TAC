<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_contact') {
    csrf_check();
    $id = intval($_POST['id'] ?? 0);
    if ($id) { db()->prepare("DELETE FROM contacts WHERE id=?")->execute([$id]); $msg = 'Message supprimé.'; }
}
$rows = db()->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div>
<?php endif; ?>

<?php if (empty($rows)): ?>
  <p style="color:var(--stone4);text-align:center;padding:3rem 0;font-size:.875rem">Aucun message.</p>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.75rem">
    <?php foreach ($rows as $r): ?>
      <div class="admin-card">
        <div class="admin-card-body">
          <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:.5rem;margin-bottom:.75rem">
            <div>
              <p style="color:#fff;font-weight:600;font-size:.9rem"><?= h($r['nom']) ?></p>
              <a href="mailto:<?= h($r['email']) ?>" style="color:var(--gold);font-size:.8rem"><?= h($r['email']) ?></a>
            </div>
            <div style="display:flex;align-items:center;gap:.5rem">
              <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['sujet']) ?></span>
              <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer ce message ?')">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="delete_contact">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <button type="submit" class="btn-delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
              </form>
            </div>
          </div>
          <p style="color:var(--stone2);font-size:.875rem;line-height:1.6"><?= nl2br(h($r['message'])) ?></p>
          <p style="color:var(--stone5);font-size:.7rem;margin-top:.75rem">Reçu le <?= formatDateFR($r['created_at']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
