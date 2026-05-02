<?php
// Photos CRUD — included from admin/index.php
$cats = ['Entraînement', 'Compétition', 'Extérieur', 'Jeunes', 'Vie du club'];
$msg  = '';
$err  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    csrf_check();
    $action = $_POST['action'];

    if ($action === 'upload') {
        if (!empty($_FILES['photo']['tmp_name'])) {
            try {
                $url = uploadPhoto($_FILES['photo']);
                $cap = trim($_POST['caption'] ?? '');
                $cat = in_array($_POST['category'] ?? '', $cats) ? $_POST['category'] : $cats[0];
                $maxPos = db()->query("SELECT COALESCE(MAX(position),0) FROM photos")->fetchColumn();
                db()->prepare("INSERT INTO photos (url,caption,category,position,created_at) VALUES (?,?,?,?,NOW())")
                    ->execute([$url, $cap, $cat, $maxPos + 1]);
                $msg = 'Photo ajoutée.';
            } catch (Exception $e) {
                $err = $e->getMessage();
            }
        } else {
            $err = 'Aucun fichier sélectionné.';
        }
    }

    if ($action === 'add_url') {
        $url = trim($_POST['url'] ?? '');
        $cap = trim($_POST['caption'] ?? '');
        $cat = in_array($_POST['category'] ?? '', $cats) ? $_POST['category'] : $cats[0];
        if (!$url) { $err = 'URL requise.'; }
        else {
            $maxPos = db()->query("SELECT COALESCE(MAX(position),0) FROM photos")->fetchColumn();
            db()->prepare("INSERT INTO photos (url,caption,category,position,created_at) VALUES (?,?,?,?,NOW())")
                ->execute([$url, $cap, $cat, $maxPos + 1]);
            $msg = 'Photo ajoutée.';
        }
    }

    if ($action === 'edit') {
        $id  = intval($_POST['id'] ?? 0);
        $cap = trim($_POST['caption'] ?? '');
        $cat = in_array($_POST['category'] ?? '', $cats) ? $_POST['category'] : $cats[0];
        db()->prepare("UPDATE photos SET caption=?,category=? WHERE id=?")->execute([$cap, $cat, $id]);
        $msg = 'Photo mise à jour.';
    }

    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        db()->prepare("DELETE FROM photos WHERE id=?")->execute([$id]);
        db()->exec("SET @pos=0; UPDATE photos SET position=(@pos:=@pos+1) ORDER BY position");
        $msg = 'Photo supprimée.';
    }

    if ($action === 'move_up') {
        $id = intval($_POST['id'] ?? 0);
        $row = db()->prepare("SELECT id,position FROM photos WHERE id=?");
        $row->execute([$id]); $row = $row->fetch();
        if ($row && $row['position'] > 1) {
            $prev = db()->prepare("SELECT id,position FROM photos WHERE position=?");
            $prev->execute([$row['position'] - 1]); $prev = $prev->fetch();
            if ($prev) {
                db()->prepare("UPDATE photos SET position=? WHERE id=?")->execute([$prev['position'], $row['id']]);
                db()->prepare("UPDATE photos SET position=? WHERE id=?")->execute([$row['position'], $prev['id']]);
            }
        }
    }

    if ($action === 'move_down') {
        $id = intval($_POST['id'] ?? 0);
        $row = db()->prepare("SELECT id,position FROM photos WHERE id=?");
        $row->execute([$id]); $row = $row->fetch();
        if ($row) {
            $next = db()->prepare("SELECT id,position FROM photos WHERE position=?");
            $next->execute([$row['position'] + 1]); $next = $next->fetch();
            if ($next) {
                db()->prepare("UPDATE photos SET position=? WHERE id=?")->execute([$next['position'], $row['id']]);
                db()->prepare("UPDATE photos SET position=? WHERE id=?")->execute([$row['position'], $next['id']]);
            }
        }
    }
}

$editId   = intval($_GET['edit_photo'] ?? 0);
$editItem = null;
if ($editId > 0) {
    $editItem = db()->prepare("SELECT * FROM photos WHERE id=?");
    $editItem->execute([$editId]);
    $editItem = $editItem->fetch();
}

$showUrlForm = isset($_GET['add_url']);
$photos = db()->query("SELECT * FROM photos ORDER BY position ASC")->fetchAll();
$total  = count($photos);
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="alert alert-error" style="margin-bottom:1rem"><?= h($err) ?></div>
<?php endif; ?>

<!-- Upload + URL buttons -->
<div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-bottom:1rem">
  <form method="POST" enctype="multipart/form-data" id="upload-form">
    <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action" value="upload">
    <label class="btn-upload" style="cursor:pointer">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>
      Uploader une photo
      <input type="file" name="photo" accept="image/*" style="display:none" onchange="this.closest('form').submit()">
    </label>
  </form>

  <a href="?tab=photos&add_url=1" class="btn-url">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
    Ajouter par URL
  </a>
</div>

<?php if ($showUrlForm): ?>
<form method="POST" class="admin-card-form">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="add_url">
  <div class="admin-form-group">
    <label class="admin-label">URL de la photo</label>
    <input type="url" name="url" required placeholder="https://…" class="admin-input">
  </div>
  <div class="admin-row">
    <div class="admin-form-group">
      <label class="admin-label">Légende</label>
      <input type="text" name="caption" placeholder="Description de la photo" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Catégorie</label>
      <select name="category" class="admin-select">
        <?php foreach ($cats as $c): ?><option value="<?= h($c) ?>"><?= h($c) ?></option><?php endforeach; ?>
      </select>
    </div>
  </div>
  <div style="display:flex;gap:.75rem">
    <button type="submit" class="btn-admin-save"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Ajouter</button>
    <a href="?tab=photos" class="btn-admin-cancel"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Annuler</a>
  </div>
</form>
<?php endif; ?>

<?php if (empty($photos)): ?>
  <p style="color:var(--stone4);text-align:center;padding:3rem 0;font-size:.875rem">Aucune photo.</p>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.5rem">
    <?php foreach ($photos as $idx => $photo): ?>
      <div class="admin-card">
        <?php if ($editItem && $editItem['id'] == $photo['id']): ?>
          <div class="admin-card-body" style="display:flex;align-items:center;gap:1rem">
            <img src="<?= h($photo['url']) ?>" alt="" class="admin-photo-thumb">
            <form method="POST" style="flex:1;display:grid;grid-template-columns:1fr 1fr;gap:.75rem;align-items:center">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action" value="edit">
              <input type="hidden" name="id" value="<?= $photo['id'] ?>">
              <input type="text" name="caption" value="<?= h($photo['caption']) ?>" placeholder="Légende" class="admin-input" style="padding:.5rem .75rem">
              <select name="category" class="admin-select" style="padding:.5rem .75rem">
                <?php foreach ($cats as $c): ?><option value="<?= h($c) ?>" <?= $c === $photo['category'] ? 'selected' : '' ?>><?= h($c) ?></option><?php endforeach; ?>
              </select>
              <button type="submit" class="btn-admin-save" style="padding:.4rem .875rem;font-size:.8rem"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg></button>
              <a href="?tab=photos" class="btn-admin-cancel" style="padding:.4rem .875rem;font-size:.8rem"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></a>
            </form>
          </div>
        <?php else: ?>
          <div class="admin-card-body" style="display:flex;align-items:center;gap:1rem">
            <img src="<?= h($photo['url']) ?>" alt="<?= h($photo['caption']) ?>" class="admin-photo-thumb">
            <div style="flex:1;min-width:0">
              <p style="color:#fff;font-size:.875rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= $photo['caption'] ? h($photo['caption']) : '<em style="color:var(--stone5)">Sans légende</em>' ?></p>
              <span style="background:var(--white10);color:var(--stone4);font-size:.7rem;padding:.1rem .5rem;border-radius:9999px;margin-top:.25rem;display:inline-block"><?= h($photo['category']) ?></span>
            </div>
            <div style="display:flex;align-items:center;gap:.25rem;flex-shrink:0">
              <!-- Move up -->
              <form method="POST" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="move_up">
                <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                <button type="submit" class="btn-edit" <?= $idx === 0 ? 'disabled style="opacity:.25"' : '' ?>>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"/></svg>
                </button>
              </form>
              <!-- Move down -->
              <form method="POST" style="display:inline">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="move_down">
                <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                <button type="submit" class="btn-edit" <?= $idx === $total - 1 ? 'disabled style="opacity:.25"' : '' ?>>
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
              </form>
              <!-- Edit -->
              <a href="?tab=photos&edit_photo=<?= $photo['id'] ?>" class="btn-edit">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
              <!-- Delete -->
              <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette photo ?')">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $photo['id'] ?>">
                <button type="submit" class="btn-delete">
                  <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                </button>
              </form>
            </div>
          </div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
