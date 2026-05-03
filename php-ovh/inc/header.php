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
<body>

<?php $currentPage = basename($_SERVER['PHP_SELF'], '.php'); ?>

<header id="site-header" class="site-header <?= ($currentPage === 'index') ? 'header-transparent' : '' ?>">
  <div class="header-inner">
    <a href="/" class="logo" style="flex-direction:row;align-items:center;gap:.75rem">
      <img src="/uploads/photos/logo.png" alt="Logo Arc Club Pechbonnieu" style="height:2.5rem;width:auto">
      <div style="display:flex;flex-direction:column">
        <span class="logo-name">Arc Club Pechbonnieu</span>
        <span class="logo-sub">Section tir à l'arc · Foyer Rural de Pechbonnieu</span>
      </div>
    </a>

    <button class="burger" id="burger" aria-label="Menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

    <nav class="main-nav" id="main-nav">
      <?php
      $nav = [
        'index'       => 'Accueil',
        'club'        => 'Le Club',
        'disciplines' => 'Disciplines',
        'traditions'  => 'Traditions',
        'calendrier'  => 'Calendrier',
        'resultats'   => 'Résultats',
        'album-photo' => 'Album photo',
        'rejoindre'   => 'Renseignements',
      ];
      foreach ($nav as $file => $label):
        $active = ($currentPage === $file) ? ' active' : '';
        $href = ($file === 'index') ? '/' : '/' . $file . '.php';
      ?>
        <a href="<?= $href ?>" class="nav-link<?= $active ?>"><?= h($label) ?></a>
      <?php endforeach; ?>
    </nav>
  </div>
</header>
