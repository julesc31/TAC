<?php
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete_preinscription') {
    csrf_check();
    $id = intval($_POST['id'] ?? 0);
    if ($id) { db()->prepare("DELETE FROM preinscriptions WHERE id=?")->execute([$id]); $msg = 'Préinscription supprimée.'; }
}
$rows = db()->query("SELECT * FROM preinscriptions ORDER BY created_at DESC")->fetchAll();
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div>
<?php endif; ?>

<?php if (empty($rows)): ?>
  <p style="color:var(--stone4);text-align:center;padding:3rem 0;font-size:.875rem">Aucune préinscription.</p>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.75rem">
    <?php foreach ($rows as $r): ?>
      <div class="admin-card">
        <div class="admin-card-body">
          <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:flex-start;gap:.5rem">
            <div>
              <p style="color:#fff;font-weight:600;font-size:.9rem"><?= h($r['nom']) ?></p>
              <p style="color:var(--gold);font-size:.8rem"><?= h($r['email']) ?></p>
            </div>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center">
              <?php if ($r['categorie']): ?>
                <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['categorie']) ?></span>
              <?php endif; ?>
              <?php if ($r['niveau']): ?>
                <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['niveau']) ?></span>
              <?php endif; ?>
              <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette préinscription ?')">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="delete_preinscription">
                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                <button type="submit" class="btn-delete"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg></button>
              </form>
            </div>
          </div>
          <?php if ($r['telephone']): ?>
            <p style="color:var(--stone4);font-size:.75rem;margin-top:.5rem">Tél : <?= h($r['telephone']) ?></p>
          <?php endif; ?>
          <?php if ($r['commentaires']): ?>
            <p style="color:var(--stone2);font-size:.8rem;margin-top:.5rem;font-style:italic">"<?= h($r['commentaires']) ?>"</p>
          <?php endif; ?>
          <p style="color:var(--stone5);font-size:.7rem;margin-top:.5rem">Reçu le <?= formatDateFR($r['created_at']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
