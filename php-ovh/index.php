<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Arc Club Pechbonnieu — Tir à l\'Arc · Affilié FFTA';
$pageDesc  = 'Club de tir à l\'arc de Pechbonnieu, affilié à la Fédération Française de Tir à l\'Arc (FFTA). Ouvert à tous depuis 1617.';

// Dernières actualités
$news = [];
try {
    $stmt = db()->query("SELECT * FROM news ORDER BY published_at DESC LIMIT 6");
    $news = $stmt->fetchAll();
} catch (Exception $e) {}

$tagClass = [
    'Entraînement' => 'tag-entrainement',
    'Compétition'  => 'tag-competition',
    'Annonce'      => 'tag-annonce',
    'Club'         => 'tag-club',
];

include __DIR__ . '/inc/header.php';
?>
<body data-page="home">

<!-- Hero -->
<section class="hero">
  <div class="hero-bg" style="background-image:url('https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="hero-badge">
      <span class="hero-badge-dot"></span>
      Club affilié FFTA · Pechbonnieu
    </div>
    <h1>Arc Club<br><span>Pechbonnieu</span></h1>
    <p>Actualités, événements et vie du club — tout ce qui se passe chez nous.</p>
    <div class="hero-btns">
      <a href="/rejoindre.php" class="btn btn-primary">
        Nous rejoindre
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="/contact.php" class="btn btn-outline">Nous contacter</a>
    </div>
  </div>
  <div class="hero-scroll">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
  </div>
</section>

<!-- Actualités -->
<section class="news-section">
  <div class="container-sm">
    <div class="section-header">
      <h2 class="section-title">Actualités <span>du club</span></h2>
      <a href="/calendrier.php" class="btn btn-sm" style="color:var(--gold);display:inline-flex;align-items:center;gap:.25rem;font-size:.875rem;font-weight:500">
        Voir le calendrier
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>

    <?php if (empty($news)): ?>
      <p style="color:var(--stone4);text-align:center;padding:3rem 0">Aucune actualité pour le moment.</p>
    <?php else: ?>
      <div class="news-list">
        <?php foreach ($news as $item): ?>
          <?php $cls = $tagClass[$item['tag']] ?? 'tag-default'; ?>
          <article class="news-card">
            <div class="news-icon">
              <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <div>
              <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:.5rem">
                <span class="news-tag <?= $cls ?>"><?= h($item['tag']) ?></span>
                <span class="news-date"><?= formatDateFR($item['published_at']) ?></span>
              </div>
              <h3 class="news-title"><?= h($item['title']) ?></h3>
              <p class="news-body"><?= h($item['body']) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<!-- CTA -->
<section class="cta-banner">
  <div class="cta-banner-bg" style="background-image:url('https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
  <div class="cta-overlay"></div>
  <div class="cta-content">
    <h2>Prêt à décocher votre première flèche ?</h2>
    <p>Contactez-nous pour une séance découverte ou inscrivez-vous dès maintenant. Tout le matériel est mis à disposition pour les débutants.</p>
    <div class="hero-btns">
      <a href="/rejoindre.php" class="btn btn-primary">
        Nous rejoindre
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
      <a href="/contact.php" class="btn btn-outline">Nous contacter</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/inc/footer.php'; ?>
