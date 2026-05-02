import { useState, useEffect, useRef } from 'react';
import { MapPin, Clock, Mail, Phone, Send, CheckCircle, AlertCircle } from 'lucide-react';

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

    const redIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41],
    });

    const blueIcon = L.icon({
      iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
      shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      shadowSize: [41, 41],
    });

    L.marker([43.707341, 1.458391], { icon: redIcon })
      .addTo(map)
      .bindPopup('Terrain pour le tir en extérieur (parking du cimetière)');

    L.marker([43.706075, 1.462986], { icon: blueIcon })
      .addTo(map)
      .bindPopup('Gymnase Colette Besson (tir en salle)');
  }, []);

  return (
    <section className="bg-white/10 border border-white/20 rounded-2xl overflow-hidden">
      <div className="p-4 border-b border-white/10">
        <h2 className="text-xl font-bold text-[#ffd700]">Plan d'accès</h2>
      </div>
      <div ref={mapRef} style={{ height: '320px', width: '100%' }} />
      <div className="flex flex-col sm:flex-row gap-2 px-4 py-3 border-t border-white/10">
        <span className="flex items-center gap-2 text-sm text-stone-300">
          <span className="w-3 h-3 rounded-full bg-red-500 shrink-0" />
          Terrain de tir extérieur (parking du cimetière)
        </span>
        <span className="flex items-center gap-2 text-sm text-stone-300 sm:ml-6">
          <span className="w-3 h-3 rounded-full bg-blue-500 shrink-0" />
          Gymnase Colette Besson (tir en salle)
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

export default function ContactPage() {
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
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-4">Contact</h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Une question ? Nous sommes là pour vous répondre.
          </p>
        </div>
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">

          {/* Coordonnées */}
          <div className="space-y-6">
            {/* Adresses */}
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

            {/* Horaires */}
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

            {/* Carte */}
            <LeafletMap />
          </div>

          {/* Formulaire + Réseaux sociaux */}
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
                    <input
                      type="text" name="nom" required value={form.nom} onChange={handleChange}
                      placeholder="Votre nom"
                      className="w-full px-4 py-2.5 rounded-xl border border-white/20 bg-white/5 text-white placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 text-sm"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-stone-300 mb-1.5">Email *</label>
                    <input
                      type="email" name="email" required value={form.email} onChange={handleChange}
                      placeholder="votre@email.com"
                      className="w-full px-4 py-2.5 rounded-xl border border-white/20 bg-white/5 text-white placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 text-sm"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-stone-300 mb-1.5">Sujet</label>
                    <select
                      name="sujet" value={form.sujet} onChange={handleChange}
                      className="w-full px-4 py-2.5 rounded-xl border border-white/20 bg-[#0a2744] text-white focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 text-sm"
                    >
                      <option value="information">Demande d'information</option>
                      <option value="inscription">Inscription</option>
                      <option value="autre">Autre</option>
                    </select>
                  </div>
                  <div>
                    <label className="block text-sm font-medium text-stone-300 mb-1.5">Message *</label>
                    <textarea
                      name="message" required value={form.message} onChange={handleChange} rows={5}
                      placeholder="Votre message..."
                      className="w-full px-4 py-2.5 rounded-xl border border-white/20 bg-white/5 text-white placeholder-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 text-sm resize-none"
                    />
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

            {/* Réseaux sociaux */}
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
      </div>
    </div>
  );
}
