export default function MentionsLegalesPage() {
  return (
    <div className="pt-16 lg:pt-20">
      <div className="py-16 bg-stone-900">
        <div className="max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl font-bold text-white mb-4">Mentions légales</h1>
          <p className="text-stone-400">Informations légales relatives au site du TAC Pechbonnieu.</p>
        </div>
      </div>

      <section className="py-16 bg-stone-50">
        <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="bg-white rounded-2xl p-8 border border-stone-100 shadow-sm space-y-8">

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                1. Éditeur du site
              </h2>
              <div className="text-stone-600 text-sm space-y-2 leading-relaxed">
                <p><strong>Nom de l'association :</strong> Tir à l'Arc Club de Pechbonnieu (TAC Pechbonnieu)</p>
                <p><strong>Statut :</strong> Association loi 1901</p>
                <p><strong>Siège social :</strong> Salle des sports de Pechbonnieu, 31140 Pechbonnieu</p>
                <p><strong>Activité :</strong> Section tir à l'arc du Foyer Rural de Pechbonnieu, affiliée à la Fédération Française de Tir à l'Arc (FFTA)</p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                2. Directeur de la publication
              </h2>
              <p className="text-stone-600 text-sm leading-relaxed">
                Le directeur de la publication est le Président en exercice de l'association TAC Pechbonnieu.
                Pour tout contact relatif au contenu du site, merci d'utiliser le formulaire de contact disponible sur ce site.
              </p>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                3. Hébergement
              </h2>
              <div className="text-stone-600 text-sm space-y-2 leading-relaxed">
                <p>Ce site est hébergé par un prestataire d'hébergement web professionnel.</p>
                <p>Pour toute question relative à l'hébergement, contactez-nous via le formulaire de contact.</p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                4. Propriété intellectuelle
              </h2>
              <div className="text-stone-600 text-sm space-y-3 leading-relaxed">
                <p>
                  L'ensemble du contenu de ce site (textes, images, illustrations, photographies, logos, etc.)
                  est la propriété de l'association TAC Pechbonnieu ou de ses partenaires, et est protégé par
                  les lois françaises et internationales relatives à la propriété intellectuelle.
                </p>
                <p>
                  Toute reproduction, représentation, modification, publication, transmission, ou dénaturation,
                  totale ou partielle du contenu de ce site, par quelque procédé que ce soit, et sur quelque
                  support que ce soit est interdite, sauf autorisation préalable écrite du TAC Pechbonnieu.
                </p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                5. Données personnelles
              </h2>
              <div className="text-stone-600 text-sm space-y-3 leading-relaxed">
                <p>
                  Conformément au Règlement Général sur la Protection des Données (RGPD) et à la loi Informatique
                  et Libertés, vous disposez d'un droit d'accès, de rectification, de suppression et de portabilité
                  de vos données personnelles.
                </p>
                <p>
                  Pour exercer ces droits ou pour toute question relative au traitement de vos données,
                  vous pouvez nous contacter via le formulaire de contact du site.
                </p>
                <p>
                  Pour plus d'informations sur la gestion de vos données personnelles, consultez notre
                  politique de confidentialité.
                </p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                6. Cookies
              </h2>
              <div className="text-stone-600 text-sm space-y-3 leading-relaxed">
                <p>
                  Ce site peut utiliser des cookies techniques nécessaires au bon fonctionnement du site.
                  Ces cookies ne collectent aucune donnée personnelle à des fins commerciales.
                </p>
                <p>
                  Vous pouvez désactiver les cookies via les paramètres de votre navigateur, ce qui pourrait
                  toutefois affecter certaines fonctionnalités du site.
                </p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                7. Responsabilité
              </h2>
              <div className="text-stone-600 text-sm space-y-3 leading-relaxed">
                <p>
                  L'association TAC Pechbonnieu s'efforce de fournir des informations exactes et à jour sur ce site.
                  Toutefois, elle ne peut garantir l'exactitude, la complétude ou l'actualité des informations
                  diffusées sur ce site.
                </p>
                <p>
                  Le TAC Pechbonnieu décline toute responsabilité quant aux dommages directs ou indirects résultant
                  de l'utilisation de ce site ou de l'impossibilité d'y accéder.
                </p>
              </div>
            </div>

            <div>
              <h2 className="text-xl font-bold text-stone-900 mb-4 pb-2 border-b border-stone-100">
                8. Droit applicable
              </h2>
              <p className="text-stone-600 text-sm leading-relaxed">
                Les présentes mentions légales sont soumises au droit français. En cas de litige, les tribunaux
                français seront seuls compétents.
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
