export default function TraditionsPage() {
  return (
    <div className="pt-16 lg:pt-20 bg-[#0a2744] min-h-screen">
      {/* Hero */}
      <div className="relative py-20 overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center opacity-25"
          style={{
            backgroundImage:
              'url(https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=1600)',
          }}
        />
        <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">Traditions des Archers</h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Le tir à l'arc est plus qu'un simple sport. Une discipline ancienne riche en valeurs et en histoire.
          </p>
        </div>
      </div>

      <div className="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-8">

        {/* Intro */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Plus qu'un Sport : Une Pratique Riche en Traditions</h2>
          <p className="text-stone-200 leading-relaxed mb-4">
            Dans le tir à l'arc, chaque geste compte. La façon de tenir l'arc, de viser, et même de récupérer
            les flèches, s'inscrit dans une longue tradition. Ces gestes, perfectionnés au fil du temps,
            combinent efficacité technique et respect des pratiques anciennes.
          </p>
          <h3 className="text-[#ffd700] font-semibold mb-3">Les Valeurs du Tir à l'Arc</h3>
          <p className="text-stone-200 leading-relaxed mb-3">La pratique du tir à l'arc met l'accent sur plusieurs qualités importantes :</p>
          <ul className="space-y-2">
            {[
              'La concentration et la patience',
              'La maîtrise de soi',
              'Le respect des autres et du matériel',
              'La volonté de s\'améliorer constamment',
            ].map((val) => (
              <li key={val} className="flex items-start gap-2 text-stone-200 text-sm">
                <span className="text-[#ffd700]">›</span>
                {val}
              </li>
            ))}
          </ul>
          <p className="text-stone-300 text-sm mt-4 leading-relaxed">
            Ces valeurs, essentielles pour bien tirer, sont également utiles dans la vie quotidienne.
            Elles font du tir à l'arc non seulement un sport, mais aussi une école de vie.
          </p>
        </section>

        {/* Le Beursault */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Le Beursault : Une Tradition Ancrée dans l'Histoire Militaire</h2>
          <p className="text-stone-200 leading-relaxed mb-4">
            Le Beursault est l'une des formes de tir à l'arc les plus anciennes en France. Son origine remonte
            au Moyen Âge et est étroitement liée à l'entraînement militaire des archers.
          </p>

          <h3 className="text-[#ffd700] font-semibold mb-3">L'Origine des Allers-Retours</h3>
          <p className="text-stone-300 text-sm mb-2">Les allers-retours caractéristiques du Beursault ont une origine pratique et militaire :</p>
          <ul className="space-y-2 mb-5">
            {[
              'Ils simulent les conditions de combat où les archers devaient tirer dans les deux sens, en avançant et en reculant sur le champ de bataille.',
              'Cette pratique permettait aux archers de s\'entraîner à tirer avec précision dans différentes directions et positions.',
              'Les distances de tir (environ 50 mètres) correspondent aux portées efficaces des arcs de l\'époque dans les batailles.',
            ].map((item) => (
              <li key={item} className="flex items-start gap-2 text-stone-200 text-sm">
                <span className="text-[#ffd700] shrink-0">›</span>
                {item}
              </li>
            ))}
          </ul>

          <h3 className="text-[#ffd700] font-semibold mb-3">Évolution vers une Pratique Sportive</h3>
          <ul className="space-y-2">
            {[
              'Les compagnies d\'arc, initialement formées pour la défense des villes, ont maintenu cette pratique même après l\'obsolescence militaire de l\'arc.',
              'Le rituel des allers-retours a été préservé, devenant une partie intégrante de la tradition du tir à l\'arc français.',
              'Aujourd\'hui, le Beursault est apprécié pour son défi technique et son lien avec l\'histoire du tir à l\'arc.',
            ].map((item) => (
              <li key={item} className="flex items-start gap-2 text-stone-200 text-sm">
                <span className="text-[#ffd700] shrink-0">›</span>
                {item}
              </li>
            ))}
          </ul>
        </section>

        {/* Le Jeu d'Arc */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Le Jeu d'Arc : Un Espace Dédié à la Pratique</h2>
          <p className="text-stone-200 leading-relaxed mb-5">
            Le jeu d'arc est une installation spécifique au tir à l'arc traditionnel français. C'est un espace
            conçu pour la pratique du tir Beursault, mais il sert aussi à d'autres formes de tir.
          </p>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-5">
            <div>
              <h3 className="text-[#ffd700] font-semibold mb-3">Qu'est-ce qu'un Jeu d'Arc ?</h3>
              <ul className="space-y-2 text-sm text-stone-200">
                <li><strong className="text-white">Structure :</strong> Terrain rectangulaire, clos par des murs ou des haies.</li>
                <li><strong className="text-white">Éléments clés :</strong> Deux buttes de tir aux extrémités, séparées par une allée centrale.</li>
                <li><strong className="text-white">Dimensions :</strong> Longueur typique d'environ 50 mètres.</li>
              </ul>
            </div>
            <div>
              <h3 className="text-[#ffd700] font-semibold mb-3">À quoi sert un Jeu d'Arc ?</h3>
              <ul className="space-y-1 text-sm text-stone-200">
                {['Entraînement sécurisé', 'Compétitions traditionnelles', 'Enseignement des techniques', 'Vie sociale et camaraderie'].map((item) => (
                  <li key={item} className="flex items-center gap-2">
                    <span className="text-[#ffd700]">›</span>
                    {item}
                  </li>
                ))}
              </ul>
            </div>
          </div>

          <div className="bg-[#1a4a7c]/50 border border-[#ffd700]/30 rounded-xl p-4">
            <p className="text-stone-200 text-sm leading-relaxed">
              À l'Arc Club Pechbonnieu, notre jeu d'arc est un atout précieux. Il nous permet de pratiquer
              le tir traditionnel tout en offrant un cadre agréable pour nos entraînements et nos rencontres amicales.
            </p>
          </div>
        </section>

        {/* Le Morata */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Le Morata : Une Tradition Unique du Sud-Ouest</h2>
          <p className="text-stone-200 leading-relaxed mb-4">
            Le Morata, né dans les années 1970, est une tradition de tir à l'arc propre au Sud-Ouest de la France.
            Il incarne l'esprit de camaraderie et d'inclusivité au sein de la communauté des archers.
          </p>

          <h3 className="text-[#ffd700] font-semibold mb-3">Objectifs du Morata</h3>
          <ul className="space-y-2 mb-4">
            {[
              'Renforcer les liens entre clubs locaux',
              'Favoriser l\'échange et la progression dans un cadre bienveillant',
              'Accueillir les archers de tous niveaux, du débutant à l\'expert',
              'Préserver l\'esprit traditionnel du tir à l\'arc',
            ].map((item) => (
              <li key={item} className="flex items-start gap-2 text-stone-200 text-sm">
                <span className="text-[#ffd700] shrink-0">›</span>
                {item}
              </li>
            ))}
          </ul>
          <p className="text-stone-300 text-sm leading-relaxed mb-3">
            Contrairement aux compétitions classiques, le Morata met l'accent sur le partage d'expérience et
            l'amélioration personnelle plutôt que sur la performance pure. Ces rencontres se déroulent dans une
            ambiance conviviale, souvent conclues par un repas partagé.
          </p>
          <p className="text-stone-300 text-sm leading-relaxed">
            Participer régulièrement aux Morata, c'est perpétuer cette tradition qui incarne les valeurs
            fondamentales du tir à l'arc : respect mutuel, entraide et passion partagée.
          </p>
        </section>

        {/* Le Salut */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Le Salut et les Gestes de Respect</h2>
          <p className="text-stone-200 leading-relaxed mb-5">
            Dans le tir à l'arc, de nombreux gestes traditionnels sont non seulement des marques de respect,
            mais aussi des pratiques de sécurité essentielles.
          </p>

          <h3 className="text-[#ffd700] font-semibold mb-3">Le Salut</h3>
          <ul className="space-y-3 mb-6">
            {[
              { title: 'Salut à la cible', desc: 'Effectué avant et après chaque volée, ce geste marque le respect envers la cible et les autres archers. Il signale aussi le début et la fin d\'une séquence de tir.' },
              { title: 'Salut aux arbitres et officiels', desc: 'Dans les compétitions, ce salut montre le respect envers l\'autorité et les règles du jeu.' },
              { title: 'Salut entre archers', desc: 'Échangé avant et après une compétition ou une séance d\'entraînement, il renforce l\'esprit de camaraderie.' },
            ].map(({ title, desc }) => (
              <li key={title} className="text-stone-200 text-sm">
                <strong className="text-white">{title} : </strong>{desc}
              </li>
            ))}
          </ul>

          <h3 className="text-[#ffd700] font-semibold mb-3">Gestes de Sécurité Ancrés dans la Tradition</h3>
          <ul className="space-y-3 mb-5">
            {[
              { title: 'La position d\'attente', desc: 'Tenir l\'arc verticalement, pointe de flèche vers le sol — une mesure de sécurité pour éviter les accidents.' },
              { title: 'Le ramassage cérémoniel des flèches', desc: 'Une par une, avec soin, pour vérifier l\'état de chaque flèche et éviter les blessures.' },
              { title: 'L\'annonce de la fin de tir', desc: 'Signal de sécurité crucial pour les autres archers.' },
            ].map(({ title, desc }) => (
              <li key={title} className="text-stone-200 text-sm">
                <strong className="text-white">{title} : </strong>{desc}
              </li>
            ))}
          </ul>

          <div className="bg-[#1a4a7c]/50 border border-[#ffd700]/30 rounded-xl p-4">
            <p className="text-stone-200 text-sm leading-relaxed">
              À l'Arc Club Pechbonnieu, nous enseignons ces gestes et pratiques comme faisant partie intégrante
              de l'apprentissage du tir à l'arc. En comprenant leur double rôle — respect de la tradition et
              sécurité — nos archers développent une approche plus complète et responsable de leur sport.
            </p>
          </div>
        </section>

      </div>
    </div>
  );
}
