<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Traditions des Archers — Arc Club Pechbonnieu';
include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Traditions des Archers</h1>
      <p>Le tir à l'arc est plus qu'un simple sport. Une discipline ancienne riche en valeurs et en histoire.</p>
    </div>
  </div>

  <div class="container-sm pb-20 space-y-8" style="padding-bottom:5rem;padding-top:2rem">

    <section class="card">
      <div class="two-col">
        <div>
          <h2 class="card-title">Plus qu'un Sport : Une Pratique Riche en Traditions</h2>
          <p class="text-body" style="margin-bottom:1rem">Dans le tir à l'arc, chaque geste compte. La façon de tenir l'arc, de viser, et même de récupérer les flèches, s'inscrit dans une longue tradition.</p>
          <h3 class="card-subtitle">Les Valeurs du Tir à l'Arc</h3>
          <p class="text-body" style="margin-bottom:.75rem">La pratique du tir à l'arc met l'accent sur plusieurs qualités importantes :</p>
          <ul class="bullet-list">
            <li>La concentration et la patience</li>
            <li>La maîtrise de soi</li>
            <li>Le respect des autres et du matériel</li>
            <li>La volonté de s'améliorer constamment</li>
          </ul>
          <p class="text-muted" style="font-size:.875rem;margin-top:1rem;line-height:1.6">Ces valeurs, essentielles pour bien tirer, sont également utiles dans la vie quotidienne. Elles font du tir à l'arc non seulement un sport, mais aussi une école de vie.</p>
        </div>
        <img src="/uploads/photos/groupe-archers.jpg" alt="Groupe d'archers" class="img-rounded" style="height:20rem;object-fit:cover">
      </div>
    </section>

    <section class="card">
      <div class="two-col">
        <img src="/uploads/photos/tb08092024-4.jpg" alt="Le Beursault" class="img-rounded" style="height:20rem;object-fit:cover">
        <div>
          <h2 class="card-title">Le Beursault : Une Tradition Ancrée dans l'Histoire Militaire</h2>
          <p class="text-body" style="margin-bottom:1rem">Le Beursault est l'une des formes de tir à l'arc les plus anciennes en France. Son origine remonte au Moyen Âge et est étroitement liée à l'entraînement militaire des archers.</p>
          <h3 class="card-subtitle">L'Origine des Allers-Retours</h3>
          <ul class="bullet-list" style="margin-bottom:1.5rem">
            <li>Ils simulent les conditions de combat où les archers devaient tirer dans les deux sens, en avançant et en reculant sur le champ de bataille.</li>
            <li>Cette pratique permettait aux archers de s'entraîner à tirer avec précision dans différentes directions et positions.</li>
            <li>Les distances de tir (environ 50 mètres) correspondent aux portées efficaces des arcs de l'époque dans les batailles.</li>
          </ul>
          <h3 class="card-subtitle">Évolution vers une Pratique Sportive</h3>
          <ul class="bullet-list">
            <li>Les compagnies d'arc, initialement formées pour la défense des villes, ont maintenu cette pratique même après l'obsolescence militaire de l'arc.</li>
            <li>Le rituel des allers-retours a été préservé, devenant une partie intégrante de la tradition du tir à l'arc français.</li>
            <li>Aujourd'hui, le Beursault est apprécié pour son défi technique et son lien avec l'histoire du tir à l'arc.</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="card">
      <h2 class="card-title">Le Jeu d'Arc : Un Espace Dédié à la Pratique</h2>
      <img src="/uploads/photos/tb08092024-5.jpg" alt="Le Jeu d'Arc" class="img-rounded" style="height:18rem;object-fit:cover;width:100%;margin-bottom:1.5rem">
      <p class="text-body" style="margin-bottom:1.5rem">Le jeu d'arc est une installation spécifique au tir à l'arc traditionnel français. C'est un espace conçu pour la pratique du tir Beursault, mais il sert aussi à d'autres formes de tir.</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem">
        <div>
          <h3 class="card-subtitle">Qu'est-ce qu'un Jeu d'Arc ?</h3>
          <ul style="list-style:none;font-size:.875rem;color:#d6d0c8;display:flex;flex-direction:column;gap:.5rem">
            <li><strong style="color:#fff">Structure :</strong> Terrain rectangulaire, clos par des murs ou des haies.</li>
            <li><strong style="color:#fff">Éléments clés :</strong> Deux buttes de tir aux extrémités, séparées par une allée centrale.</li>
            <li><strong style="color:#fff">Dimensions :</strong> Longueur typique d'environ 50 mètres.</li>
          </ul>
        </div>
        <div>
          <h3 class="card-subtitle">À quoi sert un Jeu d'Arc ?</h3>
          <ul class="bullet-list">
            <li>Entraînement sécurisé</li>
            <li>Compétitions traditionnelles</li>
            <li>Enseignement des techniques</li>
            <li>Vie sociale et camaraderie</li>
          </ul>
        </div>
      </div>
      <div class="highlight-box">
        <p>À l'Arc Club Pechbonnieu, notre jeu d'arc est un atout précieux. Il nous permet de pratiquer le tir traditionnel tout en offrant un cadre agréable pour nos entraînements et nos rencontres amicales.</p>
      </div>
    </section>

    <section class="card">
      <div class="two-col">
        <div>
          <h2 class="card-title">Le Morata : Une Tradition Unique du Sud-Ouest</h2>
          <p class="text-body" style="margin-bottom:1rem">Le Morata, né dans les années 1970, est une tradition de tir à l'arc propre au Sud-Ouest de la France. Il incarne l'esprit de camaraderie et d'inclusivité au sein de la communauté des archers.</p>
          <h3 class="card-subtitle">Objectifs du Morata</h3>
          <ul class="bullet-list" style="margin-bottom:1rem">
            <li>Renforcer les liens entre clubs locaux</li>
            <li>Favoriser l'échange et la progression dans un cadre bienveillant</li>
            <li>Accueillir les archers de tous niveaux, du débutant à l'expert</li>
            <li>Préserver l'esprit traditionnel du tir à l'arc</li>
          </ul>
          <p class="text-muted" style="font-size:.875rem;line-height:1.6;margin-bottom:.75rem">Contrairement aux compétitions classiques, le Morata met l'accent sur le partage d'expérience et l'amélioration personnelle plutôt que sur la performance pure.</p>
          <p class="text-muted" style="font-size:.875rem;line-height:1.6">Participer régulièrement aux Morata, c'est perpétuer cette tradition qui incarne les valeurs fondamentales du tir à l'arc : respect mutuel, entraide et passion partagée.</p>
        </div>
        <img src="/uploads/photos/banner.jpg" alt="Le Morata" class="img-rounded" style="height:20rem;object-fit:cover">
      </div>
    </section>

    <section class="card">
      <h2 class="card-title">Le Salut et les Gestes de Respect</h2>
      <img src="/uploads/photos/salut.jpg" alt="Le Salut des archers" class="img-rounded" style="height:18rem;object-fit:cover;width:100%;margin-bottom:1.5rem">
      <p class="text-body" style="margin-bottom:1.25rem">Dans le tir à l'arc, de nombreux gestes traditionnels sont non seulement des marques de respect, mais aussi des pratiques de sécurité essentielles.</p>
      <h3 class="card-subtitle">Le Salut</h3>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.5rem">
        <?php foreach ([
          ['Salut à la cible', 'Effectué avant et après chaque volée, ce geste marque le respect envers la cible et les autres archers.'],
          ['Salut aux arbitres et officiels', 'Dans les compétitions, ce salut montre le respect envers l\'autorité et les règles du jeu.'],
          ['Salut entre archers', 'Échangé avant et après une compétition ou une séance d\'entraînement, il renforce l\'esprit de camaraderie.'],
        ] as [$title, $desc]): ?>
          <li class="text-body" style="font-size:.875rem"><strong style="color:#fff"><?= h($title) ?> : </strong><?= h($desc) ?></li>
        <?php endforeach; ?>
      </ul>
      <h3 class="card-subtitle">Gestes de Sécurité Ancrés dans la Tradition</h3>
      <ul style="list-style:none;display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.25rem">
        <?php foreach ([
          ['La position d\'attente', 'Tenir l\'arc verticalement, pointe de flèche vers le sol — une mesure de sécurité pour éviter les accidents.'],
          ['Le ramassage cérémoniel des flèches', 'Une par une, avec soin, pour vérifier l\'état de chaque flèche et éviter les blessures.'],
          ['L\'annonce de la fin de tir', 'Signal de sécurité crucial pour les autres archers.'],
        ] as [$title, $desc]): ?>
          <li class="text-body" style="font-size:.875rem"><strong style="color:#fff"><?= h($title) ?> : </strong><?= h($desc) ?></li>
        <?php endforeach; ?>
      </ul>
      <div class="highlight-box">
        <p>À l'Arc Club Pechbonnieu, nous enseignons ces gestes et pratiques comme faisant partie intégrante de l'apprentissage du tir à l'arc.</p>
      </div>
    </section>

  </div>
</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
