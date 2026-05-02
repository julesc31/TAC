import { MapPin } from 'lucide-react';

export default function ClubPage() {
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
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">Le Club</h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Arc Club Pechbonnieu — une passion partagée
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20 space-y-8">

        {/* Histoire */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Histoire du Club</h2>
          <p className="text-stone-200 leading-relaxed mb-3">
            L'Arc Club Pechbonnieu est né de la passion de quelques archers désireux de partager leur amour pour
            le tir à l'arc. Au fil des années, le club s'est développé et structuré, accueillant toujours plus de
            membres et s'impliquant activement dans les compétitions régionales et nationales.
          </p>
          <p className="text-stone-200 leading-relaxed">
            Ancré dans les traditions du tir à l'arc tout en restant ouvert aux évolutions du sport, le club
            cultive un esprit de transmission et de partage entre générations d'archers.
          </p>
        </section>

        {/* Équipe */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Le Bureau</h2>
          <p className="text-stone-200 leading-relaxed mb-6">
            Le club est animé par un bureau bénévole investi, qui assure au quotidien la bonne marche de
            l'association : organisation des séances, gestion des licences, préparation des compétitions
            et accueil des nouveaux membres. Leur engagement est le moteur du club.
          </p>
          <div className="flex flex-wrap gap-2">
            {[
              { name: 'Benoît', role: 'Président' },
              { name: 'Marc' },
              { name: 'Michel' },
              { name: 'Julien' },
              { name: 'Erwann' },
              { name: 'Cécile' },
              { name: 'Paul' },
              { name: 'Audré' },
              { name: 'Arnaud' },
            ].map(({ name, role }) => (
              <div
                key={name}
                className="flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2"
              >
                <div className="w-7 h-7 rounded-full bg-[#1a4a7c] flex items-center justify-center text-[#ffd700] font-bold text-xs shrink-0">
                  {name[0]}
                </div>
                <span className="text-white font-medium text-sm">{name}</span>
                {role && (
                  <span className="text-[#3cb371] text-xs font-semibold">{role}</span>
                )}
              </div>
            ))}
          </div>
        </section>

        {/* Installations */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Nos Installations</h2>
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div>
              <p className="text-stone-200 leading-relaxed mb-4">
                L'Arc Club Pechbonnieu dispose d'installations adaptées à la pratique du tir à l'arc :
              </p>
              <ul className="space-y-4">
                {[
                  'Un pas de tir extérieur de onze cibles (10 à 70m) situé au 47 chemin de Labastidole à Pechbonnieu',
                  'Un jeu d\'arc traditionnel pour la pratique du Beursault',
                  'Un accès au gymnase de la communauté de communes des Coteaux de Bellevue avec 8 cibles mobiles',
                ].map((text, i) => (
                  <li key={i} className="flex items-start gap-3 text-stone-200 text-sm">
                    <MapPin size={16} className="text-[#ffd700] shrink-0 mt-0.5" />
                    <span>{text}</span>
                  </li>
                ))}
              </ul>
            </div>
            <div className="rounded-xl overflow-hidden">
              <img
                src="https://images.pexels.com/photos/8107224/pexels-photo-8107224.jpeg?auto=compress&cs=tinysrgb&w=800"
                alt="Installations du club"
                className="w-full h-64 object-cover"
              />
            </div>
          </div>
        </section>

        {/* Valeurs */}
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-2xl font-bold text-[#ffd700] mb-6">Nos Valeurs</h2>
          <p className="text-stone-200 mb-4">À l'Arc Club Pechbonnieu, nous croyons en :</p>
          <ul className="space-y-3">
            {[
              "L'esprit sportif et le fair-play",
              "Le respect de la tradition et l'ouverture à l'innovation",
              "L'inclusion et l'accueil de tous, quel que soit le niveau",
            ].map((val) => (
              <li key={val} className="flex items-start gap-3 text-stone-200">
                <span className="text-[#ffd700] text-lg leading-none">›</span>
                <span>{val}</span>
              </li>
            ))}
          </ul>
        </section>

      </div>
    </div>
  );
}