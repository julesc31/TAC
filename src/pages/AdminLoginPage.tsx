import { useState } from 'react';
import { Target, Eye, EyeOff, Loader2, AlertCircle } from 'lucide-react';
import { supabase } from '../lib/supabase';

interface AdminLoginPageProps {
  onLogin: () => void;
}

export default function AdminLoginPage({ onLogin }: AdminLoginPageProps) {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    const { error: err } = await supabase.auth.signInWithPassword({ email, password });
    setLoading(false);
    if (err) {
      setError('Email ou mot de passe incorrect.');
    } else {
      onLogin();
    }
  };

  return (
    <div className="min-h-screen bg-[#0a2744] flex items-center justify-center px-4">
      <div className="w-full max-w-md">
        <div className="text-center mb-8">
          <div className="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-[#ffd700]/10 border border-[#ffd700]/30 mb-4">
            <Target size={28} className="text-[#ffd700]" />
          </div>
          <h1 className="text-2xl font-bold text-white">Espace administration</h1>
          <p className="text-stone-400 text-sm mt-1">Arc Club Pechbonnieu</p>
        </div>

        <form
          onSubmit={handleSubmit}
          className="bg-white/5 border border-white/10 rounded-2xl p-8 space-y-5"
        >
          <div>
            <label className="block text-sm font-medium text-stone-300 mb-1.5">
              Adresse e-mail
            </label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              placeholder="admin@example.com"
              className="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 transition-all"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-stone-300 mb-1.5">
              Mot de passe
            </label>
            <div className="relative">
              <input
                type={showPassword ? 'text' : 'password'}
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                required
                placeholder="••••••••"
                className="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 pr-12 text-white placeholder:text-stone-500 focus:outline-none focus:ring-2 focus:ring-[#ffd700]/50 focus:border-[#ffd700]/50 transition-all"
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-white transition-colors"
              >
                {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
              </button>
            </div>
          </div>

          {error && (
            <div className="flex items-center gap-2 bg-red-500/10 border border-red-500/30 rounded-xl px-4 py-3 text-red-300 text-sm">
              <AlertCircle size={16} className="shrink-0" />
              {error}
            </div>
          )}

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-[#ffd700] hover:bg-yellow-300 disabled:opacity-60 text-[#0a2744] font-bold py-3 rounded-xl transition-all duration-200 flex items-center justify-center gap-2"
          >
            {loading && <Loader2 size={18} className="animate-spin" />}
            {loading ? 'Connexion…' : 'Se connecter'}
          </button>
        </form>
      </div>
    </div>
  );
}
