<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();
$pageTitle = 'Mentions légales — Arc Club Pechbonnieu';
include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <div style="background:#1c1917;padding:4rem 0">
    <div class="container-sm" style="text-align:center">
      <h1 style="font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:1rem">Mentions légales</h1>
      <p style="color:#a8a29e">Informations légales relatives au site du TAC Pechbonnieu.</p>
    </div>
  </div>

  <section style="background:#fafaf9;padding:4rem 0">
    <div class="container-sm">
      <div style="background:#fff;border-radius:1rem;padding:2rem;border:1px solid #e7e5e4;box-shadow:0 1px 4px rgba(0,0,0,.05)">

        <?php
        $sections = [
          '1. Éditeur du site' => '<p><strong>Nom de l\'association :</strong> Tir à l\'Arc Club de Pechbonnieu (TAC Pechbonnieu)</p><p><strong>Statut :</strong> Association loi 1901</p><p><strong>Siège social :</strong> Salle des sports de Pechbonnieu, 31140 Pechbonnieu</p><p><strong>Activité :</strong> Club de tir à l\'arc affilié à la Fédération Française de Tir à l\'Arc (FFTA)</p>',
          '2. Directeur de la publication' => '<p>Le directeur de la publication est le Président en exercice de l\'association TAC Pechbonnieu. Pour tout contact relatif au contenu du site, merci d\'utiliser le formulaire de contact disponible sur ce site.</p>',
          '3. Hébergement' => '<p>Ce site est hébergé par OVH SAS, 2 rue Kellermann – 59100 Roubaix – France.</p>',
          '4. Propriété intellectuelle' => '<p>L\'ensemble du contenu de ce site (textes, images, illustrations, photographies, logos, etc.) est la propriété de l\'association TAC Pechbonnieu ou de ses partenaires, et est protégé par les lois françaises et internationales relatives à la propriété intellectuelle.</p><p style="margin-top:.75rem">Toute reproduction, représentation, modification, publication, transmission, ou dénaturation, totale ou partielle du contenu de ce site, par quelque procédé que ce soit est interdite, sauf autorisation préalable écrite du TAC Pechbonnieu.</p>',
          '5. Données personnelles' => '<p>Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique et Libertés, vous disposez d\'un droit d\'accès, de rectification, de suppression et de portabilité de vos données personnelles.</p><p style="margin-top:.75rem">Pour exercer ces droits, vous pouvez nous contacter via le formulaire de contact du site.</p>',
          '6. Cookies' => '<p>Ce site peut utiliser des cookies techniques nécessaires au bon fonctionnement du site. Ces cookies ne collectent aucune donnée personnelle à des fins commerciales.</p>',
          '7. Responsabilité' => '<p>L\'association TAC Pechbonnieu s\'efforce de fournir des informations exactes et à jour sur ce site. Toutefois, elle ne peut garantir l\'exactitude, la complétude ou l\'actualité des informations diffusées.</p>',
          '8. Droit applicable' => '<p>Les présentes mentions légales sont soumises au droit français. En cas de litige, les tribunaux français seront seuls compétents.</p>',
        ];
        foreach ($sections as $title => $content): ?>
          <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid #f5f5f4">
            <h2 style="font-size:1.15rem;font-weight:700;color:#1c1917;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid #e7e5e4"><?= h($title) ?></h2>
            <div style="color:#57534e;font-size:.875rem;line-height:1.7"><?= $content ?></div>
          </div>
        <?php endforeach; ?>

        <div style="background:#f5f5f4;border-radius:.75rem;padding:1rem;text-align:center">
          <p style="color:#a8a29e;font-size:.75rem">Dernière mise à jour : <?= (new DateTime())->format('F Y') ?></p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/inc/footer.php'; ?>
