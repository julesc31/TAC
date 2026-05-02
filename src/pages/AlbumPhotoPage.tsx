import { useState, useEffect } from 'react';
import { X, ChevronLeft, ChevronRight, ZoomIn, Loader2, AlertCircle } from 'lucide-react';
import { supabase, type PhotoItem } from '../lib/supabase';

export default function AlbumPhotoPage() {
  const [photos, setPhotos] = useState<PhotoItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(false);
  const [activeCategory, setActiveCategory] = useState('Tous');
  const [lightboxIndex, setLightboxIndex] = useState<number | null>(null);

  useEffect(() => {
    supabase
      .from('photos')
      .select('*')
      .order('position', { ascending: true })
      .then(({ data, error: err }) => {
        if (err) setError(true);
        else setPhotos(data ?? []);
        setLoading(false);
      });
  }, []);

  const categories = ['Tous', ...Array.from(new Set(photos.map((p) => p.category)))];
  const filtered = activeCategory === 'Tous' ? photos : photos.filter((p) => p.category === activeCategory);

  const openLightbox = (index: number) => setLightboxIndex(index);
  const closeLightbox = () => setLightboxIndex(null);
  const prev = () => setLightboxIndex((i) => i === null ? null : (i - 1 + filtered.length) % filtered.length);
  const next = () => setLightboxIndex((i) => i === null ? null : (i + 1) % filtered.length);

  const currentPhoto = lightboxIndex !== null ? filtered[lightboxIndex] : null;

  return (
    <div className="min-h-screen bg-[#0a2744]">
      {/* Hero */}
      <div className="relative pt-16 lg:pt-20 bg-gradient-to-b from-[#0a2744] to-[#1a3a5c]">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 text-center">
          <div className="inline-flex items-center gap-2 bg-[#ffd700]/10 border border-[#ffd700]/30 rounded-full px-4 py-1.5 mb-6">
            <span className="text-[#ffd700] text-xs font-semibold tracking-widest uppercase">Arc Club Pechbonnieu</span>
          </div>
          <h1 className="text-4xl lg:text-6xl font-bold text-white mb-4 leading-tight">
            Album <span className="text-[#ffd700]">Photo</span>
          </h1>
          <p className="text-stone-300 text-lg max-w-2xl mx-auto">
            Revivez les moments forts du club : compétitions, entraînements, sorties et vie associative.
          </p>
        </div>
      </div>

      {loading && (
        <div className="flex justify-center py-24">
          <Loader2 size={36} className="text-[#ffd700] animate-spin" />
        </div>
      )}

      {error && (
        <div className="max-w-xl mx-auto px-4 py-16 flex items-center gap-3 bg-red-500/10 border border-red-500/30 rounded-2xl text-red-300 text-sm">
          <AlertCircle size={18} className="shrink-0" />
          Impossible de charger les photos pour le moment.
        </div>
      )}

      {!loading && !error && (
        <>
          {/* Filters */}
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div className="flex flex-wrap gap-2 justify-center">
              {categories.map((cat) => (
                <button
                  key={cat}
                  onClick={() => setActiveCategory(cat)}
                  className={`px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 ${
                    activeCategory === cat
                      ? 'bg-[#ffd700] text-[#0a2744] shadow-lg shadow-[#ffd700]/20'
                      : 'bg-white/10 text-stone-300 hover:bg-white/20 hover:text-white border border-white/10'
                  }`}
                >
                  {cat}
                </button>
              ))}
            </div>
          </div>

          {/* Grid */}
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            {filtered.length === 0 ? (
              <div className="text-center py-24 text-stone-400">Aucune photo dans cette catégorie.</div>
            ) : (
              <div className="columns-1 sm:columns-2 lg:columns-3 gap-4 space-y-4">
                {filtered.map((photo, index) => (
                  <div
                    key={photo.id}
                    className="break-inside-avoid group relative overflow-hidden rounded-xl cursor-pointer"
                    onClick={() => openLightbox(index)}
                  >
                    <img
                      src={photo.url}
                      alt={photo.caption}
                      className="w-full object-cover transition-transform duration-500 group-hover:scale-105"
                      loading="lazy"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                      <div className="flex items-end justify-between">
                        <div>
                          <span className="text-[#ffd700] text-xs font-semibold uppercase tracking-wider block mb-1">
                            {photo.category}
                          </span>
                          <p className="text-white text-sm font-medium leading-snug">{photo.caption}</p>
                        </div>
                        <div className="bg-white/20 backdrop-blur-sm rounded-full p-2 ml-3 shrink-0">
                          <ZoomIn size={16} className="text-white" />
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            )}
          </div>
        </>
      )}

      {/* Lightbox */}
      {currentPhoto && (
        <div
          className="fixed inset-0 z-50 bg-black/95 flex items-center justify-center p-4"
          onClick={closeLightbox}
        >
          <button
            onClick={closeLightbox}
            className="absolute top-4 right-4 z-10 bg-white/10 hover:bg-white/20 rounded-full p-2.5 text-white transition-colors"
          >
            <X size={20} />
          </button>
          <button
            onClick={(e) => { e.stopPropagation(); prev(); }}
            className="absolute left-4 z-10 bg-white/10 hover:bg-white/20 rounded-full p-3 text-white transition-colors"
          >
            <ChevronLeft size={24} />
          </button>
          <div className="max-w-5xl w-full" onClick={(e) => e.stopPropagation()}>
            <img
              src={currentPhoto.url}
              alt={currentPhoto.caption}
              className="w-full max-h-[80vh] object-contain rounded-xl shadow-2xl"
            />
            <div className="mt-4 text-center">
              <span className="text-[#ffd700] text-xs font-semibold uppercase tracking-wider">{currentPhoto.category}</span>
              <p className="text-white text-base mt-1">{currentPhoto.caption}</p>
              <p className="text-stone-500 text-sm mt-1">{(lightboxIndex ?? 0) + 1} / {filtered.length}</p>
            </div>
          </div>
          <button
            onClick={(e) => { e.stopPropagation(); next(); }}
            className="absolute right-4 z-10 bg-white/10 hover:bg-white/20 rounded-full p-3 text-white transition-colors"
          >
            <ChevronRight size={24} />
          </button>
        </div>
      )}
    </div>
  );
}
