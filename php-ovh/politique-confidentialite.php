<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();
$pageTitle = 'Politique de confidentialité — Arc Club Pechbonnieu';
include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <div style="background:#1c1917;padding:4rem 0">
    <div class="container-sm" style="text-align:center">
      <h1 style="font-size:2.5rem;font-weight:700;color:#fff;margin-bottom:1rem">Politique de confidentialité</h1>
      <p style="color:#a8a29e">Comment le TAC Pechbonnieu gère vos données personnelles.</p>
    </div>
  </div>

  <section style="background:#fafaf9;padding:4rem 0">
    <div class="container-sm">
      <div style="background:#fff;border-radius:1rem;padding:2rem;border:1px solid #e7e5e4;box-shadow:0 1px 4px rgba(0,0,0,.05);color:#57534e;font-size:.875rem;line-height:1.7">

        <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:.75rem;padding:1rem;margin-bottom:2rem;color:#44403c">
          <p>Cette politique de confidentialité vous informe sur la façon dont le TAC Pechbonnieu collecte, utilise et protège vos données personnelles, conformément au RGPD en vigueur depuis le 25 mai 2018.</p>
        </div>

        <?php
        $sections = [
          '1. Responsable du traitement' => '<p>Le responsable du traitement de vos données personnelles est l\'association TAC Pechbonnieu, représentée par son Président en exercice, dont le siège est situé Salle des sports de Pechbonnieu, 31140 Pechbonnieu.</p>',
          '2. Données collectées' => '<p>Nous collectons les données personnelles que vous nous communiquez volontairement, notamment :</p><ul style="margin-top:.75rem;list-style:none;display:flex;flex-direction:column;gap:.4rem"><li style="display:flex;align-items:center;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0"></span>Nom et prénom</li><li style="display:flex;align-items:center;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0"></span>Adresse email</li><li style="display:flex;align-items:center;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0"></span>Numéro de téléphone (optionnel)</li><li style="display:flex;align-items:center;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0"></span>Date de naissance (pour les inscriptions)</li><li style="display:flex;align-items:center;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0"></span>Informations relatives à votre pratique sportive</li></ul>',
          '3. Finalités du traitement' => '<p>Vos données sont collectées dans les finalités suivantes :</p><ul style="margin-top:.75rem;list-style:none;display:flex;flex-direction:column;gap:.4rem"><li style="display:flex;align-items:flex-start;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0;margin-top:.45rem"></span>Traitement de vos demandes d\'information ou de contact</li><li style="display:flex;align-items:flex-start;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0;margin-top:.45rem"></span>Gestion des inscriptions et adhésions au club</li><li style="display:flex;align-items:flex-start;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0;margin-top:.45rem"></span>Communication relative aux activités du club</li><li style="display:flex;align-items:flex-start;gap:.5rem"><span style="width:.4rem;height:.4rem;background:#f59e0b;border-radius:50%;flex-shrink:0;margin-top:.45rem"></span>Transmission à la FFTA pour la délivrance de la licence sportive</li></ul>',
          '4. Base légale du traitement' => '<p>Le traitement de vos données personnelles est fondé sur votre consentement explicite, donné lors de la soumission d\'un formulaire, ou sur l\'exécution d\'un contrat (adhésion au club) auquel vous êtes partie.</p>',
          '5. Destinataires des données' => '<p>Vos données peuvent être communiquées aux membres du bureau du club et à la FFTA pour la délivrance de la licence. Elles ne sont jamais vendues à des tiers ni utilisées à des fins commerciales.</p>',
          '6. Durée de conservation' => '<p>Vos données personnelles sont conservées pendant la durée de votre adhésion au club, augmentée d\'une période de 3 ans après la fin de votre dernière adhésion.</p>',
          '7. Vos droits' => '<p>Conformément au RGPD, vous disposez des droits suivants : accès, rectification, effacement, limitation du traitement, portabilité, opposition. Pour exercer ces droits, contactez-nous via le formulaire de contact.</p><p style="margin-top:.75rem">Vous pouvez également introduire une réclamation auprès de la CNIL si vous estimez que le traitement de vos données n\'est pas conforme à la réglementation.</p>',
          '8. Sécurité des données' => '<p>Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger vos données personnelles contre la perte accidentelle, l\'accès non autorisé, la divulgation, l\'altération ou la destruction.</p>',
          '9. Cookies' => '<p>Ce site utilise uniquement des cookies techniques nécessaires à son bon fonctionnement. Aucun cookie de suivi publicitaire ou analytique tiers n\'est déposé sans votre consentement explicite.</p>',
          '10. Modifications' => '<p>Cette politique de confidentialité peut être mise à jour pour refléter les changements dans nos pratiques ou pour se conformer aux évolutions légales.</p>',
        ];
        foreach ($sections as $title => $content): ?>
          <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid #f5f5f4">
            <h2 style="font-size:1.15rem;font-weight:700;color:#1c1917;margin-bottom:1rem;padding-bottom:.5rem;border-bottom:1px solid #e7e5e4"><?= h($title) ?></h2>
            <?= $content ?>
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
