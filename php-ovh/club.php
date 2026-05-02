<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Le Club — Arc Club Pechbonnieu';
include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <!-- Hero -->
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Le Club</h1>
      <p>Arc Club Pechbonnieu — une passion partagée</p>
    </div>
  </div>

  <div class="container-md pb-20 space-y-8" style="padding-bottom:5rem">

    <!-- Histoire -->
    <section class="card">
      <h2 class="card-title">Histoire du Club</h2>
      <p class="text-body" style="margin-bottom:.75rem">
        L'Arc Club Pechbonnieu est né de la passion de quelques archers désireux de partager leur amour pour
        le tir à l'arc. Au fil des années, le club s'est développé et structuré, accueillant toujours plus de
        membres et s'impliquant activement dans les compétitions régionales et nationales.
      </p>
      <p class="text-body">
        Ancré dans les traditions du tir à l'arc tout en restant ouvert aux évolutions du sport, le club
        cultive un esprit de transmission et de partage entre générations d'archers.
      </p>
    </section>

    <!-- Bureau -->
    <section class="card">
      <h2 class="card-title">Le Bureau</h2>
      <p class="text-body" style="margin-bottom:1.5rem">
        Le club est animé par un bureau bénévole investi, qui assure au quotidien la bonne marche de
        l'association : organisation des séances, gestion des licences, préparation des compétitions
        et accueil des nouveaux membres. Leur engagement est le moteur du club.
      </p>
      <div style="display:flex;flex-wrap:wrap;gap:.625rem">
        <?php
        $bureau = [
          ['name' => 'Benoît', 'role' => 'Président'],
          ['name' => 'Marc',    'role' => null],
          ['name' => 'Michel',  'role' => null],
          ['name' => 'Julien',  'role' => null],
          ['name' => 'Erwann',  'role' => null],
          ['name' => 'Cécile',  'role' => null],
          ['name' => 'Paul',    'role' => null],
          ['name' => 'Audré',   'role' => null],
          ['name' => 'Arnaud',  'role' => null],
        ];
        foreach ($bureau as $m): ?>
          <div style="display:inline-flex;align-items:center;gap:.5rem;background:var(--white10);border:1px solid var(--white20);border-radius:9999px;padding:.375rem 1rem">
            <div style="width:1.75rem;height:1.75rem;border-radius:50%;background:var(--navy2);display:flex;align-items:center;justify-content:center;color:var(--gold);font-weight:700;font-size:.75rem;flex-shrink:0">
              <?= mb_substr($m['name'], 0, 1) ?>
            </div>
            <span style="color:#fff;font-weight:500;font-size:.875rem"><?= h($m['name']) ?></span>
            <?php if ($m['role']): ?>
              <span style="color:var(--green);font-size:.7rem;font-weight:700"><?= h($m['role']) ?></span>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Installations -->
    <section class="card">
      <h2 class="card-title">Nos Installations</h2>
      <div class="two-col">
        <div>
          <p class="text-body" style="margin-bottom:1rem">L'Arc Club Pechbonnieu dispose d'installations adaptées à la pratique du tir à l'arc :</p>
          <ul class="bullet-list">
            <li>Un pas de tir extérieur de onze cibles (10 à 70m) situé au 47 chemin de Labastidole à Pechbonnieu</li>
            <li>Un jeu d'arc traditionnel pour la pratique du Beursault</li>
            <li>Un accès au gymnase de la communauté de communes des Coteaux de Bellevue avec 8 cibles mobiles</li>
          </ul>
        </div>
        <img src="https://images.pexels.com/photos/8107224/pexels-photo-8107224.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Installations du club" class="img-rounded" style="height:16rem">
      </div>
    </section>

    <!-- Valeurs -->
    <section class="card">
      <h2 class="card-title">Nos Valeurs</h2>
      <p class="text-body" style="margin-bottom:1rem">À l'Arc Club Pechbonnieu, nous croyons en :</p>
      <ul class="bullet-list">
        <li>L'esprit sportif et le fair-play</li>
        <li>Le respect de la tradition et l'ouverture à l'innovation</li>
        <li>L'inclusion et l'accueil de tous, quel que soit le niveau</li>
      </ul>
    </section>

  </div>
</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
