import { useState, useEffect, useRef } from 'react';
import {
  LogOut, Plus, Trash2, Pencil, Check, X, Loader2, Newspaper, Image,
  AlertCircle, ChevronUp, ChevronDown,
} from 'lucide-react';
import { api, type NewsItem, type PhotoItem } from '../lib/api';

interface AdminPageProps {
  onLogout: () => void;
}

type Tab = 'news' | 'photos';

const NEWS_TAGS = ['Entraînement', 'Compétition', 'Annonce', 'Club'];
const PHOTO_CATEGORIES = ['Entraînement', 'Compétition', 'Extérieur', 'Jeunes', 'Vie du club'];

function formatDateInput(iso: string) {
  return iso.slice(0, 10);
}

// ─── News section ──────────────────────────────────────────────────────────────

function NewsSection() {
  const [news, setNews] = useState<NewsItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [editingId, setEditingId] = useState<string | null>(null);
  const [saving, setSaving] = useState(false);
  const [showForm, setShowForm] = useState(false);

  const blankForm = { tag: NEWS_TAGS[0], title: '', body: '', published_at: new Date().toISOString().slice(0, 10) };
  const [form, setForm] = useState(blankForm);

  const fetchNews = async () => {
    const { data } = await api.news.list();
    setNews(data ?? []);
    setLoading(false);
  };

  useEffect(() => { fetchNews(); }, []);

  const startEdit = (item: NewsItem) => {
    setEditingId(item.id);
    setForm({ tag: item.tag, title: item.title, body: item.body, published_at: formatDateInput(item.published_at) });
    setShowForm(false);
  };

  const cancelEdit = () => { setEditingId(null); setForm(blankForm); };

  const saveEdit = async () => {
    if (!editingId) return;
    setSaving(true);
    const result = await api.news.update(editingId, form);
    setSaving(false);
    if (!result.success) { setError('Erreur lors de la sauvegarde.'); return; }
    setEditingId(null);
    fetchNews();
  };

  const deleteItem = async (id: string) => {
    if (!confirm('Supprimer cette actualité ?')) return;
    await api.news.remove(id);
    fetchNews();
  };

  const addNews = async () => {
    if (!form.title.trim() || !form.body.trim()) { setError('Titre et contenu requis.'); return; }
    setSaving(true);
    const result = await api.news.create(form);
    setSaving(false);
    if (!result.success) { setError("Erreur lors de l'ajout."); return; }
    setForm(blankForm);
    setShowForm(false);
    fetchNews();
  };

  if (loading) return <div className="flex justify-center py-16"><Loader2 size={32} className="text-[#ffd700] animate-spin" /></div>;

  return (
    <div className="space-y-4">
      {error && (
        <div className="flex items-center gap-2 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-red-300 text-sm">
          <AlertCircle size={16} className="shrink-0" />{error}
          <button onClick={() => setError('')} className="ml-auto"><X size={14} /></button>
        </div>
      )}

      <button
        onClick={() => { setShowForm(!showForm); setEditingId(null); setForm(blankForm); }}
        className="inline-flex items-center gap-2 bg-[#ffd700] hover:bg-yellow-300 text-[#0a2744] font-bold px-5 py-2.5 rounded-xl transition-all duration-200 text-sm"
      >
        <Plus size={16} />
        Nouvelle actualité
      </button>

      {showForm && (
        <NewsForm
          form={form}
          setForm={setForm}
          onSave={addNews}
          onCancel={() => { setShowForm(false); setForm(blankForm); }}
          saving={saving}
          label="Publier"
        />
      )}

      {news.length === 0 && <p className="text-stone-400 text-sm py-6 text-center">Aucune actualité.</p>}
      {news.map((item) => (
        <div key={item.id} className="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
          {editingId === item.id ? (
            <div className="p-5">
              <NewsForm
                form={form}
                setForm={setForm}
                onSave={saveEdit}
                onCancel={cancelEdit}
                saving={saving}
                label="Enregistrer"
              />
            </div>
          ) : (
            <div className="p-5 flex items-start gap-4">
              <div className="flex-1 min-w-0">
                <div className="flex flex-wrap items-center gap-2 mb-1">
                  <span className="text-xs font-semibold bg-white/10 text-stone-300 px-2 py-0.5 rounded-full">{item.tag}</span>
                  <span className="text-stone-500 text-xs">{new Date(item.published_at).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                </div>
                <p className="text-white font-semibold text-sm mb-1">{item.title}</p>
                <p className="text-stone-400 text-xs leading-relaxed line-clamp-2">{item.body}</p>
              </div>
              <div className="flex items-center gap-2 shrink-0">
                <button onClick={() => startEdit(item)} className="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-stone-300 hover:text-white transition-colors">
                  <Pencil size={15} />
                </button>
                <button onClick={() => deleteItem(item.id)} className="p-2 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-colors">
                  <Trash2 size={15} />
                </button>
              </div>
            </div>
          )}
        </div>
      ))}
    </div>
  );
}

interface NewsFormProps {
  form: { tag: string; title: string; body: string; published_at: string };
  setForm: (f: { tag: string; title: string; body: string; published_at: string }) => void;
  onSave: () => void;
  onCancel: () => void;
  saving: boolean;
  label: string;
}

function NewsForm({ form, setForm, onSave, onCancel, saving, label }: NewsFormProps) {
  return (
    <div className="bg-white/5 border border-[#ffd700]/20 rounded-2xl p-5 space-y-4">
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
          <label className="block text-xs font-medium text-stone-400 mb-1">Catégorie</label>
          <select
            value={form.tag}
            onChange={(e) => setForm({ ...form, tag: e.target.value })}
            className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
          >
            {NEWS_TAGS.map((t) => <option key={t} value={t} className="bg-[#1a4a7c]">{t}</option>)}
          </select>
        </div>
        <div>
          <label className="block text-xs font-medium text-stone-400 mb-1">Date de publication</label>
          <input
            type="date"
            value={form.published_at}
            onChange={(e) => setForm({ ...form, published_at: e.target.value })}
            className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
          />
        </div>
      </div>
      <div>
        <label className="block text-xs font-medium text-stone-400 mb-1">Titre</label>
        <input
          type="text"
          value={form.title}
          onChange={(e) => setForm({ ...form, title: e.target.value })}
          placeholder="Titre de l'actualité"
          className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white placeholder:text-stone-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
        />
      </div>
      <div>
        <label className="block text-xs font-medium text-stone-400 mb-1">Contenu</label>
        <textarea
          value={form.body}
          onChange={(e) => setForm({ ...form, body: e.target.value })}
          placeholder="Rédigez votre actualité…"
          rows={4}
          className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white placeholder:text-stone-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 resize-none"
        />
      </div>
      <div className="flex gap-3">
        <button
          onClick={onSave}
          disabled={saving}
          className="inline-flex items-center gap-2 bg-[#ffd700] hover:bg-yellow-300 disabled:opacity-60 text-[#0a2744] font-bold px-5 py-2.5 rounded-xl transition-all text-sm"
        >
          {saving ? <Loader2 size={14} className="animate-spin" /> : <Check size={14} />}
          {label}
        </button>
        <button
          onClick={onCancel}
          className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-stone-300 px-5 py-2.5 rounded-xl transition-all text-sm"
        >
          <X size={14} /> Annuler
        </button>
      </div>
    </div>
  );
}

// ─── Photos section ────────────────────────────────────────────────────────────

function PhotosSection() {
  const [photos, setPhotos] = useState<PhotoItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [editingId, setEditingId] = useState<string | null>(null);
  const [editForm, setEditForm] = useState({ caption: '', category: PHOTO_CATEGORIES[0] });
  const [saving, setSaving] = useState(false);
  const [uploading, setUploading] = useState(false);
  const [addForm, setAddForm] = useState({ url: '', caption: '', category: PHOTO_CATEGORIES[0] });
  const [showAddForm, setShowAddForm] = useState(false);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const fetchPhotos = async () => {
    const { data } = await api.photos.list();
    setPhotos(data ?? []);
    setLoading(false);
  };

  useEffect(() => { fetchPhotos(); }, []);

  const startEdit = (item: PhotoItem) => {
    setEditingId(item.id);
    setEditForm({ caption: item.caption, category: item.category });
  };

  const saveEdit = async (id: string) => {
    setSaving(true);
    await api.photos.update(id, editForm);
    setSaving(false);
    setEditingId(null);
    fetchPhotos();
  };

  const deletePhoto = async (id: string) => {
    if (!confirm('Supprimer cette photo ?')) return;
    await api.photos.remove(id);
    fetchPhotos();
  };

  const moveUp = async (index: number) => {
    if (index === 0) return;
    const a = photos[index];
    const b = photos[index - 1];
    await api.photos.swap(a.id, a.position, b.id, b.position);
    fetchPhotos();
  };

  const moveDown = async (index: number) => {
    if (index === photos.length - 1) return;
    const a = photos[index];
    const b = photos[index + 1];
    await api.photos.swap(a.id, a.position, b.id, b.position);
    fetchPhotos();
  };

  const handleFileUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    setUploading(true);
    setError('');
    const result = await api.photos.upload(file);
    setUploading(false);
    if (!result.success || !result.url) {
      setError(result.error ?? "Erreur lors de l'upload.");
      return;
    }
    setAddForm((f) => ({ ...f, url: result.url! }));
    setShowAddForm(true);
    if (fileInputRef.current) fileInputRef.current.value = '';
  };

  const addPhotoByUrl = async () => {
    if (!addForm.url.trim()) { setError('URL requise.'); return; }
    setSaving(true);
    const maxPos = photos.length > 0 ? Math.max(...photos.map((p) => p.position)) : 0;
    await api.photos.create({ ...addForm, position: maxPos + 1 });
    setSaving(false);
    setAddForm({ url: '', caption: '', category: PHOTO_CATEGORIES[0] });
    setShowAddForm(false);
    fetchPhotos();
  };

  if (loading) return <div className="flex justify-center py-16"><Loader2 size={32} className="text-[#ffd700] animate-spin" /></div>;

  return (
    <div className="space-y-4">
      {error && (
        <div className="flex items-center gap-2 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-red-300 text-sm">
          <AlertCircle size={16} className="shrink-0" />{error}
          <button onClick={() => setError('')} className="ml-auto"><X size={14} /></button>
        </div>
      )}

      <div className="flex flex-wrap gap-3">
        <label className={`inline-flex items-center gap-2 cursor-pointer bg-[#ffd700] hover:bg-yellow-300 text-[#0a2744] font-bold px-5 py-2.5 rounded-xl transition-all text-sm ${uploading ? 'opacity-60 pointer-events-none' : ''}`}>
          {uploading ? <Loader2 size={16} className="animate-spin" /> : <Plus size={16} />}
          {uploading ? 'Upload en cours…' : 'Uploader une photo'}
          <input ref={fileInputRef} type="file" accept="image/*" className="hidden" onChange={handleFileUpload} />
        </label>
        <button
          onClick={() => { setShowAddForm(!showAddForm); setAddForm({ url: '', caption: '', category: PHOTO_CATEGORIES[0] }); }}
          className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-stone-300 font-semibold px-5 py-2.5 rounded-xl transition-all text-sm border border-white/10"
        >
          <Plus size={16} />
          Ajouter par URL
        </button>
      </div>

      {showAddForm && (
        <div className="bg-white/5 border border-[#ffd700]/20 rounded-2xl p-5 space-y-4">
          <div>
            <label className="block text-xs font-medium text-stone-400 mb-1">URL de la photo</label>
            <input
              type="url"
              value={addForm.url}
              onChange={(e) => setAddForm({ ...addForm, url: e.target.value })}
              placeholder="https://…"
              className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white placeholder:text-stone-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
            />
          </div>
          {addForm.url && (
            <img src={addForm.url} alt="aperçu" className="w-32 h-24 object-cover rounded-lg border border-white/10" onError={(e) => (e.currentTarget.style.display = 'none')} />
          )}
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-medium text-stone-400 mb-1">Légende</label>
              <input
                type="text"
                value={addForm.caption}
                onChange={(e) => setAddForm({ ...addForm, caption: e.target.value })}
                placeholder="Description de la photo"
                className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white placeholder:text-stone-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
              />
            </div>
            <div>
              <label className="block text-xs font-medium text-stone-400 mb-1">Catégorie</label>
              <select
                value={addForm.category}
                onChange={(e) => setAddForm({ ...addForm, category: e.target.value })}
                className="w-full bg-white/10 border border-white/20 rounded-lg px-3 py-2.5 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
              >
                {PHOTO_CATEGORIES.map((c) => <option key={c} value={c} className="bg-[#1a4a7c]">{c}</option>)}
              </select>
            </div>
          </div>
          <div className="flex gap-3">
            <button onClick={addPhotoByUrl} disabled={saving} className="inline-flex items-center gap-2 bg-[#ffd700] hover:bg-yellow-300 disabled:opacity-60 text-[#0a2744] font-bold px-5 py-2.5 rounded-xl transition-all text-sm">
              {saving ? <Loader2 size={14} className="animate-spin" /> : <Check size={14} />}
              Ajouter
            </button>
            <button onClick={() => setShowAddForm(false)} className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-stone-300 px-5 py-2.5 rounded-xl transition-all text-sm">
              <X size={14} /> Annuler
            </button>
          </div>
        </div>
      )}

      {photos.length === 0 && <p className="text-stone-400 text-sm py-6 text-center">Aucune photo.</p>}
      <div className="space-y-2">
        {photos.map((item, index) => (
          <div key={item.id} className="bg-white/5 border border-white/10 rounded-2xl overflow-hidden">
            {editingId === item.id ? (
              <div className="p-4 flex items-center gap-4">
                <img src={item.url} alt="" className="w-20 h-14 object-cover rounded-lg shrink-0" />
                <div className="flex-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <input
                    type="text"
                    value={editForm.caption}
                    onChange={(e) => setEditForm({ ...editForm, caption: e.target.value })}
                    placeholder="Légende"
                    className="bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder:text-stone-500 text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
                  />
                  <select
                    value={editForm.category}
                    onChange={(e) => setEditForm({ ...editForm, category: e.target.value })}
                    className="bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50"
                  >
                    {PHOTO_CATEGORIES.map((c) => <option key={c} value={c} className="bg-[#1a4a7c]">{c}</option>)}
                  </select>
                </div>
                <div className="flex items-center gap-2 shrink-0">
                  <button onClick={() => saveEdit(item.id)} disabled={saving} className="p-2 rounded-lg bg-[#ffd700]/20 hover:bg-[#ffd700]/30 text-[#ffd700] transition-colors">
                    {saving ? <Loader2 size={15} className="animate-spin" /> : <Check size={15} />}
                  </button>
                  <button onClick={() => setEditingId(null)} className="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-stone-400 transition-colors">
                    <X size={15} />
                  </button>
                </div>
              </div>
            ) : (
              <div className="p-4 flex items-center gap-4">
                <img src={item.url} alt={item.caption} className="w-20 h-14 object-cover rounded-lg shrink-0" />
                <div className="flex-1 min-w-0">
                  <p className="text-white text-sm font-medium truncate">{item.caption || <span className="text-stone-500 italic">Sans légende</span>}</p>
                  <span className="text-xs text-stone-400 bg-white/10 px-2 py-0.5 rounded-full mt-1 inline-block">{item.category}</span>
                </div>
                <div className="flex items-center gap-1 shrink-0">
                  <button onClick={() => moveUp(index)} disabled={index === 0} className="p-1.5 rounded-lg hover:bg-white/10 text-stone-500 hover:text-white disabled:opacity-20 transition-colors">
                    <ChevronUp size={16} />
                  </button>
                  <button onClick={() => moveDown(index)} disabled={index === photos.length - 1} className="p-1.5 rounded-lg hover:bg-white/10 text-stone-500 hover:text-white disabled:opacity-20 transition-colors">
                    <ChevronDown size={16} />
                  </button>
                  <button onClick={() => startEdit(item)} className="p-2 rounded-lg bg-white/10 hover:bg-white/20 text-stone-300 hover:text-white transition-colors">
                    <Pencil size={15} />
                  </button>
                  <button onClick={() => deletePhoto(item.id)} className="p-2 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 transition-colors">
                    <Trash2 size={15} />
                  </button>
                </div>
              </div>
            )}
          </div>
        ))}
      </div>
    </div>
  );
}

// ─── Main AdminPage ────────────────────────────────────────────────────────────

export default function AdminPage({ onLogout }: AdminPageProps) {
  const [tab, setTab] = useState<Tab>('news');

  const handleLogout = async () => {
    await api.auth.logout();
    onLogout();
  };

  return (
    <div className="min-h-screen bg-[#0a2744] pt-6 pb-20">
      <div className="max-w-4xl mx-auto px-4 sm:px-6">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h1 className="text-2xl font-bold text-white">Administration</h1>
            <p className="text-stone-400 text-sm">Arc Club Pechbonnieu</p>
          </div>
          <button
            onClick={handleLogout}
            className="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-stone-300 hover:text-white px-4 py-2 rounded-xl transition-all text-sm border border-white/10"
          >
            <LogOut size={15} />
            Déconnexion
          </button>
        </div>

        <div className="flex gap-2 bg-white/5 border border-white/10 rounded-2xl p-1.5 mb-8 w-fit">
          <button
            onClick={() => setTab('news')}
            className={`inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 ${
              tab === 'news'
                ? 'bg-[#ffd700] text-[#0a2744] shadow-md'
                : 'text-stone-400 hover:text-white'
            }`}
          >
            <Newspaper size={16} />
            Actualités
          </button>
          <button
            onClick={() => setTab('photos')}
            className={`inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 ${
              tab === 'photos'
                ? 'bg-[#ffd700] text-[#0a2744] shadow-md'
                : 'text-stone-400 hover:text-white'
            }`}
          >
            <Image size={16} />
            Photos
          </button>
        </div>

        {tab === 'news' && <NewsSection />}
        {tab === 'photos' && <PhotosSection />}
      </div>
    </div>
  );
}
