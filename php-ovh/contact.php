<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle  = 'Contact — Arc Club Pechbonnieu';
$submitted  = false;
$formErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $nom     = trim($_POST['nom'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $sujet   = trim($_POST['sujet'] ?? 'information');
    $message = trim($_POST['message'] ?? '');

    if (!$nom)     $formErrors[] = 'Le nom est requis.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $formErrors[] = 'L\'email est invalide.';
    if (!$message) $formErrors[] = 'Le message est requis.';

    if (empty($formErrors)) {
        // Enregistrement en base
        try {
            $stmt = db()->prepare("INSERT INTO contacts (nom, email, sujet, message, created_at) VALUES (?,?,?,?,NOW())");
            $stmt->execute([$nom, $email, $sujet, $message]);
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
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Contact</h1>
      <p>Une question ? Nous sommes là pour vous répondre.</p>
    </div>
  </div>

  <div class="container-md pb-20" style="padding-bottom:5rem;padding-top:2rem">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:2.5rem">

      <!-- Colonne gauche -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">

        <!-- Coordonnées -->
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

        <!-- Horaires -->
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

        <!-- Carte -->
        <section class="card" style="padding:0;overflow:hidden">
          <div style="padding:1rem;border-bottom:1px solid var(--white10)">
            <h2 style="color:var(--gold);font-weight:700;font-size:1.1rem">Plan d'accès</h2>
          </div>
          <div id="map" style="height:320px"></div>
          <div style="padding:.75rem 1rem;border-top:1px solid var(--white10);display:flex;flex-direction:column;gap:.5rem">
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#ef4444;flex-shrink:0"></span>Terrain de tir extérieur</span>
            <span style="display:flex;align-items:center;gap:.5rem;font-size:.875rem;color:var(--stone2)"><span style="width:.75rem;height:.75rem;border-radius:50%;background:#3b82f6;flex-shrink:0"></span>Gymnase Colette Besson</span>
          </div>
        </section>

      </div>

      <!-- Colonne droite -->
      <div style="display:flex;flex-direction:column;gap:1.5rem">

        <!-- Formulaire -->
        <section class="card">
          <h2 class="card-title">Formulaire de contact</h2>

          <?php if ($submitted): ?>
            <div style="text-align:center;padding:2.5rem 0">
              <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5" style="margin:0 auto 1rem"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              <h3 style="color:#fff;font-size:1.25rem;font-weight:700;margin-bottom:.5rem">Message envoyé !</h3>
              <p style="color:var(--stone2);font-size:.9rem">Nous vous répondrons dans les meilleurs délais.</p>
            </div>
          <?php else: ?>

            <?php if (!empty($formErrors)): ?>
              <div class="alert alert-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div><?= implode('<br>', array_map('h', $formErrors)) ?></div>
              </div>
            <?php endif; ?>

            <form method="POST">
              <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">
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
                  <option value="information" <?= ($_POST['sujet'] ?? 'information') === 'information' ? 'selected' : '' ?>>Demande d'information</option>
                  <option value="inscription" <?= ($_POST['sujet'] ?? '') === 'inscription' ? 'selected' : '' ?>>Inscription</option>
                  <option value="autre" <?= ($_POST['sujet'] ?? '') === 'autre' ? 'selected' : '' ?>>Autre</option>
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

        <!-- Réseaux sociaux -->
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
  </div>
</main>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function(){
  const map = L.map('map').setView([43.70684, 1.459539], 15);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'© OpenStreetMap'}).addTo(map);
  const red = L.icon({iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',iconSize:[25,41],iconAnchor:[12,41],popupAnchor:[1,-34],shadowSize:[41,41]});
  const blue = L.icon({iconUrl:'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',shadowUrl:'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',iconSize:[25,41],iconAnchor:[12,41],popupAnchor:[1,-34],shadowSize:[41,41]});
  L.marker([43.707341,1.458391],{icon:red}).addTo(map).bindPopup('Terrain pour le tir en extérieur (parking du cimetière)');
  L.marker([43.706075,1.462986],{icon:blue}).addTo(map).bindPopup('Gymnase Colette Besson (tir en salle)');
})();
</script>
<style>
@media(max-width:768px){
  .container-md > div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr!important}
}
</style>

<?php include __DIR__ . '/inc/footer.php'; ?>
