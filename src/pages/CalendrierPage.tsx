import { useState, useEffect } from 'react';
import { ChevronLeft, ChevronRight, MapPin, X, RefreshCw } from 'lucide-react';

type EventType = 'competition' | 'entrainement' | '3d' | 'interne' | 'stage' | 'autre';

interface CalEvent {
  id: string;
  date: string; // YYYY-MM-DD
  dateEnd?: string;
  title: string;
  location: string;
  type: EventType;
  desc?: string;
  allDay?: boolean;
}

const typeConfig: Record<EventType, { label: string; bg: string; text: string }> = {
  competition: { label: 'Compétition', bg: 'bg-red-500', text: 'text-white' },
  entrainement: { label: 'Entraînement', bg: 'bg-[#1a4a7c]', text: 'text-white' },
  '3d': { label: '3D', bg: 'bg-emerald-500', text: 'text-white' },
  interne: { label: 'Interne', bg: 'bg-[#ffd700]', text: 'text-[#0a2744]' },
  stage: { label: 'Stage', bg: 'bg-sky-500', text: 'text-white' },
  autre: { label: 'Autre', bg: 'bg-stone-400', text: 'text-white' },
};

function guessType(title: string, allDay = true): EventType {
  const t = title.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

  // Parcours 3D / Morata
  if (t.includes('morata') || t.includes('3d') || t.includes('3 d') || t.includes('parcours') || t.includes('campagne')) return '3d';

  // Compétitions salle / extérieur / tir traditionnel
  if (
    t.includes('18m') || t.includes('24h') || t.includes('championnat') || t.includes('concours') ||
    t.includes('competition') || t.includes('compet') || t.includes('tac') || t.includes('soisson') ||
    t.includes('beursault') || t.includes('bouquet') || t.includes('fleches') || t.includes('flèches') ||
    t.includes('monpitol') || t.includes('muret') || t.includes('auch') || t.includes('fronton') ||
    t.includes('balma') || t.includes('blagnac') || t.includes('castelsarrasin') || t.includes('union') ||
    t.includes('cr jeunes') || t.includes('ranking') || t.includes('ffta')
  ) return 'competition';

  // Stages
  if (t.includes('stage')) return 'stage';

  // Événements internes / vie du club
  if (
    t.includes('ag') || t.includes('assemblee') || t.includes('reunion') || t.includes('bureau') ||
    t.includes('galette') || t.includes('voeux') || t.includes('forum') ||
    t.includes('inventaire') || t.includes('dechargement') || t.includes('mise a jour') ||
    t.includes('pas de gymnase') || t.includes('vacances') || t.includes('fete') || t.includes('noel') ||
    t.includes('interne') || t.includes('portes ouvertes')
  ) return 'interne';

  // Entraînements explicites
  if (
    t.includes('entrainement') || t.includes('reprise') || t.includes('outdoor') ||
    t.includes('indoor') || t.includes('seance') || t.includes('exterieur')
  ) return 'entrainement';

  // Les événements à heure précise (non journée entière) sont des entraînements par défaut
  if (!allDay) return 'entrainement';

  return 'autre';
}

function parseGoogleEvents(items: any[]): CalEvent[] {
  return items.map((item) => {
    const allDay = !!item.start?.date;
    const start = item.start?.date ?? item.start?.dateTime?.slice(0, 10) ?? '';
    // For timed events, end dateTime is exclusive but on same day or next; normalize to date only
    let end = item.end?.date ?? item.end?.dateTime?.slice(0, 10) ?? undefined;
    // For all-day multi-day events Google uses exclusive end date; keep as-is
    // For single timed events end == start date, so treat as same day
    if (end === start) end = undefined;
    const title = item.summary ?? 'Événement';
    return {
      id: item.id,
      date: start,
      dateEnd: end,
      title,
      location: item.location ?? 'Pechbonnieu',
      type: guessType(title, allDay),
      desc: item.description ?? undefined,
      allDay,
    };
  });
}

const DAYS = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
const MONTHS_FR = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];

function getDaysInMonth(year: number, month: number) {
  return new Date(year, month + 1, 0).getDate();
}

function getFirstDayOfMonth(year: number, month: number) {
  const d = new Date(year, month, 1).getDay();
  return d === 0 ? 6 : d - 1;
}

function fmt(date: Date) {
  return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}

export default function CalendrierPage() {
  const today = new Date();
  const [viewYear, setViewYear] = useState(today.getFullYear());
  const [viewMonth, setViewMonth] = useState(today.getMonth());
  const [selected, setSelected] = useState<CalEvent | null>(null);
  const [events, setEvents] = useState<CalEvent[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    async function fetchEvents() {
      setLoading(true);
      setError(null);
      try {
        // Fetch 12 months of events
        const timeMin = new Date(today.getFullYear() - 1, 0, 1).toISOString();
        const timeMax = new Date(today.getFullYear() + 2, 11, 31).toISOString();
        const url = `/api/calendar.php?timeMin=${encodeURIComponent(timeMin)}&timeMax=${encodeURIComponent(timeMax)}`;
        const res = await fetch(url);
        const data = await res.json();
        if (!res.ok) throw new Error(data?.error ?? `Erreur ${res.status}`);
        setEvents(parseGoogleEvents((data.items ?? []).filter((item: any) => !(item.summary ?? '').toLowerCase().startsWith('[privé]'))));
      } catch (e: any) {
        setError(e.message ?? 'Impossible de charger le calendrier.');
      } finally {
        setLoading(false);
      }
    }
    fetchEvents();
  }, []);

  const prevMonth = () => {
    if (viewMonth === 0) { setViewMonth(11); setViewYear(y => y - 1); }
    else setViewMonth(m => m - 1);
  };
  const nextMonth = () => {
    if (viewMonth === 11) { setViewMonth(0); setViewYear(y => y + 1); }
    else setViewMonth(m => m + 1);
  };
  const goToday = () => { setViewYear(today.getFullYear()); setViewMonth(today.getMonth()); };

  const daysInMonth = getDaysInMonth(viewYear, viewMonth);
  const firstDay = getFirstDayOfMonth(viewYear, viewMonth);
  const todayStr = fmt(today);

  const cells: (number | null)[] = [
    ...Array(firstDay).fill(null),
    ...Array.from({ length: daysInMonth }, (_, i) => i + 1),
  ];
  // Pad to complete the last week only (no trailing empty rows)
  while (cells.length % 7 !== 0) cells.push(null);

  const getEventsForDay = (day: number) => {
    const dateStr = `${viewYear}-${String(viewMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return events.filter(e => {
      if (!e.dateEnd || e.date === e.dateEnd) return e.date === dateStr;
      return dateStr >= e.date && dateStr < e.dateEnd;
    });
  };

  const upcomingEvents = events
    .filter(e => e.date >= todayStr)
    .sort((a, b) => a.date.localeCompare(b.date))
    .slice(0, 5);

  return (
    <div className="pt-16 lg:pt-20 bg-[#0a2744] min-h-screen">
      {/* Hero */}
      <div className="relative py-16 overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center opacity-20"
          style={{ backgroundImage: 'url(https://images.pexels.com/photos/3765179/pexels-photo-3765179.jpeg?auto=compress&cs=tinysrgb&w=1600)' }}
        />
        <div className="relative z-10 max-w-4xl mx-auto px-4 text-center">
          <h1 className="text-4xl sm:text-5xl font-bold text-white mb-3">Calendrier</h1>
          <p className="text-white/60 text-lg">Concours, compétitions, sorties 3D et événements du club</p>
        </div>
      </div>

      <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

        {/* Légende */}
        <div className="flex flex-wrap gap-2 mb-6">
          {Object.entries(typeConfig).filter(([k]) => k !== 'autre').map(([key, { label, bg, text }]) => (
            <span key={key} className={`inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full ${bg} ${text}`}>
              {label}
            </span>
          ))}
        </div>

        {error && (
          <div className="mb-6 bg-red-500/20 border border-red-500/40 rounded-xl p-4 text-red-300 text-sm flex items-center gap-3">
            <span className="flex-1">{error}</span>
          </div>
        )}

        <div className="grid grid-cols-1 xl:grid-cols-4 gap-6">
          {/* Calendrier principal */}
          <div className="xl:col-span-3 bg-white/10 border border-white/20 rounded-2xl overflow-hidden shadow-xl">
            {/* Header mois */}
            <div className="flex items-center justify-between px-6 py-4 bg-white/10 border-b border-white/10">
              <button onClick={prevMonth} className="p-2 text-white hover:bg-white/20 rounded-full transition-colors">
                <ChevronLeft size={20} />
              </button>
              <div className="flex items-center gap-4">
                <h2 className="text-lg font-bold text-white">
                  {MONTHS_FR[viewMonth]} {viewYear}
                </h2>
                <button
                  onClick={goToday}
                  className="text-xs font-semibold bg-[#ffd700] text-[#0a2744] px-3 py-1 rounded-full hover:bg-yellow-300 transition-colors"
                >
                  Aujourd'hui
                </button>
              </div>
              <button onClick={nextMonth} className="p-2 text-white hover:bg-white/20 rounded-full transition-colors">
                <ChevronRight size={20} />
              </button>
            </div>

            {/* Jours de la semaine */}
            <div className="grid grid-cols-7 border-b border-white/10">
              {DAYS.map(d => (
                <div key={d} className={`py-2 text-center text-xs font-semibold ${d === 'Sam' || d === 'Dim' ? 'text-red-400' : 'text-white/50'}`}>
                  {d}
                </div>
              ))}
            </div>

            {/* Grille */}
            {loading ? (
              <div className="flex items-center justify-center h-64 gap-3 text-white/40">
                <RefreshCw size={20} className="animate-spin" />
                <span className="text-sm">Chargement du calendrier…</span>
              </div>
            ) : (
              <div className="grid grid-cols-7 border-t border-l border-white/10">
                {cells.map((day, i) => {
                  if (day === null) return <div key={`empty-${i}`} className="border-r border-b border-white/10 min-h-[80px] bg-white/5" />;

                  const dateStr = `${viewYear}-${String(viewMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                  const dayEvents = getEventsForDay(day);
                  const isToday = dateStr === todayStr;
                  const colIndex = (firstDay + day - 1) % 7;
                  const isWeekend = colIndex === 5 || colIndex === 6;

                  return (
                    <div
                      key={day}
                      className={`min-h-[80px] p-1.5 flex flex-col border-r border-b border-white/10 transition-colors ${
                        isWeekend ? 'bg-white/5' : 'bg-transparent'
                      } hover:bg-white/10`}
                    >
                      <div className={`w-6 h-6 flex items-center justify-center rounded-full text-xs font-semibold mb-1 self-end ${
                        isToday ? 'bg-[#ffd700] text-[#0a2744] font-bold' : isWeekend ? 'text-red-400' : 'text-white/70'
                      }`}>
                        {day}
                      </div>
                      <div className="flex flex-col gap-0.5 overflow-hidden">
                        {dayEvents.slice(0, 3).map((ev, idx) => {
                          const cfg = typeConfig[ev.type];
                          return (
                            <button
                              key={idx}
                              onClick={() => setSelected(ev)}
                              className={`text-left text-[11px] px-1.5 py-0.5 rounded font-medium truncate w-full leading-tight ${cfg.bg} ${cfg.text} hover:opacity-80 transition-opacity`}
                            >
                              {ev.title}
                            </button>
                          );
                        })}
                        {dayEvents.length > 3 && (
                          <span className="text-[10px] text-white/40 px-1">+{dayEvents.length - 3}</span>
                        )}
                      </div>
                    </div>
                  );
                })}
              </div>
            )}
          </div>

          {/* Sidebar */}
          <div className="space-y-4">
            <div className="bg-white/10 border border-white/20 rounded-2xl p-5">
              <h3 className="font-bold text-[#ffd700] mb-4 text-sm uppercase tracking-wide">Prochains événements</h3>
              {loading ? (
                <div className="flex items-center gap-2 text-white/40 text-sm">
                  <RefreshCw size={14} className="animate-spin" /> Chargement…
                </div>
              ) : upcomingEvents.length === 0 ? (
                <p className="text-white/50 text-sm">Aucun événement à venir.</p>
              ) : (
                <div className="space-y-3">
                  {upcomingEvents.map((ev) => {
                    const cfg = typeConfig[ev.type];
                    const d = new Date(ev.date + 'T00:00:00');
                    return (
                      <button
                        key={ev.id}
                        onClick={() => setSelected(ev)}
                        className="w-full text-left flex items-start gap-3 hover:bg-white/5 rounded-xl p-2 transition-colors"
                      >
                        <div className="shrink-0 text-center">
                          <div className="text-xs text-white/50 font-medium">{MONTHS_FR[d.getMonth()].slice(0, 3)}</div>
                          <div className={`w-9 h-9 ${cfg.bg} ${cfg.text} rounded-lg flex items-center justify-center font-bold text-sm`}>
                            {d.getDate()}
                          </div>
                        </div>
                        <div className="min-w-0">
                          <div className="text-white text-sm font-medium leading-tight truncate">{ev.title}</div>
                          <div className="text-white/40 text-xs mt-0.5 flex items-center gap-1">
                            <MapPin size={10} /> {ev.location}
                          </div>
                        </div>
                      </button>
                    );
                  })}
                </div>
              )}
            </div>

            <div className="bg-white/10 border border-white/20 rounded-2xl p-5">
              <h3 className="font-bold text-[#ffd700] mb-3 text-sm uppercase tracking-wide">Légende</h3>
              <div className="space-y-2">
                {Object.entries(typeConfig).filter(([k]) => k !== 'autre').map(([key, { label, bg }]) => (
                  <div key={key} className="flex items-center gap-2">
                    <span className={`w-3 h-3 rounded-sm shrink-0 ${bg}`} />
                    <span className="text-white/70 text-xs">{label}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="bg-[#ffd700]/10 border border-[#ffd700]/20 rounded-2xl p-5">
              <p className="text-white/60 text-xs leading-relaxed">
                Les dates sont synchronisées depuis l'agenda officiel du club. Consultez les annonces en club pour les informations les plus récentes.
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Modal */}
      {selected && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
          onClick={() => setSelected(null)}
        >
          <div
            className="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl"
            onClick={e => e.stopPropagation()}
          >
            <div className="flex items-start justify-between mb-4">
              <div>
                <span className={`inline-flex text-xs font-semibold px-2.5 py-1 rounded-full mb-2 ${typeConfig[selected.type].bg} ${typeConfig[selected.type].text}`}>
                  {typeConfig[selected.type].label}
                </span>
                <h3 className="text-stone-900 font-bold text-lg leading-tight">{selected.title}</h3>
              </div>
              <button onClick={() => setSelected(null)} className="text-stone-400 hover:text-stone-600 transition-colors ml-3 shrink-0">
                <X size={20} />
              </button>
            </div>
            {selected.desc && (
              <p className="text-stone-600 text-sm mb-4 leading-relaxed">{selected.desc}</p>
            )}
            <div className="space-y-2 text-sm text-stone-500">
              <div className="flex items-center gap-2">
                <span className="font-medium text-stone-700">Date :</span>
                {new Date(selected.date + 'T00:00:00').toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })}
              </div>
              <div className="flex items-center gap-2">
                <MapPin size={14} className="text-stone-400 shrink-0" />
                {selected.location}
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
