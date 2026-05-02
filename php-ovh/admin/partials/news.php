<?php
// News CRUD — included from admin/index.php
$tags = ['Entraînement', 'Compétition', 'Annonce', 'Club'];
$msg  = '';
$err  = '';

// ── Actions ────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    csrf_check();
    $action = $_POST['action'];

    if ($action === 'add' || $action === 'edit') {
        $tag    = in_array($_POST['tag'] ?? '', $tags) ? $_POST['tag'] : $tags[0];
        $title  = trim($_POST['title'] ?? '');
        $body   = trim($_POST['body'] ?? '');
        $pubAt  = $_POST['published_at'] ?? date('Y-m-d');

        if (!$title || !$body) { $err = 'Titre et contenu requis.'; }
        else {
            if ($action === 'add') {
                db()->prepare("INSERT INTO news (tag,title,body,published_at,created_at) VALUES (?,?,?,?,NOW())")
                    ->execute([$tag, $title, $body, $pubAt]);
                $msg = 'Actualité ajoutée.';
            } else {
                $id = intval($_POST['id'] ?? 0);
                db()->prepare("UPDATE news SET tag=?,title=?,body=?,published_at=? WHERE id=?")
                    ->execute([$tag, $title, $body, $pubAt, $id]);
                $msg = 'Actualité mise à jour.';
            }
        }
    }
    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        db()->prepare("DELETE FROM news WHERE id=?")->execute([$id]);
        $msg = 'Actualité supprimée.';
    }
}

$editId   = intval($_GET['edit'] ?? 0);
$showForm = isset($_GET['add']) || $editId > 0;
$editItem = null;
if ($editId > 0) {
    $editItem = db()->prepare("SELECT * FROM news WHERE id=?");
    $editItem->execute([$editId]);
    $editItem = $editItem->fetch();
}

$allNews = db()->query("SELECT * FROM news ORDER BY published_at DESC")->fetchAll();
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="alert alert-error" style="margin-bottom:1rem"><?= h($err) ?></div>
<?php endif; ?>

<a href="?tab=news&add=1" class="btn-admin-add">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  Nouvelle actualité
</a>

<?php if ($showForm): ?>
<form method="POST" class="admin-card-form">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="<?= $editItem ? 'edit' : 'add' ?>">
  <?php if ($editItem): ?><input type="hidden" name="id" value="<?= $editItem['id'] ?>"><?php endif; ?>

  <div class="admin-row">
    <div class="admin-form-group">
      <label class="admin-label">Catégorie</label>
      <select name="tag" class="admin-select">
        <?php foreach ($tags as $t): ?>
          <option value="<?= h($t) ?>" <?= ($editItem['tag'] ?? $tags[0]) === $t ? 'selected' : '' ?>><?= h($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Date de publication</label>
      <input type="date" name="published_at" value="<?= h($editItem['published_at'] ?? date('Y-m-d')) ?>" class="admin-input">
    </div>
  </div>
  <div class="admin-form-group">
    <label class="admin-label">Titre</label>
    <input type="text" name="title" required value="<?= h($editItem['title'] ?? '') ?>" placeholder="Titre de l'actualité" class="admin-input">
  </div>
  <div class="admin-form-group">
    <label class="admin-label">Contenu</label>
    <textarea name="body" required rows="4" placeholder="Rédigez votre actualité…" class="admin-textarea"><?= h($editItem['body'] ?? '') ?></textarea>
  </div>
  <div style="display:flex;gap:.75rem">
    <button type="submit" class="btn-admin-save">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      <?= $editItem ? 'Enregistrer' : 'Publier' ?>
    </button>
    <a href="?tab=news" class="btn-admin-cancel">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      Annuler
    </a>
  </div>
</form>
<?php endif; ?>

<?php if (empty($allNews)): ?>
  <p style="color:var(--stone4);text-align:center;padding:3rem 0;font-size:.875rem">Aucune actualité.</p>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.75rem">
    <?php foreach ($allNews as $item): ?>
      <div class="admin-card">
        <div class="admin-card-body" style="display:flex;align-items:flex-start;gap:1rem">
          <div style="flex:1;min-width:0">
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:.375rem">
              <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;font-weight:700;padding:.15rem .6rem;border-radius:9999px"><?= h($item['tag']) ?></span>
              <span style="color:var(--stone5);font-size:.75rem"><?= formatDateFR($item['published_at']) ?></span>
            </div>
            <p style="color:#fff;font-weight:600;font-size:.875rem;margin-bottom:.25rem"><?= h($item['title']) ?></p>
            <p style="color:var(--stone4);font-size:.75rem;line-height:1.5;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical"><?= h($item['body']) ?></p>
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0">
            <a href="?tab=news&edit=<?= $item['id'] ?>" class="btn-edit">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette actualité ?')">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action" value="delete">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">
              <button type="submit" class="btn-delete">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
