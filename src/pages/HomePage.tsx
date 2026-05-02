import { useEffect, useState } from 'react';
import { ArrowRight, ChevronDown, Calendar, Megaphone, Trophy, Users, Loader2, AlertCircle } from 'lucide-react';
import type { Page } from '../App';
import { api, type NewsItem } from '../lib/api';

interface HomePageProps {
  navigate: (page: Page) => void;
}

const tagConfig: Record<string, { color: string; Icon: React.ElementType }> = {
  'Entraînement': { color: 'bg-sky-500/20 text-sky-300 border-sky-500/30', Icon: Calendar },
  'Compétition':  { color: 'bg-amber-500/20 text-amber-300 border-amber-500/30', Icon: Trophy },
  'Annonce':      { color: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30', Icon: Megaphone },
  'Club':         { color: 'bg-rose-500/20 text-rose-300 border-rose-500/30', Icon: Users },
};

const defaultConfig = { color: 'bg-stone-500/20 text-stone-300 border-stone-500/30', Icon: Calendar };

function formatDate(iso: string): string {
  return new Date(iso).toLocaleDateString('fr-FR', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  });
}

export default function HomePage({ navigate }: HomePageProps) {
  const [news, setNews] = useState<NewsItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);

  useEffect(() => {
    api.news.list(6).then(({ data }) => {
      setNews(data ?? []);
      setLoading(false);
    }).catch(() => {
      setError(true);
      setLoading(false);
    });
  }, []);

  return (
    <div>
      {/* Hero */}
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center bg-no-repeat"
          style={{
            backgroundImage:
              'url(https://images.pexels.com/photos/3621104/pexels-photo-3621104.jpeg?auto=compress&cs=tinysrgb&w=1600)',
          }}
        />
        <div className="absolute inset-0 bg-gradient-to-b from-[#0a2744]/80 via-[#0a2744]/60 to-[#0a2744]/90" />

        <div className="relative z-10 text-center px-4 max-w-4xl mx-auto">
          <div className="inline-flex items-center gap-2 bg-[#ffd700]/20 border border-[#ffd700]/40 rounded-full px-4 py-1.5 mb-6">
            <span className="w-2 h-2 rounded-full bg-[#ffd700] animate-pulse" />
            <span className="text-[#ffd700] text-sm font-medium">Foyer Rural de Pechbonnieu · Section Tir à l'Arc</span>
          </div>
          <h1 className="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
            Arc Club<br />
            <span className="text-[#ffd700]">Pechbonnieu</span>
          </h1>
          <p className="text-stone-200 text-lg sm:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Actualités, événements et vie du club — tout ce qui se passe chez nous.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              onClick={() => navigate('rejoindre')}
              className="inline-flex items-center gap-2 bg-[#ffd700] hover:bg-yellow-300 text-[#0a2744] font-bold px-8 py-3.5 rounded-full transition-all duration-200 hover:shadow-lg hover:shadow-[#ffd700]/25 hover:-translate-y-0.5"
            >
              Nous rejoindre
              <ArrowRight size={18} />
            </button>
            <button
              onClick={() => navigate('contact')}
              className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold px-8 py-3.5 rounded-full border border-white/20 transition-all duration-200 hover:-translate-y-0.5"
            >
              Nous contacter
            </button>
          </div>
        </div>

        <div className="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
          <ChevronDown size={28} className="text-white/60" />
        </div>
      </section>

      {/* Actualités */}
      <section className="py-20 bg-[#0a2744]">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between mb-10">
            <h2 className="text-3xl font-bold text-white">
              Actualités <span className="text-[#ffd700]">du club</span>
            </h2>
            <button
              onClick={() => navigate('calendrier')}
              className="hidden sm:inline-flex items-center gap-1.5 text-sm text-[#ffd700] hover:text-yellow-300 font-medium transition-colors"
            >
              Voir le calendrier <ArrowRight size={14} />
            </button>
          </div>

          {loading && (
            <div className="flex justify-center py-16">
              <Loader2 size={32} className="text-[#ffd700] animate-spin" />
            </div>
          )}

          {error && (
            <div className="flex items-center gap-3 bg-red-500/10 border border-red-500/30 rounded-2xl px-6 py-5 text-red-300 text-sm">
              <AlertCircle size={18} className="shrink-0" />
              Impossible de charger les actualités pour le moment.
            </div>
          )}

          {!loading && !error && news.length === 0 && (
            <p className="text-stone-400 text-center py-12">Aucune actualité pour le moment.</p>
          )}

          {!loading && !error && news.length > 0 && (
            <div className="space-y-5">
              {news.map((item) => {
                const { color, Icon } = tagConfig[item.tag] ?? defaultConfig;
                return (
                  <article
                    key={item.id}
                    className="bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 rounded-2xl p-6 transition-all duration-200 group cursor-default"
                  >
                    <div className="flex items-start gap-4">
                      <div className="flex-shrink-0 w-11 h-11 rounded-xl bg-[#1a4a7c] flex items-center justify-center mt-0.5">
                        <Icon size={20} className="text-[#ffd700]" />
                      </div>
                      <div className="flex-1 min-w-0">
                        <div className="flex flex-wrap items-center gap-2 mb-2">
                          <span className={`text-xs font-semibold px-2.5 py-0.5 rounded-full border ${color}`}>
                            {item.tag}
                          </span>
                          <span className="text-stone-500 text-xs">{formatDate(item.published_at)}</span>
                        </div>
                        <h3 className="text-white font-semibold text-base mb-1.5 group-hover:text-[#ffd700] transition-colors">
                          {item.title}
                        </h3>
                        <p className="text-stone-400 text-sm leading-relaxed">{item.body}</p>
                      </div>
                    </div>
                  </article>
                );
              })}
            </div>
          )}

          <div className="mt-8 text-center sm:hidden">
            <button
              onClick={() => navigate('calendrier')}
              className="inline-flex items-center gap-1.5 text-sm text-[#ffd700] hover:text-yellow-300 font-medium transition-colors"
            >
              Voir le calendrier <ArrowRight size={14} />
            </button>
          </div>
        </div>
      </section>

      {/* CTA banner */}
      <section className="relative py-24 overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{
            backgroundImage:
              'url(https://images.pexels.com/photos/1263986/pexels-photo-1263986.jpeg?auto=compress&cs=tinysrgb&w=1600)',
          }}
        />
        <div className="absolute inset-0 bg-[#0a2744]/78" />
        <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h2 className="text-3xl sm:text-4xl font-bold text-white mb-6">
            Prêt à décocher votre première flèche ?
          </h2>
          <p className="text-stone-200 text-lg leading-relaxed mb-8 max-w-2xl mx-auto">
            Contactez-nous pour une séance découverte ou inscrivez-vous dès maintenant.
            Tout le matériel est mis à disposition pour les débutants.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              onClick={() => navigate('rejoindre')}
              className="inline-flex items-center gap-2 bg-[#ffd700] hover:bg-yellow-300 text-[#0a2744] font-bold px-8 py-3.5 rounded-full transition-all duration-200 hover:shadow-lg hover:shadow-[#ffd700]/25"
            >
              Nous rejoindre
              <ArrowRight size={18} />
            </button>
            <button
              onClick={() => navigate('contact')}
              className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white font-semibold px-8 py-3.5 rounded-full border border-white/20 transition-all duration-200"
            >
              Nous contacter
            </button>
          </div>
        </div>
      </section>
    </div>
  );
}
