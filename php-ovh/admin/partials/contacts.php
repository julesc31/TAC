<?php
$rows = db()->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
?>

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
            <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['sujet']) ?></span>
          </div>
          <p style="color:var(--stone2);font-size:.875rem;line-height:1.6"><?= nl2br(h($r['message'])) ?></p>
          <p style="color:var(--stone5);font-size:.7rem;margin-top:.75rem">Reçu le <?= formatDateFR($r['created_at']) ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
