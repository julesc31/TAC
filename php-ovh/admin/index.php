<?php
require_once __DIR__ . '/../inc/config.php';
require_once __DIR__ . '/../inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();
requireAdmin();

$tab = $_GET['tab'] ?? 'news';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Administration — Arc Club Pechbonnieu</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="admin-body">

<div class="admin-wrap">
  <div class="admin-header-bar">
    <div>
      <h1 class="admin-title">Administration</h1>
      <p class="admin-subtitle">Arc Club Pechbonnieu</p>
    </div>
    <a href="/admin/logout.php" style="display:inline-flex;align-items:center;gap:.5rem;background:var(--white10);border:1px solid var(--white10);color:var(--stone2);padding:.5rem 1rem;border-radius:.75rem;font-size:.875rem;transition:background .2s" onmouseover="this.style.background='var(--white20)'" onmouseout="this.style.background='var(--white10)'">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Déconnexion
    </a>
  </div>

  <div class="admin-tabs">
    <a href="?tab=news" class="admin-tab <?= $tab === 'news' ? 'active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6z"/></svg>
      Actualités
    </a>
    <a href="?tab=photos" class="admin-tab <?= $tab === 'photos' ? 'active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
      Photos
    </a>
    <a href="?tab=preinscriptions" class="admin-tab <?= $tab === 'preinscriptions' ? 'active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Préinscriptions
    </a>
    <a href="?tab=contacts" class="admin-tab <?= $tab === 'contacts' ? 'active' : '' ?>">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Messages
    </a>
  </div>

  <?php if ($tab === 'news'): ?>
    <?php include __DIR__ . '/partials/news.php'; ?>
  <?php elseif ($tab === 'photos'): ?>
    <?php include __DIR__ . '/partials/photos.php'; ?>
  <?php elseif ($tab === 'preinscriptions'): ?>
    <?php include __DIR__ . '/partials/preinscriptions.php'; ?>
  <?php elseif ($tab === 'contacts'): ?>
    <?php include __DIR__ . '/partials/contacts.php'; ?>
  <?php endif; ?>

</div>

<footer style="padding:1rem;text-align:center;border-top:1px solid var(--white10);margin-top:2rem">
  <a href="/" style="color:var(--stone5);font-size:.75rem;transition:color .2s" onmouseover="this.style.color='var(--stone4)'" onmouseout="this.style.color='var(--stone5)'">← Retour au site public</a>
</footer>

<script src="/assets/js/main.js"></script>
</body>
</html>
