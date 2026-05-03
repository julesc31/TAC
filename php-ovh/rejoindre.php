<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Rejoindre & Contact — Arc Club Pechbonnieu';
$activeTab = ($_GET['tab'] ?? 'rejoindre') === 'contact' ? 'contact' : 'rejoindre';

$submittedPreinscription = false;
$submittedContact        = false;
$formErrors              = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $action = $_POST['form'] ?? '';

    if ($action === 'preinscription') {
        $nom        = trim($_POST['nom'] ?? '');
        $email      = trim($_POST['email'] ?? '');
        $categorie  = trim($_POST['categorie'] ?? '');
        $age        = trim($_POST['age'] ?? '');
        $telephone  = trim($_POST['telephone'] ?? '');
        $typeArc    = trim($_POST['typeArc'] ?? '');
        $niveau     = trim($_POST['niveau'] ?? '');
        $commentaires = trim($_POST['commentaires'] ?? '');

        if (!$nom)   $formErrors[] = 'Le nom est requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $formErrors[] = 'L\'email est invalide.';
        if (!$categorie) $formErrors[] = 'La catégorie est requise.';

        if (empty($formErrors)) {
            try {
                db()->prepare("INSERT INTO preinscriptions (nom, email, categorie, age, telephone, type_arc, niveau, commentaires, created_at) VALUES (?,?,?,?,?,?,?,?,NOW())")
                   ->execute([$nom, $email, $categorie, $age ?: null, $telephone ?: null, $typeArc ?: null, $niveau ?: null, $commentaires ?: null]);
                sendNotification(
                    '[Arc Club Pechbonnieu] Nouvelle préinscription',
                    "Nom : $nom\nEmail : $email\nCatégorie : $categorie\nÂge : $age\nTéléphone : $telephone\nType d'arc : $typeArc\nNiveau : $niveau\n\nCommentaires :\n$commentaires"
                );
                $submittedPreinscription = true;
            } catch (Exception $e) { $formErrors[] = 'Erreur lors de l\'enregistrement. Veuillez réessayer.'; }
        }
    }

    if ($action === 'contact') {
        $activeTab = 'contact';
        $nom     = trim($_POST['nom'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $sujet   = trim($_POST['sujet'] ?? 'information');
        $message = trim($_POST['message'] ?? '');

        if (!$nom)     $formErrors[] = 'Le nom est requis.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $formErrors[] = 'L\'email est invalide.';
        if (!$message) $formErrors[] = 'Le message est requis.';

        if (empty($formErrors)) {
            try {
                db()->prepare("INSERT INTO contacts (nom, email, sujet, message, created_at) VALUES (?,?,?,?,NOW())")
                   ->execute([$nom, $email, $sujet, $message]);
                sendNotification(
                    '[Arc Club Pechbonnieu] Nouveau message de contact',
                    "Nom : $nom\nEmail : $email\nSujet : $sujet\n\nMessage :\n$message"
                );
                $submittedContact = true;
            } catch (Exception $e) { $formErrors[] = 'Erreur lors de l\'envoi. Veuillez réessayer.'; }
        }
    }
}

include __DIR__ . '/inc/header.php';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<main class="pt-16">
  <!-- Hero -->
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/8107224/pexels-photo-8107224.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Rejoindre & Contact</h1>
      <p>Inscrivez-vous au club ou prenez contact avec nous — nous sommes là pour vous accueillir.</p>
    </div>
  </div>

  <div class="container-lg" style="padding-top:2rem;padding-bottom:5rem">

    <!-- Onglets -->
    <div style="display:flex;gap:.5rem;border-bottom:1px solid rgba(255,255,255,.1);margin-bottom:2rem">
      <a href="?tab=rejoindre" class="tab-btn <?= $activeTab === 'rejoindre' ? 'active' : '' ?>">Rejoindre le club</a>
      <a href="?tab=contact"   class="tab-btn <?= $activeTab === 'contact'   ? 'active' : '' ?>">Nous contacter</a>
    </div>

    <?php if ($activeTab === 'rejoindre'): ?>
    <!-- ── TAB REJOINDRE ─────────────────────────────────────────────── -->
    <div style="display:flex;flex-direction:column;gap:2rem">

      <!-- Horaires -->
      <section class="card">
        <h2 class="card-title">Horaires des séances</h2>
        <div class="schedule-grid">
          <div class="schedule-card"><div class="schedule-day">Lundi</div><div class="schedule-hours">21h – 23h</div><div class="schedule-public">Adultes</div><div class="schedule-note">Salle ou extérieur selon saison</div></div>
          <div class="schedule-card"><div class="schedule-day">Mercredi</div><div class="schedule-hours">16h – 17h30</div><div class="schedule-public">Enfants et ados</div><div class="schedule-note">Encadrement jeunesse</div></div>
          <div class="schedule-card"><div class="schedule-day">Samedi</div><div class="schedule-hours">09h – 12h</div><div class="schedule-public">Tous</div><div class="schedule-note">Séance ouverte à tous les niveaux</div></div>
        </div>
      </section>

      <!-- Infos pratiques -->
      <section class="card">
        <h2 class="card-title">Informations pratiques</h2>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem">
          <?php foreach ([
            'Le club prête le matériel pour les débutants',
            'Pratique loisir possible sans licence FFTA — cotisation club uniquement',
            "Séances d'initiation disponibles avant inscription",
            'Licence FFTA optionnelle pour participer aux compétitions officielles',
            'Encadrement par des moniteurs diplômés FFTA',
            "Pratique possible dès l'âge de 7 ans",
          ] as $item): ?>
            <div style="display:flex;align-items:flex-start;gap:.75rem;padding:.75rem;background:rgba(255,255,255,.05);border-radius:.75rem">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3cb371" stroke-width="2.5" style="flex-shrink:0;margin-top:.1rem"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="color:var(--stone2);font-size:.875rem"><?= h($item) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Étapes -->
      <section class="card">
        <h2 class="card-title">Comment s'inscrire ?</h2>
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem">
          <?php foreach ([
            ['1', "Préinscription", "Remplissez le formulaire ci-dessous pour manifester votre intérêt."],
            ['2', "Séance découverte", "Venez essayer gratuitement lors d'une de nos séances d'initiation."],
            ['3', "Dossier d'inscription", "Remplissez le dossier d'adhésion et réglez la cotisation."],
            ['4', "Licence FFTA (optionnelle)", "Pour les compétitions officielles, finalisez votre licence sur le site de la FFTA."],
          ] as [$step, $title, $desc]): ?>
            <div style="background:rgba(26,74,124,.5);border:1px solid rgba(255,255,255,.2);border-radius:.75rem;padding:1.25rem;text-align:center">
              <div style="width:2.5rem;height:2.5rem;background:var(--gold);color:#0a2744;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:1.1rem;font-weight:700;margin:0 auto 1rem"><?= $step ?></div>
              <h3 style="color:#fff;font-weight:600;font-size:.875rem;margin-bottom:.5rem"><?= h($title) ?></h3>
              <p style="color:var(--stone4);font-size:.75rem;line-height:1.5"><?= h($desc) ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Formulaire préinscription -->
      <section class="card">
        <h2 class="card-title">Formulaire de préinscription</h2>
        <p style="color:var(--stone3);font-size:.875rem;margin-bottom:2rem">Remplissez ce formulaire pour manifester votre intérêt. Nous vous contacterons pour vous donner la suite.</p>

        <?php if ($submittedPreinscription): ?>
          <div style="text-align:center;padding:3rem 0">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3cb371" stroke-width="1.5" style="margin:0 auto 1rem;display:block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <h3 style="color:#fff;font-size:1.25rem;font-weight:700;margin-bottom:.5rem">Demande envoyée !</h3>
            <p style="color:var(--stone2);font-size:.9rem">Nous avons bien reçu votre demande. Nous vous contacterons sous 48h.</p>
          </div>
        <?php else: ?>
          <?php if (!empty($formErrors) && ($_POST['form'] ?? '') === 'preinscription'): ?>
            <div class="alert alert-error" style="margin-bottom:1.5rem">
              <?= implode('<br>', array_map('h', $formErrors)) ?>
            </div>
          <?php endif; ?>
          <form method="POST" style="max-width:42rem">
            <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
            <input type="hidden" name="form" value="preinscription">
            <div class="form-group">
              <label class="form-label">Catégorie *</label>
              <select name="categorie" required class="form-select">
                <option value="">Sélectionner...</option>
                <option value="jeune" <?= ($_POST['categorie'] ?? '') === 'jeune' ? 'selected' : '' ?>>Jeune (moins de 18 ans)</option>
                <option value="adulte" <?= ($_POST['categorie'] ?? '') === 'adulte' ? 'selected' : '' ?>>Adulte</option>
                <option value="senior" <?= ($_POST['categorie'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior</option>
              </select>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
              <div class="form-group">
                <label class="form-label">Nom complet *</label>
                <input type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>" placeholder="Votre nom" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">Âge</label>
                <input type="number" name="age" value="<?= h($_POST['age'] ?? '') ?>" placeholder="Votre âge" class="form-input">
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
              <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>" placeholder="votre@email.com" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="telephone" value="<?= h($_POST['telephone'] ?? '') ?>" placeholder="06 xx xx xx xx" class="form-input">
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
              <div class="form-group">
                <label class="form-label">Type d'arc souhaité</label>
                <select name="typeArc" class="form-select">
                  <option value="">Sélectionner...</option>
                  <option value="recurve">Arc recurve (classique)</option>
                  <option value="compound">Arc à poulies (compound)</option>
                  <option value="traditionnel">Arc traditionnel / longbow</option>
                  <option value="pas-de-preference">Pas de préférence</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Niveau actuel</label>
                <select name="niveau" class="form-select">
                  <option value="">Sélectionner...</option>
                  <option value="debutant">Débutant (jamais pratiqué)</option>
                  <option value="initie">Initié (quelques séances)</option>
                  <option value="intermediaire">Intermédiaire (1–3 ans)</option>
                  <option value="confirme">Confirmé (3+ ans)</option>
                  <option value="competition">Compétiteur</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="form-label">Commentaires (optionnel)</label>
              <textarea name="commentaires" rows="3" placeholder="Questions, informations complémentaires..." class="form-textarea"><?= h($_POST['commentaires'] ?? '') ?></textarea>
            </div>
            <p style="font-size:.75rem;color:var(--stone4);margin-bottom:1.25rem">En soumettant ce formulaire, vous acceptez que vos données soient utilisées pour traiter votre demande. Voir notre <a href="/politique-confidentialite.php" style="color:var(--gold)">politique de confidentialité</a>.</p>
            <button type="submit" class="btn btn-primary">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              Envoyer ma préinscription
            </button>
          </form>
        <?php endif; ?>
      </section>
    </div>

    <?php else: ?>
    <!-- ── TAB CONTACT ──────────────────────────────────────────────────── -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.5rem">

      <!-- Colonne gauche -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <section class="card">
          <h2 class="card-title">Nos Coordonnées</h2>
          <ul class="contact-info-list">
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <div><div class="contact-info-title">Tir extérieur</div><div class="contact-info-val">47 chemin de Labastidole<br>31140 Pechbonnieu<br><span style="color:var(--stone5)">(Parking du cimetière)</span></div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <div><div class="contact-info-title">Tir en salle</div><div class="contact-info-val">Gymnase Colette Besson<br>31140 Pechbonnieu<br><span style="color:var(--stone5)">(À côté du collège)</span></div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.95a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <div><div class="contact-info-title">Téléphone</div><div class="contact-info-val">05 61 09 73 32<br><span style="color:var(--stone5)">(Foyer rural ESCALE de Pechbonnieu)</span></div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <div><div class="contact-info-title">Email</div><a href="mailto:pechbonnieu.tiralarc@gmail.com" style="color:var(--gold)">pechbonnieu.tiralarc@gmail.com</a></div>
            </li>
          </ul>
        </section>

        <section class="card">
          <h2 class="card-title" style="display:flex;align-items:center;gap:.5rem">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Horaires d'ouverture
          </h2>
          <ul style="list-style:none;display:flex;flex-direction:column">
            <?php foreach ([['Lundi','21h – 23h','Adultes'],['Mercredi','16h – 17h30','Enfants et ados'],['Samedi','09h – 12h','Tous']] as [$d,$h,$p]): ?>
              <li style="display:grid;grid-template-columns:1fr 1fr 1fr;align-items:center;padding:.625rem 0;border-bottom:1px solid var(--white10)">
                <span style="color:#fff;font-weight:500;font-size:.875rem"><?= $d ?></span>
                <span style="color:var(--gold);font-weight:600;font-size:.875rem;text-align:center"><?= $h ?></span>
                <span style="color:var(--stone4);font-size:.75rem;text-align:right"><?= $p ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </section>

        <section class="card" style="padding:0;overflow:hidden">
          <div style="padding:1rem;border-bottom:1px solid var(--white10)">
            <h2 style="color:var(--gold);font-weight:700;font-size:1.1rem">Plan d'accès</h2>
          </div>
          <div id="map" style="height:280px"></div>
          <div style="padding:.75rem 1rem;border-top:1px solid var(--white10);display:flex;flex-direction:column;gap:.5rem">
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#ef4444;flex-shrink:0"></span>Terrain de tir extérieur</span>
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#3b82f6;flex-shrink:0"></span>Gymnase Colette Besson</span>
          </div>
        </section>
      </div>

      <!-- Colonne droite -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <section class="card">
          <h2 class="card-title">Formulaire de contact</h2>

          <?php if ($submittedContact): ?>
            <div style="text-align:center;padding:2.5rem 0">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5" style="margin:0 auto 1rem;display:block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <h3 style="color:#fff;font-size:1.25rem;font-weight:700;margin-bottom:.5rem">Message envoyé !</h3>
              <p style="color:var(--stone2);font-size:.9rem">Nous vous répondrons dans les meilleurs délais.</p>
            </div>
          <?php else: ?>
            <?php if (!empty($formErrors) && ($_POST['form'] ?? '') === 'contact'): ?>
              <div class="alert alert-error" style="margin-bottom:1.25rem">
                <?= implode('<br>', array_map('h', $formErrors)) ?>
              </div>
            <?php endif; ?>
            <form method="POST">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
              <input type="hidden" name="form" value="contact">
              <div class="form-group">
                <label class="form-label">Nom *</label>
                <input type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>" placeholder="Votre nom" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>" placeholder="votre@email.com" class="form-input">
              </div>
              <div class="form-group">
                <label class="form-label">Sujet</label>
                <select name="sujet" class="form-select">
                  <option value="information">Demande d'information</option>
                  <option value="inscription">Inscription</option>
                  <option value="autre">Autre</option>
                </select>
              </div>
              <div class="form-group">
                <label class="form-label">Message *</label>
                <textarea name="message" required rows="5" placeholder="Votre message..." class="form-textarea"><?= h($_POST['message'] ?? '') ?></textarea>
              </div>
              <p style="font-size:.75rem;color:var(--stone4);margin-bottom:1.25rem">Vos données sont utilisées uniquement pour traiter votre demande.</p>
              <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Envoyer
              </button>
            </form>
          <?php endif; ?>
        </section>

        <section class="card">
          <h2 class="card-title">Suivez-nous</h2>
          <p class="text-muted" style="font-size:.875rem;margin-bottom:1rem">Restez connectés pour nos dernières actualités !</p>
          <div style="display:flex;gap:.75rem">
            <a href="https://www.facebook.com/" target="_blank" rel="noopener noreferrer"
               style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(24,119,242,.2);border:1px solid rgba(24,119,242,.4);color:#60a5fa;padding:.625rem 1rem;border-radius:.75rem;font-size:.875rem;font-weight:500;transition:all .2s"
               onmouseover="this.style.background='#1877f2';this.style.color='#fff'" onmouseout="this.style.background='rgba(24,119,242,.2)';this.style.color='#60a5fa'">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
              Facebook
            </a>
            <a href="https://www.instagram.com/" target="_blank" rel="noopener noreferrer"
               style="display:inline-flex;align-items:center;gap:.5rem;background:rgba(225,48,108,.2);border:1px solid rgba(225,48,108,.4);color:#f472b6;padding:.625rem 1rem;border-radius:.75rem;font-size:.875rem;font-weight:500;transition:all .2s"
               onmouseover="this.style.background='#e1306c';this.style.color='#fff'" onmouseout="this.style.background='rgba(225,48,108,.2)';this.style.color='#f472b6'">
              <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
              Instagram
            </a>
          </div>
        </section>
      </div>
    </div>
    <?php endif; ?>
  </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<?php if ($activeTab === 'contact'): ?>
<script>
(function(){
  const map = L.map('map').setView([43.70684, 1.459539], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
  const makeIcon = c => L.icon({iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-'+c+'.png',shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',iconSize:[25,41],iconAnchor:[12,41],popupAnchor:[1,-34],shadowSize:[41,41]});
  L.marker([43.707341,1.458391],{icon:makeIcon('red')}).addTo(map).bindPopup('Terrain pour le tir en extérieur (parking du cimetière)');
  L.marker([43.706075,1.462986],{icon:makeIcon('blue')}).addTo(map).bindPopup('Gymnase Colette Besson (tir en salle)');
})();
</script>
<?php endif; ?>
<style>
@media(max-width:768px){
  .container-lg > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}
  .card > div[style*="grid-template-columns:repeat(4"]{grid-template-columns:1fr 1fr!important}
  .card > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}
}
</style>

<?php include __DIR__ . '/inc/footer.php'; ?>
