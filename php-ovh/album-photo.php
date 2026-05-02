<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Album Photo — Arc Club Pechbonnieu';

$photos = [];
try {
    $photos = db()->query("SELECT * FROM photos ORDER BY position ASC")->fetchAll();
} catch (Exception $e) {}

$categories = ['Tous'];
foreach ($photos as $p) {
    if (!in_array($p['category'], $categories)) $categories[] = $p['category'];
}

include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <!-- Hero -->
  <div style="background:linear-gradient(to bottom,var(--navy),#1a3a5c);padding:5rem 0 4rem;margin-top:0;text-align:center">
    <div class="container-sm">
      <div class="inline-badge">Arc Club Pechbonnieu</div>
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:800;color:#fff;margin-bottom:1rem">Album <span style="color:var(--gold)">Photo</span></h1>
      <p style="color:var(--stone2);font-size:1.05rem;max-width:34rem;margin:0 auto">Revivez les moments forts du club : compétitions, entraînements, sorties et vie associative.</p>
    </div>
  </div>

  <?php if (empty($photos)): ?>
    <div class="container-sm" style="padding:5rem 0;text-align:center">
      <p style="color:var(--stone4)">Aucune photo pour le moment.</p>
    </div>
  <?php else: ?>

    <!-- Filtres -->
    <div class="container-md" style="padding:2rem 1.5rem 1rem">
      <div class="filters">
        <?php foreach ($categories as $cat): ?>
          <button class="filter-btn <?= $cat === 'Tous' ? 'active' : '' ?>" data-cat="<?= h($cat) ?>"><?= h($cat) ?></button>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Grille masonry -->
    <div class="container-md" style="padding-bottom:5rem">
      <div class="photo-masonry">
        <?php foreach ($photos as $photo): ?>
          <div class="photo-item"
               data-category="<?= h($photo['category']) ?>"
               data-url="<?= h($photo['url']) ?>"
               data-caption="<?= h($photo['caption']) ?>">
            <img src="<?= h($photo['url']) ?>" alt="<?= h($photo['caption']) ?>" loading="lazy">
            <div class="photo-overlay">
              <div>
                <span class="photo-cat"><?= h($photo['category']) ?></span>
                <p class="photo-caption"><?= h($photo['caption']) ?></p>
              </div>
              <div style="position:absolute;right:1rem;bottom:1rem;background:rgba(255,255,255,.2);backdrop-filter:blur(4px);border-radius:50%;padding:.5rem;display:flex">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

  <?php endif; ?>

  <!-- Lightbox -->
  <div class="lightbox" id="lightbox">
    <button class="lightbox-close" id="lb-close">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <button class="lightbox-prev" id="lb-prev">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
    </button>
    <div style="max-width:64rem;width:100%;text-align:center">
      <img id="lb-img" src="" alt="" class="lightbox-img">
      <div class="lightbox-info">
        <div class="lightbox-cat" id="lb-cat"></div>
        <p class="lightbox-caption" id="lb-caption"></p>
        <p class="lightbox-counter" id="lb-counter"></p>
      </div>
    </div>
    <button class="lightbox-next" id="lb-next">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
    </button>
  </div>

</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
