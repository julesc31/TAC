<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Disciplines — Arc Club Pechbonnieu';
include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Disciplines</h1>
      <p>Découvrez le tir à l'arc sous toutes ses formes — du loisir à la compétition FFTA</p>
    </div>
  </div>

  <div class="container-md pb-20" style="padding-bottom:5rem">

    <!-- Le Tir à l'Arc -->
    <section style="padding:4rem 0 0">
      <h2 style="font-size:1.875rem;font-weight:700;color:var(--gold);text-align:center;margin-bottom:2rem">LE TIR À L'ARC</h2>
      <div class="two-col">
        <div class="text-body" style="display:flex;flex-direction:column;gap:1rem">
          <p>Le Tir à l'Arc était pratiqué à la préhistoire comme arme de chasse, puis, à l'époque des chevaliers, comme arme de guerre. La pratique actuelle est un art martial, qui allie de nombreuses qualités :</p>
          <ul class="bullet-list">
            <li>Anticipation, contrôle de la respiration, de la posture, de la tension musculaire</li>
            <li>Amélioration de l'endurance cardiovasculaire</li>
            <li>Maîtrise de soi et de ses émotions</li>
            <li>Amélioration de la coordination des mouvements</li>
            <li>Amélioration de ses repères dans l'espace</li>
          </ul>
          <p>Il peut être pratiqué toute l'année, que ce soit en salle ou en extérieur.</p>
          <p>Ce sport est un excellent moyen de détente et de relâchement des tensions accumulées dans la journée.</p>
          <p style="color:var(--gold);font-style:italic">Qui n'a jamais rêvé de ressembler à Robin des bois ou autre archer légendaire ?</p>
        </div>
        <img src="https://images.pexels.com/photos/6874498/pexels-photo-6874498.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Archer en action" class="img-rounded" style="height:20rem">
      </div>
    </section>

    <!-- 6 raisons -->
    <section style="margin-top:4rem">
      <div class="reasons-section">
        <h2 style="font-size:1.5rem;font-weight:700;color:var(--gold);text-align:center;margin-bottom:.5rem">SIX BONNES RAISONS DE PRATIQUER</h2>
        <p style="text-align:center;color:var(--stone2);max-width:32rem;margin:0 auto 1rem">Sport créateur de lien social : favorise la convivialité, les échanges entre générations, et peut se pratiquer en famille ou en équipe.</p>
        <div class="reasons-grid">
          <?php
          $reasons = [
            ['1', 'Sport de plein air et de salle', 'Pratique toute l\'année, en ville ou à la campagne, en loisir comme en compétition.'],
            ['2', 'Bon pour le corps', 'Amélioration de la coordination, de l\'équilibre, exercice musculaire harmonieux, amélioration du système cardiovasculaire.'],
            ['3', 'Bon pour l\'esprit', 'Effet anti-stress, canalisation de l\'énergie, amélioration de la concentration, maîtrise de soi.'],
            ['4', 'Pour tous', 'Adapté à tous les âges et toutes les morphologies.'],
            ['5', 'Sport d\'intégration', 'Permet à tous, valides et non valides, de pratiquer ensemble.'],
            ['6', 'Sport éducatif', 'Apprentissage du calcul, gestion des émotions, respect des règles et de l\'environnement.'],
          ];
          foreach ($reasons as [$num, $title, $desc]): ?>
            <div class="reason-card">
              <div class="reason-num"><?= $num ?></div>
              <div class="reason-title"><?= h($title) ?></div>
              <p class="reason-desc"><?= h($desc) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </section>

    <!-- Tir Loisir -->
    <section style="margin-top:4rem">
      <div class="card">
        <div class="two-col">
          <div>
            <h2 class="card-title">Tir Loisir</h2>
            <p class="text-body" style="margin-bottom:1.5rem">Le tir à l'arc loisir est ouvert à tous, débutants comme confirmés. C'est l'occasion de pratiquer dans une ambiance décontractée et conviviale.</p>
            <h3 class="card-subtitle">Horaires</h3>
            <div class="schedule-grid" style="margin-bottom:1.5rem">
              <div class="schedule-card"><div class="schedule-day">Lundi</div><div class="schedule-hours">21h – 23h</div><div class="schedule-public">Adultes</div></div>
              <div class="schedule-card"><div class="schedule-day">Mercredi</div><div class="schedule-hours">16h – 17h30</div><div class="schedule-public">Enfants et ados</div></div>
              <div class="schedule-card"><div class="schedule-day">Samedi</div><div class="schedule-hours">09h – 12h</div><div class="schedule-public">Tous</div></div>
            </div>
            <h3 class="card-subtitle">Équipement nécessaire</h3>
            <p class="text-muted" style="font-size:.875rem;line-height:1.6">Le club met à disposition l'équipement nécessaire pour les débutants. Pour les archers réguliers, nous recommandons l'achat de matériel personnel.</p>
          </div>
          <img src="https://images.pexels.com/photos/6874498/pexels-photo-6874498.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Tir loisir" class="img-rounded" style="height:16rem">
        </div>
      </div>
    </section>

    <!-- Compétition FFTA -->
    <section style="margin-top:2rem">
      <div class="card">
        <div class="two-col" style="direction:rtl">
          <div style="direction:ltr">
            <h2 class="card-title">Compétition FFTA</h2>
            <p class="text-body" style="margin-bottom:1.5rem">Nos archers participent régulièrement aux compétitions organisées par la Fédération Française de Tir à l'Arc (FFTA).</p>
            <h3 class="card-subtitle">Types de compétitions</h3>
            <ul class="bullet-list">
              <li>Tir en salle (18m)</li>
              <li>Tir en extérieur (50m, 70m)</li>
              <li>Tir campagne</li>
              <li>Tir 3D</li>
            </ul>
          </div>
          <img src="https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Compétition FFTA" class="img-rounded" style="height:16rem;direction:ltr">
        </div>
      </div>
    </section>

    <!-- Tir Traditionnel -->
    <section style="margin-top:2rem;padding-bottom:0">
      <div class="card">
        <div class="two-col">
          <div>
            <h2 class="card-title">Tir Traditionnel</h2>
            <p class="text-body" style="margin-bottom:1.5rem">Le tir traditionnel met l'accent sur l'utilisation d'arcs historiques et traditionnels, offrant une expérience unique et enrichissante.</p>
            <h3 class="card-subtitle">Types d'arcs traditionnels</h3>
            <ul class="bullet-list" style="margin-bottom:1.5rem">
              <li>Arc droit (longbow)</li>
              <li>Arc recurve nu</li>
              <li>Arc à poulies nu</li>
            </ul>
            <h3 class="card-subtitle">Événements spécifiques</h3>
            <p class="text-muted" style="font-size:.875rem;line-height:1.6">Nous organisons régulièrement des journées découverte et des compétitions amicales de tir traditionnel.</p>
          </div>
          <img src="https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Tir traditionnel" class="img-rounded" style="height:16rem">
        </div>
      </div>
    </section>

  </div>
</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
