<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle  = 'Rejoindre le club — Arc Club Pechbonnieu';
$submitted  = false;
$formErrors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
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
        // Enregistrement en base (table preinscriptions)
        try {
            $stmt = db()->prepare("INSERT INTO preinscriptions (nom, email, categorie, age, telephone, type_arc, niveau, commentaires, created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
            $stmt->execute([$nom, $email, $categorie, $age ?: null, $telephone ?: null, $typeArc ?: null, $niveau ?: null, $commentaires ?: null]);
            $submitted = true;
        } catch (Exception $e) {
            $formErrors[] = 'Erreur lors de l\'enregistrement. Veuillez réessayer.';
        }
    }
}

include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/8107224/pexels-photo-8107224.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Rejoindre le club</h1>
      <p>Toutes les informations pour vous inscrire à l'Arc Club Pechbonnieu et commencer votre aventure.</p>
    </div>
  </div>

  <div class="container-md pb-20 space-y-8" style="padding-bottom:5rem;padding-top:2rem">

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
      <div class="check-list">
        <?php foreach ([
          'Le club prête le matériel pour les débutants',
          'Licence FFTA obligatoire pour pratiquer (assurance incluse)',
          'Séances d\'initiation disponibles avant inscription',
          'Accès aux compétitions régionales et nationales',
          'Encadrement par des moniteurs diplômés FFTA',
          'Pratique possible dès l\'âge de 7 ans',
        ] as $item): ?>
          <div class="check-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
            <span><?= h($item) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Étapes -->
    <section class="card">
      <h2 class="card-title">Comment s'inscrire ?</h2>
      <div class="steps-grid">
        <?php foreach ([
          ['1', 'Préinscription', 'Remplissez le formulaire ci-dessous pour manifester votre intérêt.'],
          ['2', 'Séance découverte', 'Venez essayer gratuitement lors d\'une de nos séances d\'initiation.'],
          ['3', 'Dossier d\'inscription', 'Remplissez le dossier d\'adhésion et réglez la cotisation.'],
          ['4', 'Licence FFTA', 'Finalisez votre licence sur le site de la FFTA. Vous êtes prêt à tirer !'],
        ] as [$step, $title, $desc]): ?>
          <div class="step-card">
            <div class="step-num"><?= $step ?></div>
            <div class="step-title"><?= h($title) ?></div>
            <p class="step-desc"><?= h($desc) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- Formulaire -->
    <section class="card">
      <h2 class="card-title">Formulaire de préinscription</h2>
      <p class="text-muted" style="font-size:.875rem;margin-bottom:2rem">Remplissez ce formulaire pour manifester votre intérêt. Nous vous contacterons pour vous donner la suite.</p>

      <?php if ($submitted): ?>
        <div style="text-align:center;padding:2.5rem 0">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="1.5" style="margin:0 auto 1rem"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <h3 style="color:#fff;font-size:1.25rem;font-weight:700;margin-bottom:.5rem">Demande envoyée !</h3>
          <p style="color:var(--stone2);margin-bottom:1.5rem;font-size:.9rem">Nous avons bien reçu votre demande de préinscription. Nous vous contacterons sous 48h.</p>
          <a href="/" class="btn btn-primary">Retour à l'accueil</a>
        </div>
      <?php else: ?>

        <?php if (!empty($formErrors)): ?>
          <div class="alert alert-error" style="margin-bottom:1.5rem">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <div><?= implode('<br>', array_map('h', $formErrors)) ?></div>
          </div>
        <?php endif; ?>

        <form method="POST" style="max-width:40rem">
          <input type="hidden" name="csrf_token" value="<?= h(csrf_token()) ?>">

          <div class="form-group">
            <label class="form-label">Catégorie *</label>
            <select name="categorie" required class="form-select">
              <option value="">Sélectionner...</option>
              <option value="jeune" <?= ($_POST['categorie'] ?? '') === 'jeune' ? 'selected' : '' ?>>Jeune (moins de 18 ans)</option>
              <option value="adulte" <?= ($_POST['categorie'] ?? '') === 'adulte' ? 'selected' : '' ?>>Adulte</option>
              <option value="senior" <?= ($_POST['categorie'] ?? '') === 'senior' ? 'selected' : '' ?>>Senior</option>
            </select>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Nom complet *</label>
              <input type="text" name="nom" required value="<?= h($_POST['nom'] ?? '') ?>" placeholder="Votre nom" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Âge</label>
              <input type="number" name="age" value="<?= h($_POST['age'] ?? '') ?>" placeholder="Votre âge" class="form-input" min="5" max="100">
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Email *</label>
              <input type="email" name="email" required value="<?= h($_POST['email'] ?? '') ?>" placeholder="votre@email.com" class="form-input">
            </div>
            <div class="form-group">
              <label class="form-label">Téléphone</label>
              <input type="tel" name="telephone" value="<?= h($_POST['telephone'] ?? '') ?>" placeholder="06 xx xx xx xx" class="form-input">
            </div>
          </div>

          <div class="form-row">
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

          <p style="font-size:.75rem;color:var(--stone4);display:flex;align-items:flex-start;gap:.5rem;margin-bottom:1.25rem">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2" style="flex-shrink:0;margin-top:.1rem"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            En soumettant ce formulaire, vous acceptez que vos données soient utilisées pour traiter votre demande d'inscription. Voir notre <a href="/politique-confidentialite.php" style="color:var(--gold)">politique de confidentialité</a>.
          </p>

          <button type="submit" class="btn btn-primary">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Envoyer ma préinscription
          </button>
        </form>
      <?php endif; ?>
    </section>

  </div>
</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
