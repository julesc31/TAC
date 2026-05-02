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

async function req<T>(path: string, init?: RequestInit): Promise<T> {
  const isFormData = init?.body instanceof FormData;
  const res = await fetch(`/api${path}`, {
    credentials: 'include',
    headers: isFormData ? undefined : { 'Content-Type': 'application/json' },
    ...init,
  });
  return res.json() as Promise<T>;
}

export const api = {
  auth: {
    check: () =>
      req<{ loggedIn: boolean }>('/auth.php?action=check'),
    login: (email: string, password: string) =>
      req<{ success: boolean; error?: string }>('/auth.php?action=login', {
        method: 'POST',
        body: JSON.stringify({ email, password }),
      }),
    logout: () =>
      req<{ success: boolean }>('/auth.php?action=logout', { method: 'POST' }),
  },
  news: {
    list: (limit?: number) =>
      req<{ data: NewsItem[] }>(`/news.php${limit ? `?limit=${limit}` : ''}`),
    create: (body: Omit<NewsItem, 'id' | 'created_at'>) =>
      req<{ success: boolean }>('/news.php', { method: 'POST', body: JSON.stringify(body) }),
    update: (id: string, body: Partial<Omit<NewsItem, 'id' | 'created_at'>>) =>
      req<{ success: boolean }>(`/news.php?id=${id}`, { method: 'PUT', body: JSON.stringify(body) }),
    remove: (id: string) =>
      req<{ success: boolean }>(`/news.php?id=${id}`, { method: 'DELETE' }),
  },
  photos: {
    list: () =>
      req<{ data: PhotoItem[] }>('/photos.php'),
    create: (body: Omit<PhotoItem, 'id' | 'created_at'>) =>
      req<{ success: boolean }>('/photos.php', { method: 'POST', body: JSON.stringify(body) }),
    update: (id: string, body: Partial<Omit<PhotoItem, 'id' | 'created_at'>>) =>
      req<{ success: boolean }>(`/photos.php?id=${id}`, { method: 'PUT', body: JSON.stringify(body) }),
    remove: (id: string) =>
      req<{ success: boolean }>(`/photos.php?id=${id}`, { method: 'DELETE' }),
    swap: (id1: string, pos1: number, id2: string, pos2: number) =>
      req<{ success: boolean }>('/photos.php?action=swap', {
        method: 'PUT',
        body: JSON.stringify({ id1, pos1, id2, pos2 }),
      }),
    upload: (file: File) => {
      const fd = new FormData();
      fd.append('file', file);
      return req<{ success: boolean; url?: string; error?: string }>(
        '/photos.php?action=upload',
        { method: 'POST', body: fd }
      );
    },
  },
};
