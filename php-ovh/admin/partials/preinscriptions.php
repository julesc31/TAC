<?php
$rows = db()->query("SELECT * FROM preinscriptions ORDER BY created_at DESC")->fetchAll();
?>

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
            <div style="display:flex;gap:.5rem;flex-wrap:wrap">
              <?php if ($r['categorie']): ?>
                <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['categorie']) ?></span>
              <?php endif; ?>
              <?php if ($r['niveau']): ?>
                <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;padding:.15rem .6rem;border-radius:9999px"><?= h($r['niveau']) ?></span>
              <?php endif; ?>
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
