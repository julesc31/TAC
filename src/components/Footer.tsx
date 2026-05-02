import { MapPin, Mail, Phone } from 'lucide-react';
import type { Page } from '../App';

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

interface FooterProps {
  navigate: (page: Page) => void;
}

export default function Footer({ navigate }: FooterProps) {
  return (
    <footer className="bg-[#0a2744] border-t border-white/10 text-stone-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {/* Brand */}
          <div>
            <div className="mb-4">
              <div className="text-white font-bold text-lg">Arc Club Pechbonnieu</div>
              <div className="text-[#ffd700] text-sm font-medium">Section tir à l'arc · Foyer Rural de Pechbonnieu</div>
            </div>
            <p className="text-sm text-stone-400 leading-relaxed mb-5">
              Section tir à l'arc du Foyer Rural de Pechbonnieu, affiliée à la FFTA.
              Ouvert à tous — loisir ou compétition, débutants comme confirmés.
            </p>
            <div className="flex gap-3">
              <a
                href="https://www.facebook.com"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Facebook"
                className="w-9 h-9 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-stone-400 hover:text-white hover:bg-[#1877f2] hover:border-[#1877f2] transition-all duration-200"
              >
                <FacebookIcon size={18} />
              </a>
              <a
                href="https://www.instagram.com"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Instagram"
                className="w-9 h-9 rounded-full bg-white/10 border border-white/20 flex items-center justify-center text-stone-400 hover:text-white hover:bg-[#e1306c] hover:border-[#e1306c] transition-all duration-200"
              >
                <InstagramIcon size={18} />
              </a>
            </div>
          </div>

          {/* Navigation */}
          <div>
            <h3 className="text-white font-semibold mb-4">Navigation</h3>
            <ul className="space-y-2 text-sm">
              {[
                { label: 'Accueil', page: 'accueil' as Page },
                { label: 'Le Club', page: 'club' as Page },
                { label: 'Album photo', page: 'album-photo' as Page },
                { label: 'Disciplines', page: 'disciplines' as Page },
                { label: 'Rejoindre & Contact', page: 'rejoindre' as Page },
                { label: 'Calendrier', page: 'calendrier' as Page },
                { label: 'Traditions des archers', page: 'traditions' as Page },
              ].map(({ label, page }) => (
                <li key={page}>
                  <button
                    onClick={() => navigate(page)}
                    className="hover:text-[#ffd700] transition-colors text-left"
                  >
                    {label}
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="text-white font-semibold mb-4">Contact</h3>
            <ul className="space-y-3 text-sm">
              <li className="flex items-start gap-2">
                <MapPin size={16} className="text-[#ffd700] mt-0.5 shrink-0" />
                <span>47 chemin de Labastidole<br />31140 Pechbonnieu</span>
              </li>
              <li className="flex items-start gap-2">
                <MapPin size={16} className="text-[#ffd700] mt-0.5 shrink-0" />
                <span>Gymnase Colette Besson<br />31140 Pechbonnieu</span>
              </li>
              <li className="flex items-center gap-2">
                <Phone size={16} className="text-[#ffd700] shrink-0" />
                <span>05 61 09 73 32</span>
              </li>
              <li className="flex items-center gap-2">
                <Mail size={16} className="text-[#ffd700] shrink-0" />
                <a
                  href="mailto:pechbonnieu.tiralarc@gmail.com"
                  className="hover:text-[#ffd700] transition-colors break-all"
                >
                  pechbonnieu.tiralarc@gmail.com
                </a>
              </li>
            </ul>
          </div>
        </div>

        <div className="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-stone-500">
          <p>&copy; {new Date().getFullYear()} Arc Club Pechbonnieu. Tous droits réservés.</p>
          <div className="flex gap-4">
            <button onClick={() => navigate('mentions-legales')} className="hover:text-[#ffd700] transition-colors">
              Mentions légales
            </button>
            <button onClick={() => navigate('politique-confidentialite')} className="hover:text-[#ffd700] transition-colors">
              Politique de confidentialité
            </button>
            <button onClick={() => navigate('admin')} className="hover:text-stone-400 transition-colors text-stone-700">
              Administration
            </button>
          </div>
        </div>
      </div>
    </footer>
  );
}
