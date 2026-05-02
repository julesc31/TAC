import { useState, useEffect } from 'react';
import { Menu, X } from 'lucide-react';
import type { Page } from '../App';

interface HeaderProps {
  currentPage: Page;
  navigate: (page: Page) => void;
}

const navLinks: { label: string; page: Page }[] = [
  { label: 'Accueil', page: 'accueil' },
  { label: 'Le Club', page: 'club' },
  { label: 'Disciplines', page: 'disciplines' },
  { label: 'Traditions', page: 'traditions' },
  { label: 'Album photo', page: 'album-photo' },
  { label: 'Calendrier', page: 'calendrier' },
  { label: 'Rejoindre & Contact', page: 'rejoindre' },
];

export default function Header({ currentPage, navigate }: HeaderProps) {
  const [menuOpen, setMenuOpen] = useState(false);
  const [scrolled, setScrolled] = useState(false);

  useEffect(() => {
    const handleScroll = () => setScrolled(window.scrollY > 50);
    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const handleNav = (page: Page) => {
    navigate(page);
    setMenuOpen(false);
  };

  const isTransparent = currentPage === 'accueil' && !scrolled && !menuOpen;

  return (
    <header
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isTransparent
          ? 'bg-transparent'
          : 'bg-[#1a4a7c]/98 backdrop-blur-md shadow-lg border-b border-white/10'
      }`}
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 lg:h-20">
          <button
            onClick={() => handleNav('accueil')}
            className="flex items-center gap-3 group"
          >
            <div className="text-left">
              <div className="text-white font-bold text-lg leading-tight group-hover:text-[#ffd700] transition-colors">
                Arc Club Pechbonnieu
              </div>
              <div className="text-[#ffd700] text-xs leading-tight font-medium tracking-wide">
                Section tir à l'arc · Foyer Rural de Pechbonnieu
              </div>
            </div>
          </button>

          <nav className="hidden lg:flex items-center gap-1">
            {navLinks.map(({ label, page }) => (
              <button
                key={page}
                onClick={() => handleNav(page)}
                className={`px-3 py-2 text-sm font-medium rounded-full transition-all duration-200 ${
                  currentPage === page
                    ? 'text-[#ffd700] bg-white/10 shadow-sm'
                    : 'text-stone-200 hover:text-[#ffd700] hover:bg-white/10'
                }`}
              >
                {label}
              </button>
            ))}
          </nav>

          <button
            onClick={() => setMenuOpen(!menuOpen)}
            className="lg:hidden text-white p-2 rounded-lg hover:bg-white/10 transition-colors"
            aria-label="Menu"
          >
            {menuOpen ? <X size={22} /> : <Menu size={22} />}
          </button>
        </div>
      </div>

      {menuOpen && (
        <div className="lg:hidden bg-[#0a2744] border-t border-white/10">
          <nav className="px-4 py-3 flex flex-col gap-1">
            {navLinks.map(({ label, page }) => (
              <button
                key={page}
                onClick={() => handleNav(page)}
                className={`text-left px-4 py-3 text-sm font-medium rounded-xl transition-colors ${
                  currentPage === page
                    ? 'text-[#ffd700] bg-white/10'
                    : 'text-stone-200 hover:text-[#ffd700] hover:bg-white/10'
                }`}
              >
                {label}
              </button>
            ))}
          </nav>
        </div>
      )}
    </header>
  );
}
