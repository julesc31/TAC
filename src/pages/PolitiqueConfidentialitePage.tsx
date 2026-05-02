export default function PolitiqueConfidentialitePage() {
  return (
    <div className="pt-16 lg:pt-20">
      <div className="py-16 bg-stone-900">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl font-bold text-white mb-4">Politique de confidentialité</h1>
          <p className="text-stone-400">Comment le TAC Pechbonnieu gère vos données personnelles.</p>
        </div>
      </div>

      <section className="py-16 bg-stone-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-white rounded-2xl p-8 border border-stone-100 shadow-sm space-y-8 text-sm text-stone-600 leading-relaxed">

            <div className="bg-amber-50 border border-amber-200 rounded-xl p-4 text-stone-700">
              <p>
                Cette politique de confidentialité vous informe sur la façon dont le TAC Pechbonnieu collecte,
                utilise et protège vos données personnelles, conformément au Règlement Général sur la Protection
                des Données (RGPD) en vigueur depuis le 25 mai 2018.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                1. Responsable du traitement
              </h2>
              <p>
                Le responsable du traitement de vos données personnelles est l'association TAC Pechbonnieu,
                représentée par son Président en exercice, dont le siège est situé Salle des sports de Pechbonnieu,
                31140 Pechbonnieu.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                2. Données collectées
              </h2>
              <p className="mb-3">
                Nous collectons les données personnelles que vous nous communiquez volontairement, notamment :
              </p>
              <ul className="space-y-2">
                {[
                  'Nom et prénom',
                  'Adresse email',
                  'Numéro de téléphone (optionnel)',
                  'Date de naissance (pour les inscriptions)',
                  'Informations relatives à votre pratique sportive',
                ].map((item) => (
                  <li key={item} className="flex items-center gap-2">
                    <span className="w-1.5 h-1.5 bg-amber-500 rounded-full shrink-0" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                3. Finalités du traitement
              </h2>
              <p className="mb-3">Vos données sont collectées dans les finalités suivantes :</p>
              <ul className="space-y-2">
                {[
                  'Traitement de vos demandes d\'information ou de contact',
                  'Gestion des inscriptions et adhésions au club',
                  'Communication relative aux activités du club',
                  'Transmission à la FFTA pour la délivrance de la licence sportive',
                  'Respect des obligations légales (notamment en matière de sécurité des mineurs)',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2">
                    <span className="w-1.5 h-1.5 bg-amber-500 rounded-full shrink-0 mt-1.5" />
                    {item}
                  </li>
                ))}
              </ul>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                4. Base légale du traitement
              </h2>
              <p>
                Le traitement de vos données personnelles est fondé sur votre consentement explicite,
                donné lors de la soumission d'un formulaire, ou sur l'exécution d'un contrat (adhésion
                au club) auquel vous êtes partie.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                5. Destinataires des données
              </h2>
              <p className="mb-3">Vos données peuvent être communiquées aux destinataires suivants :</p>
              <ul className="space-y-2">
                {[
                  'Les membres du bureau du club (dans le cadre de la gestion des adhésions)',
                  'La Fédération Française de Tir à l\'Arc (FFTA) pour la délivrance de la licence',
                  'Toute autorité légalement habilitée à en prendre connaissance',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2">
                    <span className="w-1.5 h-1.5 bg-amber-500 rounded-full shrink-0 mt-1.5" />
                    {item}
                  </li>
                ))}
              </ul>
              <p className="mt-3">
                Vos données ne sont jamais vendues à des tiers ni utilisées à des fins commerciales.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                6. Durée de conservation
              </h2>
              <p>
                Vos données personnelles sont conservées pendant la durée de votre adhésion au club,
                augmentée d'une période de 3 ans après la fin de votre dernière adhésion, à des fins
                archivistiques et pour répondre à d'éventuelles obligations légales.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                7. Vos droits
              </h2>
              <p className="mb-3">Conformément au RGPD, vous disposez des droits suivants :</p>
              <ul className="space-y-2 mb-3">
                {[
                  'Droit d\'accès à vos données personnelles',
                  'Droit de rectification des données inexactes',
                  'Droit à l\'effacement ("droit à l\'oubli")',
                  'Droit à la limitation du traitement',
                  'Droit à la portabilité de vos données',
                  'Droit d\'opposition au traitement',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2">
                    <span className="w-1.5 h-1.5 bg-amber-500 rounded-full shrink-0 mt-1.5" />
                    {item}
                  </li>
                ))}
              </ul>
              <p>
                Pour exercer ces droits, contactez-nous via le formulaire de contact disponible sur ce site.
                Vous pouvez également introduire une réclamation auprès de la CNIL (Commission Nationale
                de l'Informatique et des Libertés) si vous estimez que le traitement de vos données n'est
                pas conforme à la réglementation.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                8. Sécurité des données
              </h2>
              <p>
                Nous mettons en œuvre des mesures techniques et organisationnelles appropriées pour protéger
                vos données personnelles contre la perte accidentelle, l'accès non autorisé, la divulgation,
                l'altération ou la destruction.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                9. Cookies
              </h2>
              <p>
                Ce site utilise uniquement des cookies techniques nécessaires à son bon fonctionnement.
                Aucun cookie de suivi publicitaire ou analytique tiers n'est déposé sans votre consentement explicite.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                10. Modifications
              </h2>
              <p>
                Cette politique de confidentialité peut être mise à jour pour refléter les changements
                dans nos pratiques ou pour se conformer aux évolutions légales. La date de dernière mise
                à jour est indiquée ci-dessous.
              </p>
            </div>

            <div className="bg-stone-50 rounded-xl p-4 border border-stone-100">
              <p className="text-stone-400 text-xs text-center">
                Dernière mise à jour : {new Date().toLocaleDateString('fr-FR', { year: 'numeric', month: 'long' })}
              </p>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
