export default function DisciplinesPage() {
  const reasons = [
    { num: '1', title: 'Sport de plein air et de salle', desc: 'Pratique toute l\'année, en ville ou à la campagne, en loisir comme en compétition.' },
    { num: '2', title: 'Bon pour le corps', desc: 'Amélioration de la coordination, de l\'équilibre, exercice musculaire harmonieux, amélioration du système cardiovasculaire.' },
    { num: '3', title: 'Bon pour l\'esprit', desc: 'Effet anti-stress, canalisation de l\'énergie, amélioration de la concentration, maîtrise de soi.' },
    { num: '4', title: 'Pour tous', desc: 'Adapté à tous les âges et toutes les morphologies.' },
    { num: '5', title: 'Sport d\'intégration', desc: 'Permet à tous, valides et non valides, de pratiquer ensemble.' },
    { num: '6', title: 'Sport éducatif', desc: 'Apprentissage du calcul, gestion des émotions, respect des règles et de l\'environnement.' },
  ];

  return (
    <div className="pt-16 lg:pt-20 bg-[#0a2744] min-h-screen">
      {/* Hero */}
      <div className="relative py-20 overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center opacity-25"
          style={{
            backgroundImage:
              'url(https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=1600)',
          }}
        />
        <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">Disciplines</h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Découvrez le tir à l'arc sous toutes ses formes — du loisir à la compétition FFTA
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-16">

        {/* Le Tir à l'Arc */}
        <section>
          <h2 className="text-3xl font-bold text-[#ffd700] mb-8 text-center">LE TIR À L'ARC</h2>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <div className="text-stone-200 space-y-4 text-base leading-relaxed">
              <p>
                Le Tir à l'Arc était pratiqué à la préhistoire comme arme de chasse, puis, à l'époque des chevaliers,
                comme arme de guerre. La pratique actuelle est un art martial, qui allie de nombreuses qualités :
              </p>
              <ul className="space-y-2 ml-4">
                {[
                  'Anticipation, contrôle de la respiration, de la posture, de la tension musculaire',
                  'Amélioration de l\'endurance cardiovasculaire',
                  'Maîtrise de soi et de ses émotions',
                  'Amélioration de la coordination des mouvements',
                  'Amélioration de ses repères dans l\'espace',
                ].map((item) => (
                  <li key={item} className="flex items-start gap-2">
                    <span className="text-[#ffd700] mt-1">›</span>
                    <span>{item}</span>
                  </li>
                ))}
              </ul>
              <p>Il peut être pratiqué toute l'année, que ce soit en salle ou en extérieur.</p>
              <p>Ce sport est un excellent moyen de détente et de relâchement des tensions accumulées dans la journée.</p>
              <p>
                Il fait appel à des qualités physiques et mentales. Il peut se pratiquer en compétition ou en
                une activité de loisir — il n'est pas nécessaire d'être un champion de haut niveau pour y trouver
                un grand plaisir.
              </p>
              <p className="italic text-[#ffd700]">
                Qui n'a jamais rêvé de ressembler à Robin des bois ou autre archer légendaire ?
              </p>
            </div>
            <div className="rounded-2xl overflow-hidden shadow-2xl">
              <img
                src="https://images.pexels.com/photos/6874498/pexels-photo-6874498.jpeg?auto=compress&cs=tinysrgb&w=800"
                alt="Archer en action"
                className="w-full h-80 object-cover"
              />
            </div>
          </div>
        </section>

        {/* 6 Bonnes raisons */}
        <section className="bg-[#1a4a7c]/60 border border-white/10 rounded-3xl p-8 sm:p-12">
          <h2 className="text-2xl sm:text-3xl font-bold text-[#ffd700] mb-3 text-center">
            SIX BONNES RAISONS DE PRATIQUER
          </h2>
          <p className="text-center text-stone-300 mb-10 max-w-xl mx-auto">
            Sport créateur de lien social : favorise la convivialité, les échanges entre générations,
            et peut se pratiquer en famille ou en équipe.
          </p>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {reasons.map(({ num, title, desc }) => (
              <div
                key={num}
                className="bg-white/10 border border-white/20 rounded-2xl p-6 hover:-translate-y-1 transition-transform duration-200"
              >
                <div className="w-10 h-10 bg-[#ffd700] text-[#0a2744] rounded-full flex items-center justify-center font-bold text-lg mb-4">
                  {num}
                </div>
                <h3 className="font-semibold text-[#ffd700] mb-2">{title}</h3>
                <p className="text-stone-300 text-sm leading-relaxed">{desc}</p>
              </div>
            ))}
          </div>
        </section>

        {/* Tir Loisir */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div>
              <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Tir Loisir</h2>
              <p className="text-stone-200 leading-relaxed mb-6">
                Le tir à l'arc loisir est ouvert à tous, débutants comme confirmés.
                C'est l'occasion de pratiquer dans une ambiance décontractée et conviviale.
              </p>
              <div className="mb-6">
                <h3 className="text-[#ffd700] font-semibold mb-3">Horaires</h3>
                <ul className="space-y-2">
                  {[
                    { day: 'Lundi', hours: '21h – 23h', public: 'Adultes' },
                    { day: 'Mercredi', hours: '16h – 17h30', public: 'Enfants et ados' },
                    { day: 'Samedi', hours: '09h – 12h', public: 'Tous' },
                  ].map(({ day, hours, public: pub }) => (
                    <li key={day} className="flex items-center gap-3 text-stone-200 text-sm">
                      <span className="font-semibold text-white w-20">{day}</span>
                      <span className="text-[#ffd700]">{hours}</span>
                      <span className="text-stone-400">({pub})</span>
                    </li>
                  ))}
                </ul>
              </div>
              <div>
                <h3 className="text-[#ffd700] font-semibold mb-2">Équipement nécessaire</h3>
                <p className="text-stone-300 text-sm leading-relaxed">
                  Le club met à disposition l'équipement nécessaire pour les débutants.
                  Pour les archers réguliers, nous recommandons l'achat de matériel personnel.
                </p>
              </div>
            </div>
            <div className="rounded-xl overflow-hidden">
              <img
                src="https://images.pexels.com/photos/6874498/pexels-photo-6874498.jpeg?auto=compress&cs=tinysrgb&w=800"
                alt="Tir loisir"
                className="w-full h-64 object-cover"
              />
            </div>
          </div>
        </section>

        {/* Compétition FFTA */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div className="order-2 lg:order-1 rounded-xl overflow-hidden">
              <img
                src="https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=800"
                alt="Compétition FFTA"
                className="w-full h-64 object-cover"
              />
            </div>
            <div className="order-1 lg:order-2">
              <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Compétition FFTA</h2>
              <p className="text-stone-200 leading-relaxed mb-6">
                Nos archers participent régulièrement aux compétitions organisées par la
                Fédération Française de Tir à l'Arc (FFTA).
              </p>
              <h3 className="text-[#ffd700] font-semibold mb-3">Types de compétitions</h3>
              <ul className="space-y-2">
                {[
                  'Tir en salle (18m)',
                  'Tir en extérieur (50m, 70m)',
                  'Tir campagne',
                  'Tir 3D',
                ].map((item) => (
                  <li key={item} className="flex items-center gap-2 text-stone-200 text-sm">
                    <span className="text-[#ffd700]">›</span>
                    {item}
                  </li>
                ))}
              </ul>
            </div>
          </div>
        </section>

        {/* Tir Traditionnel */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div>
              <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Tir Traditionnel</h2>
              <p className="text-stone-200 leading-relaxed mb-6">
                Le tir traditionnel met l'accent sur l'utilisation d'arcs historiques et traditionnels,
                offrant une expérience unique et enrichissante.
              </p>
              <div className="mb-6">
                <h3 className="text-[#ffd700] font-semibold mb-3">Types d'arcs traditionnels</h3>
                <ul className="space-y-2">
                  {[
                    'Arc droit (longbow)',
                    'Arc recurve nu',
                    'Arc à poulies nu',
                  ].map((item) => (
                    <li key={item} className="flex items-center gap-2 text-stone-200 text-sm">
                      <span className="text-[#ffd700]">›</span>
                      {item}
                    </li>
                  ))}
                </ul>
              </div>
              <div>
                <h3 className="text-[#ffd700] font-semibold mb-2">Événements spécifiques</h3>
                <p className="text-stone-300 text-sm leading-relaxed">
                  Nous organisons régulièrement des journées découverte et des compétitions amicales
                  de tir traditionnel.
                </p>
              </div>
            </div>
            <div className="rounded-xl overflow-hidden">
              <img
                src="https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=800"
                alt="Tir traditionnel"
                className="w-full h-64 object-cover"
              />
            </div>
          </div>
        </section>

      </div>
    </div>
  );
}
