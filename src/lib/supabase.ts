import { createClient } from '@supabase/supabase-js';

export const supabase = createClient(
  import.meta.env.VITE_SUPABASE_URL,
  import.meta.env.VITE_SUPABASE_ANON_KEY
);

export interface NewsItem {
  id: string;
  tag: string;
  title: string;
  body: string;
  published_at: string;
  created_at: string;
}

export interface PhotoItem {
  id: string;
  url: string;
  caption: string;
  category: string;
  position: number;
  created_at: string;
}
