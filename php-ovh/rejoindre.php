<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Renseignements — Arc Club Pechbonnieu';
$submitted  = false;
$formErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nom        = trim($_POST['nom']        ?? '');
    $email      = trim($_POST['email']      ?? '');
    $telephone  = trim($_POST['telephone']  ?? '');
    $sujet      = trim($_POST['sujet']      ?? 'information');
    $categorie  = trim($_POST['categorie']  ?? '');
    $age        = trim($_POST['age']        ?? '');
    $typeArc    = trim($_POST['typeArc']    ?? '');
    $niveau     = trim($_POST['niveau']     ?? '');
    $message    = trim($_POST['message']    ?? '');

    if (!$nom)   $formErrors[] = 'Le nom est requis.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $formErrors[] = 'L\'email est invalide.';
    if (!$message) $formErrors[] = 'Le message est requis.';

    if (empty($formErrors)) {
        try {
            if ($sujet === 'preinscription') {
                db()->prepare("INSERT INTO preinscriptions (nom, email, categorie, age, telephone, type_arc, niveau, commentaires, created_at) VALUES (?,?,?,?,?,?,?,?,NOW())")
                   ->execute([$nom, $email, $categorie, $age ?: null, $telephone ?: null, $typeArc ?: null, $niveau ?: null, $message ?: null]);
                sendNotification(
                    '[Arc Club Pechbonnieu] Nouvelle préinscription',
                    "Nom : $nom\nEmail : $email\nTéléphone : $telephone\nCatégorie : $categorie\nÂge : $age\nType d'arc : $typeArc\nNiveau : $niveau\n\nMessage :\n$message"
                );
            } else {
                db()->prepare("INSERT INTO contacts (nom, email, sujet, message, created_at) VALUES (?,?,?,?,NOW())")
                   ->execute([$nom, $email, $sujet, $message]);
                sendNotification(
                    '[Arc Club Pechbonnieu] Nouveau message — ' . $sujet,
                    "Nom : $nom\nEmail : $email\nTéléphone : $telephone\nSujet : $sujet\n\nMessage :\n$message"
                );
            }
            $submitted = true;
        } catch (Exception $e) {
            $formErrors[] = 'Erreur lors de l\'envoi. Veuillez réessayer.';
        }
    }
}

include __DIR__ . '/inc/header.php';
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

<main class="pt-16">
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('/uploads/photos/banner3.jpg')"></div>
    <div class="page-hero-content">
      <h1>Renseignements</h1>
      <p>Vous souhaitez rejoindre le club ou obtenir des informations ? Nous sommes là pour vous répondre.</p>
    </div>
  </div>

  <div class="container-lg" style="padding-top:3rem;padding-bottom:5rem;display:flex;flex-direction:column;gap:2rem">

    <!-- Rangée 1 : Horaires + Tarifs -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">

      <!-- Horaires -->
      <section class="card">
        <h2 class="card-title">Horaires des séances</h2>
        <div class="schedule-grid" style="grid-template-columns:repeat(3,1fr)">
          <div class="schedule-card"><div class="schedule-day">Lundi</div><div class="schedule-hours">21h – 23h</div><div class="schedule-public">Adultes</div></div>
          <div class="schedule-card"><div class="schedule-day">Mercredi</div><div class="schedule-hours">16h – 17h30</div><div class="schedule-public">Enfants & ados</div></div>
          <div class="schedule-card"><div class="schedule-day">Samedi</div><div class="schedule-hours">09h – 12h</div><div class="schedule-public">Tous niveaux</div></div>
        </div>
        <div style="margin-top:1.25rem;display:flex;flex-direction:column;gap:.5rem">
          <?php foreach ([
            'Matériel prêté gratuitement aux débutants',
            'Pratique loisir sans licence FFTA possible',
            'Séances d\'initiation avant inscription',
            'Encadrement par des moniteurs diplômés FFTA',
            'Ouvert dès 7 ans',
          ] as $item): ?>
            <div style="display:flex;align-items:flex-start;gap:.5rem">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#3cb371" stroke-width="2.5" style="flex-shrink:0;margin-top:.2rem"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="color:var(--stone2);font-size:.85rem"><?= h($item) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <!-- Tarifs 2026 -->
      <section class="card">
        <h2 class="card-title">Tarifs 2026</h2>
        <div style="display:flex;flex-direction:column;gap:.75rem;margin-bottom:1.5rem">
          <?php foreach ([
            ['Cotisation annuelle', 'XXX €', 'Inclut la licence tir loisir, la cotisation club et un T-shirt du club'],
            ['Cotisation Foyer Rural ESCALE', 'XX €', 'Participation aux frais de fonctionnement du Foyer Rural de Pechbonnieu'],
            ['Petit matériel de départ', 'XX €', 'Matériel personnel minimum pour débuter la pratique'],
            ['Licence FFTA', 'XX €', 'Optionnelle — obligatoire pour participer aux compétitions officielles'],
          ] as [$label, $price, $desc]): ?>
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;padding:.875rem;background:rgba(255,255,255,.05);border-radius:.75rem;border:1px solid var(--white10)">
              <div>
                <div style="color:#fff;font-weight:600;font-size:.875rem;margin-bottom:.2rem"><?= $label ?></div>
                <div style="color:var(--stone4);font-size:.78rem;line-height:1.4"><?= $desc ?></div>
              </div>
              <div style="color:var(--gold);font-weight:700;font-size:1rem;white-space:nowrap;flex-shrink:0"><?= $price ?></div>
            </div>
          <?php endforeach; ?>
        </div>
        <a href="/reglement-interieur.php" style="display:inline-flex;align-items:center;gap:.5rem;color:var(--gold);font-size:.85rem;font-weight:500">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Consulter le règlement intérieur
        </a>
      </section>
    </div>

    <!-- Rangée 2 : Coordonnées + Carte -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2rem">

      <!-- Coordonnées + étapes + liens -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <section class="card">
          <h2 class="card-title">Nos Coordonnées</h2>
          <ul class="contact-info-list">
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <div><div class="contact-info-title">Tir extérieur</div><div class="contact-info-val">47 chemin de Labastidole, 31140 Pechbonnieu<br><span style="color:var(--stone5)">(Parking du cimetière)</span></div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
              <div><div class="contact-info-title">Tir en salle</div><div class="contact-info-val">Gymnase Colette Besson, 31140 Pechbonnieu<br><span style="color:var(--stone5)">(À côté du collège)</span></div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 1.27h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.95a16 16 0 0 0 6 6l.95-.95a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              <div><div class="contact-info-title">Téléphone</div><div class="contact-info-val">05 61 09 73 32</div></div>
            </li>
            <li>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
              <div><div class="contact-info-title">Email</div><a href="mailto:pechbonnieu.tiralarc@gmail.com" style="color:var(--gold)">pechbonnieu.tiralarc@gmail.com</a></div>
            </li>
          </ul>
        </section>

        <section class="card">
          <h2 class="card-title">Comment s'inscrire ?</h2>
          <div style="display:flex;flex-direction:column;gap:.875rem">
            <?php foreach ([
              ['1', 'Prise de contact', 'Envoyez-nous votre message via le formulaire ci-dessous.'],
              ['2', 'Séance découverte', 'Venez essayer gratuitement lors d\'une de nos séances.'],
              ['3', 'Dossier d\'adhésion', 'Remplissez le dossier et réglez la cotisation.'],
              ['4', 'Licence FFTA', 'Optionnelle, pour participer aux compétitions officielles.'],
            ] as [$n, $t, $d]): ?>
              <div style="display:flex;align-items:flex-start;gap:.875rem">
                <div style="width:2rem;height:2rem;background:var(--gold);color:#0a2744;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0"><?= $n ?></div>
                <div><div style="color:#fff;font-weight:600;font-size:.875rem"><?= $t ?></div><div style="color:var(--stone4);font-size:.8rem;line-height:1.4"><?= $d ?></div></div>
              </div>
            <?php endforeach; ?>
          </div>
        </section>

        <!-- Liens utiles -->
        <section class="card">
          <h2 class="card-title">Liens utiles</h2>
          <div style="display:flex;flex-direction:column;gap:.75rem">
            <a href="https://www.ffta.fr" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:.75rem;padding:.75rem;background:rgba(255,255,255,.05);border:1px solid var(--white10);border-radius:.75rem;transition:border-color .2s"
               onmouseover="this.style.borderColor='rgba(255,215,0,.3)'" onmouseout="this.style.borderColor='var(--white10)'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              <div><div style="color:#fff;font-size:.875rem;font-weight:500">Fédération Française de Tir à l'Arc</div><div style="color:var(--stone4);font-size:.75rem">www.ffta.fr</div></div>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--stone5)" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <a href="https://www.foyer-rural-pechbonnieu.fr" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:.75rem;padding:.75rem;background:rgba(255,255,255,.05);border:1px solid var(--white10);border-radius:.75rem;transition:border-color .2s"
               onmouseover="this.style.borderColor='rgba(255,215,0,.3)'" onmouseout="this.style.borderColor='var(--white10)'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
              <div><div style="color:#fff;font-size:.875rem;font-weight:500">Foyer Rural ESCALE de Pechbonnieu</div><div style="color:var(--stone4);font-size:.75rem">foyer-rural-pechbonnieu.fr</div></div>
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--stone5)" stroke-width="2" style="margin-left:auto;flex-shrink:0"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            </a>
            <a href="/reglement-interieur.php"
               style="display:flex;align-items:center;gap:.75rem;padding:.75rem;background:rgba(255,255,255,.05);border:1px solid var(--white10);border-radius:.75rem;transition:border-color .2s"
               onmouseover="this.style.borderColor='rgba(255,215,0,.3)'" onmouseout="this.style.borderColor='var(--white10)'">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              <div><div style="color:#fff;font-size:.875rem;font-weight:500">Règlement intérieur du club</div><div style="color:var(--stone4);font-size:.75rem">Consulter les règles de fonctionnement</div></div>
            </a>
          </div>
        </section>
      </div>

      <!-- Carte Leaflet + Formulaire -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">
        <section class="card" style="padding:0;overflow:hidden">
          <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--white10)">
            <h2 style="color:var(--gold);font-weight:700;font-size:1.1rem">Plan d'accès</h2>
          </div>
          <div id="map" style="height:340px"></div>
          <div style="padding:.75rem 1.25rem;border-top:1px solid var(--white10);display:flex;flex-direction:column;gap:.5rem">
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#ef4444;flex-shrink:0"></span>Terrain extérieur — 47 chemin de Labastidole</span>
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#3b82f6;flex-shrink:0"></span>Gymnase Colette Besson (tir en salle)</span>
          </div>
        </section>

        <!-- Formulaire -->
        <section class="card">
          <h2 class="card-title">Formulaire de contact & préinscription</h2>
          <p style="color:var(--stone4);font-size:.875rem;margin-bottom:2rem">Demande d'information, préinscription ou autre — un seul formulaire suffit. Nous vous répondrons sous 48h.</p>

          <?php if ($submitted): ?>
            <div style="text-align:center;padding:3rem 0">
              <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#3cb371" stroke-width="1.5" style="margin:0 auto 1rem;display:block"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <h3 style="color:#fff;font-size:1.25rem;font-weight:700;margin-bottom:.5rem">Message envoyé !</h3>
              <p style="color:var(--stone2);font-size:.9rem">Nous avons bien reçu votre message et vous répondrons dans les meilleurs délais.</p>
            </div>
          <?php else: ?>
            <?php if (!empty($formErrors)): ?>
              <div class="alert alert-error" style="margin-bottom:1.5rem">
                <?= implode('<br>', array_map('h', $formErrors)) ?>
              </div>
            <?php endif; ?>

            <form method="POST">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

              <div class="form-group">
                <label class="form-label">Objet de votre demande *</label>
                <select name="sujet" id="sujet-select" class="form-select" onchange="togglePreinscription(this.value)">
                  <option value="information"    <?= ($_POST['sujet'] ?? '') === 'information'    ? 'selected' : '' ?>>Demande d'information générale</option>
                  <option value="preinscription" <?= ($_POST['sujet'] ?? '') === 'preinscription' ? 'selected' : '' ?>>Préinscription au club</option>
                  <option value="autre"          <?= ($_POST['sujet'] ?? '') === 'autre'          ? 'selected' : '' ?>>Autre</option>
                </select>
              </div>

              <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                  <label class="form-label">Nom complet *</label>
                  <input type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>" placeholder="Votre nom" class="form-input">
                </div>
                <div class="form-group">
                  <label class="form-label">Email *</label>
                  <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>" placeholder="votre@email.com" class="form-input">
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="tel" name="telephone" value="<?= h($_POST['telephone'] ?? '') ?>" placeholder="06 xx xx xx xx" class="form-input">
              </div>

              <div id="preinscription-fields" style="display:<?= ($_POST['sujet'] ?? '') === 'preinscription' ? 'block' : 'none' ?>">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                  <div class="form-group">
                    <label class="form-label">Catégorie</label>
                    <select name="categorie" class="form-select">
                      <option value="">Sélectionner...</option>
                      <option value="jeune"  <?= ($_POST['categorie'] ?? '') === 'jeune'  ? 'selected' : '' ?>>Jeune (moins de 18 ans)</option>
                      <option value="adulte" <?= ($_POST['categorie'] ?? '') === 'adulte' ? 'selected' : '' ?>>Adulte</option>
                      <option value="senior" <?= ($_POST['categorie'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label class="form-label">Âge</label>
                    <input type="number" name="age" value="<?= h($_POST['age'] ?? '') ?>" placeholder="Votre âge" class="form-input">
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
                    </select>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="form-label">Message *</label>
                <textarea name="message" required rows="5" placeholder="Votre message, questions, informations complémentaires..." class="form-textarea"><?= h($_POST['message'] ?? '') ?></textarea>
              </div>

              <p style="font-size:.75rem;color:var(--stone4);margin-bottom:1.25rem">Vos données sont utilisées uniquement pour traiter votre demande.</p>

              <button type="submit" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                Envoyer
              </button>
            </form>
          <?php endif; ?>
        </section>

      </div><!-- fin colonne droite -->
    </div><!-- fin rangée 2 -->
  </div><!-- fin container-lg -->
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function(){
  const map = L.map('map').setView([43.70684, 1.459539], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
  const makeIcon = c => L.icon({iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-'+c+'.png',shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',iconSize:[25,41],iconAnchor:[12,41],popupAnchor:[1,-34],shadowSize:[41,41]});
  L.marker([43.707341,1.458391],{icon:makeIcon('red')}).addTo(map).bindPopup('Terrain pour le tir en extérieur (parking du cimetière)');
  L.marker([43.706075,1.462986],{icon:makeIcon('blue')}).addTo(map).bindPopup('Gymnase Colette Besson (tir en salle)');
})();

function togglePreinscription(val) {
  document.getElementById('preinscription-fields').style.display = val === 'preinscription' ? 'block' : 'none';
}
</script>

<style>
@media(max-width:900px){
  .container-lg > div > div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns:1fr!important }
}
</style>

<?php include __DIR__ . '/inc/footer.php'; ?>
