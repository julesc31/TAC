<?php
// Résultats CRUD — included from admin/index.php

$COMPETITION_TYPES = [
    'Tir 18m en salle',
    'Beursault',
    'Bouquet provincial',
    'TAE (Tir à l\'Arc en Extérieur)',
    'Nature',
    'Campagne',
    '3D',
    'Tir en campagne FITA',
    'Autre',
];

$msg = '';
$err = '';

// ── Paramètres de navigation ────────────────────────────────────────────────
$competitionId = intval($_GET['competition_id'] ?? 0); // vue détail d'une compétition
$editCompId    = intval($_GET['edit_comp'] ?? 0);      // édition d'une compétition
$addComp       = isset($_GET['add_comp']);              // formulaire nouvelle compétition

// ── Actions POST ────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    csrf_check();
    $action = $_POST['action'];

    // ── Compétitions ────────────────────────────────────────────────────────
    if ($action === 'add_comp' || $action === 'edit_comp') {
        $titre   = trim($_POST['titre'] ?? '');
        $type    = trim($_POST['type_competition'] ?? '');
        $lieu    = trim($_POST['lieu'] ?? '');
        $date    = $_POST['date'] ?? '';
        $lien    = trim($_POST['lien_officiel'] ?? '');

        if (!$titre || !$type || !$date) {
            $err = 'Titre, type et date sont requis.';
        } else {
            if ($action === 'add_comp') {
                db()->prepare("INSERT INTO competitions (titre,type_competition,lieu,date,lien_officiel,created_at) VALUES (?,?,?,?,?,NOW())")
                    ->execute([$titre, $type, $lieu, $date, $lien]);
                $newId = db()->lastInsertId();
                $msg = 'Compétition ajoutée.';
                // Redirige vers le détail pour ajouter les archers
                redirect('/admin/?tab=resultats&competition_id=' . $newId . '&msg=added');
            } else {
                $id = intval($_POST['comp_id'] ?? 0);
                db()->prepare("UPDATE competitions SET titre=?,type_competition=?,lieu=?,date=?,lien_officiel=? WHERE id=?")
                    ->execute([$titre, $type, $lieu, $date, $lien, $id]);
                $msg = 'Compétition mise à jour.';
                redirect('/admin/?tab=resultats&msg=updated');
            }
        }
    }

    if ($action === 'delete_comp') {
        $id = intval($_POST['comp_id'] ?? 0);
        db()->prepare("DELETE FROM competitions WHERE id=?")->execute([$id]);
        redirect('/admin/?tab=resultats&msg=deleted');
    }

    // ── Résultats (archers) ──────────────────────────────────────────────────
    if ($action === 'add_resultat') {
        $cid      = intval($_POST['competition_id'] ?? 0);
        $nom      = trim($_POST['nom'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $place    = $_POST['place'] !== '' ? intval($_POST['place']) : null;

        if (!$nom) {
            $err = 'Le nom de l\'archer est requis.';
            $competitionId = $cid;
        } else {
            db()->prepare("INSERT INTO resultats (competition_id,nom,categorie,place,created_at) VALUES (?,?,?,?,NOW())")
                ->execute([$cid, $nom, $categorie, $place]);
            redirect('/admin/?tab=resultats&competition_id=' . $cid . '&msg=archer_added');
        }
    }

    if ($action === 'edit_resultat') {
        $rid      = intval($_POST['resultat_id'] ?? 0);
        $cid      = intval($_POST['competition_id'] ?? 0);
        $nom      = trim($_POST['nom'] ?? '');
        $categorie = trim($_POST['categorie'] ?? '');
        $place    = $_POST['place'] !== '' ? intval($_POST['place']) : null;

        if (!$nom) {
            $err = 'Le nom de l\'archer est requis.';
            $competitionId = $cid;
        } else {
            db()->prepare("UPDATE resultats SET nom=?,categorie=?,place=? WHERE id=?")
                ->execute([$nom, $categorie, $place, $rid]);
            redirect('/admin/?tab=resultats&competition_id=' . $cid . '&msg=archer_updated');
        }
    }

    if ($action === 'delete_resultat') {
        $rid = intval($_POST['resultat_id'] ?? 0);
        $cid = intval($_POST['competition_id'] ?? 0);
        db()->prepare("DELETE FROM resultats WHERE id=?")->execute([$rid]);
        redirect('/admin/?tab=resultats&competition_id=' . $cid . '&msg=archer_deleted');
    }
}

// Messages de confirmation via GET
if (isset($_GET['msg'])) {
    $msgs = [
        'added'          => 'Compétition ajoutée.',
        'updated'        => 'Compétition mise à jour.',
        'deleted'        => 'Compétition supprimée.',
        'archer_added'   => 'Archer ajouté.',
        'archer_updated' => 'Archer mis à jour.',
        'archer_deleted' => 'Archer supprimé.',
    ];
    $msg = $msgs[$_GET['msg']] ?? '';
}

// ── Charger les données ─────────────────────────────────────────────────────
$allComps = db()->query("SELECT * FROM competitions ORDER BY date DESC")->fetchAll();

$editComp = null;
if ($editCompId > 0) {
    $stmt = db()->prepare("SELECT * FROM competitions WHERE id=?");
    $stmt->execute([$editCompId]);
    $editComp = $stmt->fetch();
}

$currentComp = null;
$compResultats = [];
$editResultatId = intval($_GET['edit_resultat'] ?? 0);
$editResultat = null;

if ($competitionId > 0) {
    $stmt = db()->prepare("SELECT * FROM competitions WHERE id=?");
    $stmt->execute([$competitionId]);
    $currentComp = $stmt->fetch();
    if ($currentComp) {
        $stmt2 = db()->prepare("SELECT * FROM resultats WHERE competition_id=? ORDER BY place ASC, nom ASC");
        $stmt2->execute([$competitionId]);
        $compResultats = $stmt2->fetchAll();
    }
    if ($editResultatId > 0) {
        $stmt3 = db()->prepare("SELECT * FROM resultats WHERE id=? AND competition_id=?");
        $stmt3->execute([$editResultatId, $competitionId]);
        $editResultat = $stmt3->fetch();
    }
}
?>

<?php if ($msg): ?>
  <div class="alert alert-success" style="margin-bottom:1rem"><?= h($msg) ?></div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="alert alert-error" style="margin-bottom:1rem"><?= h($err) ?></div>
<?php endif; ?>

<?php
// ════════════════════════════════════════════════════════════════════════════
// VUE DÉTAIL D'UNE COMPÉTITION (gestion des archers)
// ════════════════════════════════════════════════════════════════════════════
if ($competitionId > 0 && $currentComp):
?>

<div style="margin-bottom:1.25rem">
  <a href="?tab=resultats" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--stone4);font-size:.82rem;transition:color .2s" onmouseover="this.style.color='var(--stone2)'" onmouseout="this.style.color='var(--stone4)'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Retour aux compétitions
  </a>
</div>

<!-- Info compétition -->
<div style="background:var(--white10);border:1px solid var(--white10);border-radius:1rem;padding:1.25rem 1.5rem;margin-bottom:1.5rem">
  <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;flex-wrap:wrap">
    <div>
      <p style="color:var(--stone4);font-size:.75rem;margin-bottom:.25rem"><?= h($currentComp['type_competition']) ?> · <?= h(formatDateFR($currentComp['date'])) ?><?= $currentComp['lieu'] ? ' · ' . h($currentComp['lieu']) : '' ?></p>
      <h2 style="color:#fff;font-weight:700;font-size:1.05rem"><?= h($currentComp['titre']) ?></h2>
    </div>
    <a href="?tab=resultats&edit_comp=<?= $currentComp['id'] ?>" class="btn-edit" style="flex-shrink:0">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
    </a>
  </div>
</div>

<!-- Bouton ajouter un archer -->
<a href="?tab=resultats&competition_id=<?= $competitionId ?>&add_archer=1" class="btn-admin-add" style="margin-bottom:1rem">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  Ajouter un archer
</a>

<!-- Formulaire ajout archer -->
<?php if (isset($_GET['add_archer'])): ?>
<form method="POST" class="admin-card-form" style="margin-bottom:1.25rem">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="add_resultat">
  <input type="hidden" name="competition_id" value="<?= $competitionId ?>">
  <div class="admin-row">
    <div class="admin-form-group">
      <label class="admin-label">Nom de l'archer</label>
      <input type="text" name="nom" required placeholder="Prénom Nom" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Catégorie</label>
      <input type="text" name="categorie" placeholder="ex : Senior H CL" class="admin-input">
    </div>
    <div class="admin-form-group" style="max-width:140px">
      <label class="admin-label">Place</label>
      <input type="number" name="place" min="1" placeholder="ex : 3" class="admin-input">
    </div>
  </div>
  <div style="display:flex;gap:.75rem">
    <button type="submit" class="btn-admin-save">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      Ajouter
    </button>
    <a href="?tab=resultats&competition_id=<?= $competitionId ?>" class="btn-admin-cancel">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      Annuler
    </a>
  </div>
</form>
<?php endif; ?>

<!-- Liste des archers -->
<?php if (empty($compResultats)): ?>
  <p style="color:var(--stone4);text-align:center;padding:2.5rem 0;font-size:.875rem">Aucun archer enregistré pour cette compétition.</p>
<?php else: ?>
  <div style="background:var(--white10);border:1px solid var(--white10);border-radius:1rem;overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:.875rem">
      <thead>
        <tr style="border-bottom:1px solid rgba(255,255,255,.1)">
          <th style="text-align:left;padding:.75rem 1rem;color:var(--stone4);font-weight:500;font-size:.78rem;width:90px">Place</th>
          <th style="text-align:left;padding:.75rem 1rem;color:var(--stone4);font-weight:500;font-size:.78rem">Nom</th>
          <th style="text-align:left;padding:.75rem 1rem;color:var(--stone4);font-weight:500;font-size:.78rem">Catégorie</th>
          <th style="width:90px"></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($compResultats as $r): ?>
        <?php if ($editResultatId === intval($r['id']) && $editResultat): ?>
          <!-- Ligne d'édition inline -->
          <tr style="background:rgba(255,215,0,.04);border-bottom:1px solid rgba(255,255,255,.06)">
            <td colspan="4" style="padding:.75rem 1rem">
              <form method="POST" style="display:flex;flex-wrap:wrap;align-items:center;gap:.75rem">
                <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                <input type="hidden" name="action" value="edit_resultat">
                <input type="hidden" name="resultat_id" value="<?= $r['id'] ?>">
                <input type="hidden" name="competition_id" value="<?= $competitionId ?>">
                <input type="number" name="place" min="1" value="<?= h($r['place'] ?? '') ?>" placeholder="Place" style="width:80px" class="admin-input">
                <input type="text" name="nom" required value="<?= h($r['nom']) ?>" placeholder="Nom" style="flex:1;min-width:130px" class="admin-input">
                <input type="text" name="categorie" value="<?= h($r['categorie']) ?>" placeholder="Catégorie" style="flex:1;min-width:130px" class="admin-input">
                <button type="submit" class="btn-admin-save" style="padding:.45rem .9rem">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
                <a href="?tab=resultats&competition_id=<?= $competitionId ?>" class="btn-admin-cancel" style="padding:.45rem .9rem">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </a>
              </form>
            </td>
          </tr>
        <?php else: ?>
          <tr style="border-bottom:1px solid rgba(255,255,255,.05)">
            <td style="padding:.7rem 1rem;color:var(--gold);font-weight:700">
              <?php
              $p = $r['place'];
              if ($p === null || $p === '') echo '—';
              elseif ($p == 1) echo '🥇 1er';
              elseif ($p == 2) echo '🥈 2e';
              elseif ($p == 3) echo '🥉 3e';
              else echo h($p) . 'e';
              ?>
            </td>
            <td style="padding:.7rem 1rem;color:#fff"><?= h($r['nom']) ?></td>
            <td style="padding:.7rem 1rem;color:var(--stone4)"><?= h($r['categorie']) ?: '—' ?></td>
            <td style="padding:.7rem 1rem">
              <div style="display:flex;justify-content:flex-end;gap:.4rem">
                <a href="?tab=resultats&competition_id=<?= $competitionId ?>&edit_resultat=<?= $r['id'] ?>" class="btn-edit">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </a>
                <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cet archer ?')">
                  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
                  <input type="hidden" name="action" value="delete_resultat">
                  <input type="hidden" name="resultat_id" value="<?= $r['id'] ?>">
                  <input type="hidden" name="competition_id" value="<?= $competitionId ?>">
                  <button type="submit" class="btn-delete">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<?php
// ════════════════════════════════════════════════════════════════════════════
// FORMULAIRE ÉDITION COMPÉTITION (depuis la liste ou le détail)
// ════════════════════════════════════════════════════════════════════════════
elseif ($editComp):
?>

<div style="margin-bottom:1.25rem">
  <a href="?tab=resultats" style="display:inline-flex;align-items:center;gap:.4rem;color:var(--stone4);font-size:.82rem;transition:color .2s" onmouseover="this.style.color='var(--stone2)'" onmouseout="this.style.color='var(--stone4)'">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    Retour
  </a>
</div>

<form method="POST" class="admin-card-form">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="edit_comp">
  <input type="hidden" name="comp_id" value="<?= $editComp['id'] ?>">

  <div class="admin-row">
    <div class="admin-form-group" style="flex:2">
      <label class="admin-label">Titre de la compétition</label>
      <input type="text" name="titre" required value="<?= h($editComp['titre']) ?>" placeholder="ex : Championnat départemental salle" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Date</label>
      <input type="date" name="date" required value="<?= h($editComp['date']) ?>" class="admin-input">
    </div>
  </div>
  <div class="admin-row">
    <div class="admin-form-group">
      <label class="admin-label">Type de compétition</label>
      <select name="type_competition" class="admin-select">
        <?php foreach ($COMPETITION_TYPES as $t): ?>
          <option value="<?= h($t) ?>" <?= $editComp['type_competition'] === $t ? 'selected' : '' ?>><?= h($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Lieu</label>
      <input type="text" name="lieu" value="<?= h($editComp['lieu']) ?>" placeholder="ex : Toulouse" class="admin-input">
    </div>
  </div>
  <div class="admin-form-group">
    <label class="admin-label">Lien officiel des résultats (optionnel)</label>
    <input type="url" name="lien_officiel" value="<?= h($editComp['lien_officiel']) ?>" placeholder="https://…" class="admin-input">
  </div>
  <div style="display:flex;gap:.75rem">
    <button type="submit" class="btn-admin-save">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      Enregistrer
    </button>
    <a href="?tab=resultats" class="btn-admin-cancel">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      Annuler
    </a>
  </div>
</form>

<?php
// ════════════════════════════════════════════════════════════════════════════
// LISTE DES COMPÉTITIONS (vue principale)
// ════════════════════════════════════════════════════════════════════════════
else:
?>

<!-- Bouton ajouter -->
<a href="?tab=resultats&add_comp=1" class="btn-admin-add">
  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
  Nouvelle compétition
</a>

<!-- Formulaire ajout compétition -->
<?php if ($addComp): ?>
<form method="POST" class="admin-card-form" style="margin-top:1rem">
  <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
  <input type="hidden" name="action" value="add_comp">
  <div class="admin-row">
    <div class="admin-form-group" style="flex:2">
      <label class="admin-label">Titre de la compétition</label>
      <input type="text" name="titre" required placeholder="ex : Championnat départemental salle" class="admin-input">
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Date</label>
      <input type="date" name="date" required value="<?= date('Y-m-d') ?>" class="admin-input">
    </div>
  </div>
  <div class="admin-row">
    <div class="admin-form-group">
      <label class="admin-label">Type de compétition</label>
      <select name="type_competition" class="admin-select">
        <?php foreach ($COMPETITION_TYPES as $t): ?>
          <option value="<?= h($t) ?>"><?= h($t) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="admin-form-group">
      <label class="admin-label">Lieu</label>
      <input type="text" name="lieu" placeholder="ex : Toulouse" class="admin-input">
    </div>
  </div>
  <div class="admin-form-group">
    <label class="admin-label">Lien officiel des résultats (optionnel)</label>
    <input type="url" name="lien_officiel" placeholder="https://…" class="admin-input">
  </div>
  <div style="display:flex;gap:.75rem">
    <button type="submit" class="btn-admin-save">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      Créer et ajouter les archers
    </button>
    <a href="?tab=resultats" class="btn-admin-cancel">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      Annuler
    </a>
  </div>
</form>
<?php endif; ?>

<!-- Liste des compétitions -->
<?php if (empty($allComps)): ?>
  <p style="color:var(--stone4);text-align:center;padding:3rem 0;font-size:.875rem">Aucune compétition enregistrée.</p>
<?php else: ?>
  <div style="display:flex;flex-direction:column;gap:.75rem;margin-top:1rem">
    <?php foreach ($allComps as $comp): ?>
      <?php
      $cnt = db()->prepare("SELECT COUNT(*) FROM resultats WHERE competition_id=?");
      $cnt->execute([$comp['id']]);
      $nbArchers = intval($cnt->fetchColumn());
      ?>
      <div class="admin-card">
        <div class="admin-card-body" style="display:flex;align-items:flex-start;gap:1rem">
          <div style="flex:1;min-width:0">
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:.375rem">
              <span style="background:var(--white10);color:var(--stone2);font-size:.7rem;font-weight:700;padding:.15rem .6rem;border-radius:9999px"><?= h($comp['type_competition']) ?></span>
              <span style="color:var(--stone5);font-size:.75rem"><?= h(formatDateFR($comp['date'])) ?></span>
              <?php if ($comp['lieu']): ?>
                <span style="color:var(--stone5);font-size:.75rem">· <?= h($comp['lieu']) ?></span>
              <?php endif; ?>
            </div>
            <p style="color:#fff;font-weight:600;font-size:.875rem;margin-bottom:.25rem"><?= h($comp['titre']) ?></p>
            <p style="color:var(--stone5);font-size:.75rem">
              <?= $nbArchers ?> archer<?= $nbArchers > 1 ? 's' : '' ?> enregistré<?= $nbArchers > 1 ? 's' : '' ?>
            </p>
          </div>
          <div style="display:flex;align-items:center;gap:.5rem;flex-shrink:0">
            <a href="?tab=resultats&competition_id=<?= $comp['id'] ?>" title="Gérer les archers"
               style="display:inline-flex;align-items:center;gap:.4rem;padding:.45rem .85rem;border-radius:.6rem;background:rgba(255,215,0,.12);color:var(--gold);font-size:.78rem;font-weight:600;border:1px solid rgba(255,215,0,.2);transition:background .2s"
               onmouseover="this.style.background='rgba(255,215,0,.22)'" onmouseout="this.style.background='rgba(255,215,0,.12)'">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              Archers
            </a>
            <a href="?tab=resultats&edit_comp=<?= $comp['id'] ?>" class="btn-edit">
              <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </a>
            <form method="POST" style="display:inline" onsubmit="return confirm('Supprimer cette compétition et tous ses résultats ?')">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="action" value="delete_comp">
              <input type="hidden" name="comp_id" value="<?= $comp['id'] ?>">
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

<?php endif; ?>
