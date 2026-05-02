import { useState, useEffect, useRef } from 'react';
import { CheckCircle, ArrowRight, Send, AlertCircle, MapPin, Clock, Mail, Phone } from 'lucide-react';
import type { Page } from '../App';

interface Props {
  navigate: (page: Page) => void;
}

declare const L: any;

function LeafletMap() {
  const mapRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (!mapRef.current || typeof L === 'undefined') return;
    if ((mapRef.current as any)._leaflet_id) return;

    const map = L.map(mapRef.current).setView([43.70684, 1.459539], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
    }).addTo(map);

    const makeIcon = (color: string) => L.icon({
      iconUrl: `https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-${color}.png`,
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41], iconAnchor: [12, 41], popupAnchor: [1, -34], shadowSize: [41, 41],
    });

    L.marker([43.707341, 1.458391], { icon: makeIcon('red') })
      .addTo(map).bindPopup('Terrain pour le tir en extérieur (parking du cimetière)');
    L.marker([43.706075, 1.462986], { icon: makeIcon('blue') })
      .addTo(map).bindPopup('Gymnase Colette Besson (tir en salle)');
  }, []);

  return (
    <section className="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
      <div className="p-4 border-b border-white/10">
        <h2 className="text-xl font-bold text-[#ffd700]">Plan d'accès</h2>
      </div>
      <div ref={mapRef} style={{ height: '280px', width: '100%' }} />
      <div className="flex flex-col sm:flex-row gap-2 px-4 py-3 border-t border-white/10">
        <span className="flex items-center gap-2 text-sm text-stone-300">
          <span className="w-3 h-3 rounded-full bg-red-500 shrink-0" />
          Terrain extérieur (parking du cimetière)
        </span>
        <span className="flex items-center gap-2 text-sm text-stone-300 sm:ml-6">
          <span className="w-3 h-3 rounded-full bg-blue-500 shrink-0" />
          Gymnase Colette Besson (salle)
        </span>
      </div>
    </section>
  );
}

function FacebookIcon({ size = 20 }: { size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="currentColor">
      <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
    </svg>
  );
}

function InstagramIcon({ size = 20 }: { size?: number }) {
  return (
    <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round">
      <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
      <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
      <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
    </svg>
  );
}

function TabRejoindre({ navigate }: { navigate: (page: Page) => void }) {
  const [form, setForm] = useState({
    categorie: '', nom: '', age: '', email: '', telephone: '',
    typeArc: '', niveau: '', commentaires: '',
  });
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    await new Promise((r) => setTimeout(r, 800));
    setLoading(false);
    setSubmitted(true);
  };

  const inputCls = 'w-full px-4 py-2.5 rounded-xl border border-white/20 bg-white/5 text-white placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 text-sm';
  const selectCls = 'w-full px-4 py-2.5 rounded-xl border border-white/20 bg-[#0a2744] text-white focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 text-sm';

  return (
    <div className="space-y-8">
      {/* Horaires */}
      <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
        <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Horaires des séances</h2>
        <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
          {[
            { day: 'Lundi', hours: '21h – 23h', public: 'Adultes', note: 'Salle ou extérieur selon saison' },
            { day: 'Mercredi', hours: '16h – 17h30', public: 'Enfants et ados', note: 'Encadrement jeunesse' },
            { day: 'Samedi', hours: '09h – 12h', public: 'Tous', note: 'Séance ouverte à tous les niveaux' },
          ].map(({ day, hours, public: pub, note }) => (
            <div key={day} className="bg-[#1a4a7c]/50 border border-white/20 rounded-xl p-5 text-center">
              <div className="text-[#ffd700] font-bold text-lg mb-1">{day}</div>
              <div className="text-white font-semibold text-base mb-1">{hours}</div>
              <div className="text-stone-300 text-sm mb-2">{pub}</div>
              <div className="text-stone-400 text-xs">{note}</div>
            </div>
          ))}
        </div>
      </section>

      {/* Infos pratiques */}
      <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
        <h2 className="text-2xl font-bold text-[#ffd700] mb-4">Informations pratiques</h2>
        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
          {[
            'Le club prête le matériel pour les débutants',
            'Pratique loisir possible sans licence FFTA — cotisation club uniquement',
            "Séances d'initiation disponibles avant inscription",
            'Licence FFTA optionnelle pour participer aux compétitions officielles',
            'Encadrement par des moniteurs diplômés FFTA',
            "Pratique possible dès l'âge de 7 ans",
          ].map((item) => (
            <div key={item} className="flex items-start gap-3 p-3 bg-white/5 rounded-xl">
              <CheckCircle size={16} className="text-[#3cb371] shrink-0 mt-0.5" />
              <span className="text-stone-200 text-sm">{item}</span>
            </div>
          ))}
        </div>
      </section>

      {/* Étapes */}
      <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
        <h2 className="text-2xl font-bold text-[#ffd700] mb-6">Comment s'inscrire ?</h2>
        <div className="grid grid-cols-1 sm:grid-cols-4 gap-4">
          {[
            { step: '1', title: 'Préinscription', desc: 'Remplissez le formulaire ci-dessous pour manifester votre intérêt.' },
            { step: '2', title: 'Séance découverte', desc: "Venez essayer gratuitement lors d'une de nos séances d'initiation." },
            { step: '3', title: "Dossier d'inscription", desc: "Remplissez le dossier d'adhésion et réglez la cotisation." },
            { step: '4', title: 'Licence FFTA (optionnelle)', desc: 'Pour participer aux compétitions officielles, finalisez votre licence sur le site de la FFTA.' },
          ].map(({ step, title, desc }) => (
            <div key={step} className="bg-[#1a4a7c]/50 border border-white/20 rounded-xl p-5 text-center">
              <div className="w-10 h-10 bg-[#ffd700] text-[#0a2744] rounded-full flex items-center justify-center text-lg font-bold mx-auto mb-3">
                {step}
              </div>
              <h3 className="font-semibold text-white mb-2 text-sm">{title}</h3>
              <p className="text-stone-400 text-xs leading-relaxed">{desc}</p>
            </div>
          ))}
        </div>
      </section>

      {/* Formulaire */}
      <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
        <h2 className="text-2xl font-bold text-[#ffd700] mb-2">Formulaire de préinscription</h2>
        <p className="text-stone-300 text-sm mb-8">
          Remplissez ce formulaire pour manifester votre intérêt. Nous vous contacterons pour vous donner la suite.
        </p>

        {submitted ? (
          <div className="text-center py-10">
            <CheckCircle size={48} className="text-[#3cb371] mx-auto mb-4" />
            <h3 className="text-xl font-bold text-white mb-2">Demande envoyée !</h3>
            <p className="text-stone-300 mb-6 text-sm">
              Nous avons bien reçu votre demande de préinscription. Nous vous contacterons sous 48h.
            </p>
            <button
              onClick={() => navigate('accueil')}
              className="inline-flex items-center gap-2 bg-[#ffd700] text-[#0a2744] font-bold px-6 py-3 rounded-full hover:bg-yellow-300 transition-colors"
            >
              Retour à l'accueil <ArrowRight size={16} />
            </button>
          </div>
        ) : (
          <form onSubmit={handleSubmit} className="space-y-5 max-w-2xl">
            <div>
              <label className="block text-sm font-medium text-stone-300 mb-1.5">Catégorie *</label>
              <select name="categorie" required value={form.categorie} onChange={handleChange} className={selectCls}>
                <option value="">Sélectionner...</option>
                <option value="jeune">Jeune (moins de 18 ans)</option>
                <option value="adulte">Adulte</option>
                <option value="senior">Senior</option>
              </select>
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Nom complet *</label>
                <input type="text" name="nom" required value={form.nom} onChange={handleChange} placeholder="Votre nom" className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Âge</label>
                <input type="number" name="age" value={form.age} onChange={handleChange} placeholder="Votre âge" className={inputCls} />
              </div>
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Email *</label>
                <input type="email" name="email" required value={form.email} onChange={handleChange} placeholder="votre@email.com" className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Téléphone</label>
                <input type="tel" name="telephone" value={form.telephone} onChange={handleChange} placeholder="06 xx xx xx xx" className={inputCls} />
              </div>
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Type d'arc souhaité</label>
                <select name="typeArc" value={form.typeArc} onChange={handleChange} className={selectCls}>
                  <option value="">Sélectionner...</option>
                  <option value="recurve">Arc recurve (classique)</option>
                  <option value="compound">Arc à poulies (compound)</option>
                  <option value="traditionnel">Arc traditionnel / longbow</option>
                  <option value="pas-de-preference">Pas de préférence</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Niveau actuel</label>
                <select name="niveau" value={form.niveau} onChange={handleChange} className={selectCls}>
                  <option value="">Sélectionner...</option>
                  <option value="debutant">Débutant (jamais pratiqué)</option>
                  <option value="initie">Initié (quelques séances)</option>
                  <option value="intermediaire">Intermédiaire (1–3 ans)</option>
                  <option value="confirme">Confirmé (3+ ans)</option>
                  <option value="competition">Compétiteur</option>
                </select>
              </div>
            </div>
            <div>
              <label className="block text-sm font-medium text-stone-300 mb-1.5">Commentaires (optionnel)</label>
              <textarea name="commentaires" value={form.commentaires} onChange={handleChange} rows={3} placeholder="Questions, informations complémentaires..." className={`${inputCls} resize-none`} />
            </div>
            <div className="flex items-start gap-2 text-xs text-stone-400">
              <AlertCircle size={14} className="shrink-0 mt-0.5 text-[#ffd700]" />
              <span>
                En soumettant ce formulaire, vous acceptez que vos données soient utilisées pour traiter
                votre demande d'inscription. Voir notre{' '}
                <button type="button" onClick={() => navigate('politique-confidentialite')} className="text-[#ffd700] hover:underline">
                  politique de confidentialité
                </button>.
              </span>
            </div>
            <button
              type="submit"
              disabled={loading}
              className="inline-flex items-center justify-center gap-2 bg-[#ffd700] hover:bg-yellow-300 disabled:opacity-60 text-[#0a2744] font-bold px-8 py-3.5 rounded-full transition-colors"
            >
              {loading ? 'Envoi en cours...' : <><Send size={16} /> Envoyer ma préinscription</>}
            </button>
          </form>
        )}
      </section>
    </div>
  );
}

function TabContact({ navigate }: { navigate: (page: Page) => void }) {
  const [form, setForm] = useState({ nom: '', email: '', sujet: 'information', message: '' });
  const [submitted, setSubmitted] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement | HTMLTextAreaElement>) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    await new Promise((r) => setTimeout(r, 800));
    setLoading(false);
    setSubmitted(true);
  };

  const inputCls = 'w-full px-4 py-2.5 rounded-xl border border-white/20 bg-white/5 text-white placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 text-sm';

  return (
    <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
      {/* Colonne gauche : coordonnées + horaires + carte */}
      <div className="space-y-6">
        <section className="bg-white/10 border border-white/20 rounded-2xl p-6">
          <h2 className="text-xl font-bold text-[#ffd700] mb-4">Nos Coordonnées</h2>
          <div className="space-y-5">
            <div className="flex items-start gap-3">
              <MapPin size={18} className="text-[#ffd700] shrink-0 mt-0.5" />
              <div>
                <div className="text-white font-semibold text-sm mb-0.5">Tir extérieur</div>
                <div className="text-stone-300 text-sm">47 chemin de Labastidole<br />31140 Pechbonnieu<br /><span className="text-stone-400">(Parking du cimetière)</span></div>
              </div>
            </div>
            <div className="flex items-start gap-3">
              <MapPin size={18} className="text-[#ffd700] shrink-0 mt-0.5" />
              <div>
                <div className="text-white font-semibold text-sm mb-0.5">Tir en salle</div>
                <div className="text-stone-300 text-sm">Gymnase Colette Besson<br />31140 Pechbonnieu<br /><span className="text-stone-400">(À côté du collège)</span></div>
              </div>
            </div>
            <div className="flex items-start gap-3">
              <Phone size={18} className="text-[#ffd700] shrink-0 mt-0.5" />
              <div>
                <div className="text-white font-semibold text-sm mb-0.5">Téléphone</div>
                <div className="text-stone-300 text-sm">05 61 09 73 32<br /><span className="text-stone-400">(Foyer rural ESCALE de Pechbonnieu)</span></div>
              </div>
            </div>
            <div className="flex items-start gap-3">
              <Mail size={18} className="text-[#ffd700] shrink-0 mt-0.5" />
              <div>
                <div className="text-white font-semibold text-sm mb-0.5">Email</div>
                <a href="mailto:pechbonnieu.tiralarc@gmail.com" className="text-[#ffd700] hover:text-yellow-300 text-sm transition-colors">
                  pechbonnieu.tiralarc@gmail.com
                </a>
              </div>
            </div>
          </div>
        </section>

        <section className="bg-white/10 border border-white/20 rounded-2xl p-6">
          <div className="flex items-center gap-2 mb-4">
            <Clock size={18} className="text-[#ffd700]" />
            <h2 className="text-xl font-bold text-[#ffd700]">Horaires d'ouverture</h2>
          </div>
          <ul className="space-y-3">
            {[
              { day: 'Lundi', hours: '21h – 23h', public: 'Adultes' },
              { day: 'Mercredi', hours: '16h – 17h30', public: 'Enfants et ados' },
              { day: 'Samedi', hours: '09h – 12h', public: 'Tous' },
            ].map(({ day, hours, public: pub }) => (
              <li key={day} className="grid grid-cols-3 items-center py-2 border-b border-white/10 last:border-0">
                <span className="text-white font-medium text-sm">{day}</span>
                <span className="text-[#ffd700] font-semibold text-sm text-center">{hours}</span>
                <span className="text-stone-400 text-xs text-right">{pub}</span>
              </li>
            ))}
          </ul>
        </section>

        <LeafletMap />
      </div>

      {/* Colonne droite : formulaire + réseaux */}
      <div className="space-y-6">
        <section className="bg-white/10 border border-white/20 rounded-2xl p-8">
          <h2 className="text-xl font-bold text-[#ffd700] mb-6">Formulaire de contact</h2>

          {submitted ? (
            <div className="text-center py-10">
              <CheckCircle size={48} className="text-[#3cb371] mx-auto mb-4" />
              <h3 className="text-xl font-bold text-white mb-2">Message envoyé !</h3>
              <p className="text-stone-300 text-sm">Nous vous répondrons dans les meilleurs délais.</p>
            </div>
          ) : (
            <form onSubmit={handleSubmit} className="space-y-5">
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Nom *</label>
                <input type="text" name="nom" required value={form.nom} onChange={handleChange} placeholder="Votre nom" className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Email *</label>
                <input type="email" name="email" required value={form.email} onChange={handleChange} placeholder="votre@email.com" className={inputCls} />
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Sujet</label>
                <select
                  name="sujet" value={form.sujet} onChange={handleChange}
                  className="w-full px-4 py-2.5 rounded-xl border border-white/20 bg-[#0a2744] text-white focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 text-sm"
                >
                  <option value="information">Demande d'information</option>
                  <option value="inscription">Inscription</option>
                  <option value="autre">Autre</option>
                </select>
              </div>
              <div>
                <label className="block text-sm font-medium text-stone-300 mb-1.5">Message *</label>
                <textarea name="message" required value={form.message} onChange={handleChange} rows={5} placeholder="Votre message..." className={`${inputCls} resize-none`} />
              </div>
              <div className="flex items-start gap-2 text-xs text-stone-400">
                <AlertCircle size={14} className="shrink-0 mt-0.5 text-[#ffd700]" />
                <span>Vos données sont utilisées uniquement pour traiter votre demande.</span>
              </div>
              <button
                type="submit"
                disabled={loading}
                className="w-full flex items-center justify-center gap-2 bg-[#ffd700] hover:bg-yellow-300 disabled:opacity-60 text-[#0a2744] font-bold py-3.5 rounded-xl transition-colors"
              >
                {loading ? 'Envoi en cours...' : <><Send size={16} /> Envoyer</>}
              </button>
            </form>
          )}
        </section>

        <section className="bg-white/10 border border-white/20 rounded-2xl p-6">
          <h2 className="text-xl font-bold text-[#ffd700] mb-3">Suivez-nous</h2>
          <p className="text-stone-300 text-sm mb-4">Restez connectés pour nos dernières actualités !</p>
          <div className="flex gap-3">
            <a
              href="https://www.facebook.com/"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 bg-[#1877f2]/20 border border-[#1877f2]/40 text-[#1877f2] hover:bg-[#1877f2] hover:text-white px-4 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium"
            >
              <FacebookIcon size={17} />
              Facebook
            </a>
            <a
              href="https://www.instagram.com/"
              target="_blank"
              rel="noopener noreferrer"
              className="flex items-center gap-2 bg-pink-500/20 border border-pink-500/40 text-pink-400 hover:bg-[#e1306c] hover:border-[#e1306c] hover:text-white px-4 py-2.5 rounded-xl transition-all duration-200 text-sm font-medium"
            >
              <InstagramIcon size={17} />
              Instagram
            </a>
          </div>
        </section>
      </div>
    </div>
  );
}

export default function RejoindreePage({ navigate }: Props) {
  const [tab, setTab] = useState<'rejoindre' | 'contact'>('rejoindre');

  return (
    <div className="pt-16 lg:pt-20 bg-[#0a2744] min-h-screen">
      {/* Hero */}
      <div className="relative py-20 overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center opacity-25"
          style={{
            backgroundImage:
              'url(https://images.pexels.com/photos/8107224/pexels-photo-8107224.jpeg?auto=compress&cs=tinysrgb&w=1600)',
          }}
        />
        <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">Rejoindre & Contact</h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Inscrivez-vous au club ou prenez contact avec nous — nous sommes là pour vous accueillir.
          </p>
        </div>
      </div>

      {/* Onglets */}
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex gap-2 border-b border-white/10 mb-8">
          {([
            { key: 'rejoindre', label: 'Rejoindre le club' },
            { key: 'contact', label: 'Nous contacter' },
          ] as const).map(({ key, label }) => (
            <button
              key={key}
              onClick={() => setTab(key)}
              className={`px-6 py-3 text-sm font-semibold border-b-2 transition-colors -mb-px ${
                tab === key
                  ? 'border-[#ffd700] text-[#ffd700]'
                  : 'border-transparent text-stone-400 hover:text-stone-200'
              }`}
            >
              {label}
            </button>
          ))}
        </div>

        <div className="pb-20">
          {tab === 'rejoindre' ? <TabRejoindre navigate={navigate} /> : <TabContact navigate={navigate} />}
        </div>
      </div>
    </div>
  );
}
