<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= h($pageTitle ?? SITE_NAME) ?></title>
  <meta name="description" content="<?= h($pageDesc ?? 'Arc Club Pechbonnieu — Club de tir à l\'arc affilié FFTA depuis 1617.') ?>">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body<?= (basename($_SERVER['PHP_SELF'], '.php') === 'index') ? ' data-page="home"' : '' ?>>

<?php $currentPage = basename($_SERVER['PHP_SELF'], '.php'); ?>

<header id="site-header" class="site-header <?= ($currentPage === 'index') ? 'header-transparent' : '' ?>">
  <div class="header-inner">
    <a href="/" class="logo">
      <span class="logo-name">Arc Club Pechbonnieu</span>
      <span class="logo-sub">Section tir à l'arc · Foyer Rural de Pechbonnieu</span>
    </a>

    <button class="burger" id="burger" aria-label="Menu" aria-expanded="false">
      <svg id="burger-icon-menu" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      <svg id="burger-icon-close" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <nav class="main-nav" id="main-nav">
      <?php
      $nav = [
        'index'       => 'Accueil',
        'club'        => 'Le Club',
        'disciplines' => 'Disciplines',
        'traditions'  => 'Traditions',
        'album-photo' => 'Album photo',
        'calendrier'  => 'Calendrier',
        'rejoindre'   => 'Renseignements',
      ];
      foreach ($nav as $file => $label):
        $isActive = ($currentPage === $file) || ($currentPage === 'contact' && $file === 'rejoindre');
        $active = $isActive ? ' active' : '';
        $href = ($file === 'index') ? '/' : '/' . $file . '.php';
      ?>
        <a href="<?= $href ?>" class="nav-link<?= $active ?>"><?= h($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>
