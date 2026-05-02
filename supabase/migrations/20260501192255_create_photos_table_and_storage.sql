/*
  # Création de la table photos

  1. Nouvelle table
    - `photos`
      - `id` (uuid, clé primaire)
      - `url` (text) — URL publique de la photo (Supabase Storage ou externe)
      - `caption` (text) — légende
      - `category` (text) — catégorie (Entraînement, Compétition, Extérieur, Jeunes, Vie du club…)
      - `position` (int) — ordre d'affichage
      - `created_at` (timestamptz)

  2. Sécurité
    - RLS activé
    - Lecture publique
    - Écriture réservée aux utilisateurs authentifiés

  3. Données initiales
    - Les 12 photos existantes dans le code
*/

CREATE TABLE IF NOT EXISTS photos (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  url text NOT NULL DEFAULT '',
  caption text NOT NULL DEFAULT '',
  category text NOT NULL DEFAULT 'Vie du club',
  position integer NOT NULL DEFAULT 0,
  created_at timestamptz NOT NULL DEFAULT now()
);

ALTER TABLE photos ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Photos visibles par tous"
  ON photos FOR SELECT
  TO anon, authenticated
  USING (true);

CREATE POLICY "Seuls les authentifiés peuvent ajouter des photos"
  ON photos FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Seuls les authentifiés peuvent modifier des photos"
  ON photos FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Seuls les authentifiés peuvent supprimer des photos"
  ON photos FOR DELETE
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS photos_position_idx ON photos (position ASC);

INSERT INTO photos (url, caption, category, position) VALUES
  ('https://images.pexels.com/photos/6551416/pexels-photo-6551416.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Séance d''entraînement en salle', 'Entraînement', 1),
  ('https://images.pexels.com/photos/6551176/pexels-photo-6551176.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Concentration avant le tir', 'Entraînement', 2),
  ('https://images.pexels.com/photos/6551136/pexels-photo-6551136.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Tir à l''arc extérieur', 'Extérieur', 3),
  ('https://images.pexels.com/photos/6551119/pexels-photo-6551119.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Compétition régionale', 'Compétition', 4),
  ('https://images.pexels.com/photos/6551108/pexels-photo-6551108.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Archers en plein air', 'Extérieur', 5),
  ('https://images.pexels.com/photos/5048108/pexels-photo-5048108.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Podium de compétition', 'Compétition', 6),
  ('https://images.pexels.com/photos/6832047/pexels-photo-6832047.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Jeunes archers au club', 'Jeunes', 7),
  ('https://images.pexels.com/photos/6551175/pexels-photo-6551175.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Initiation tir à l''arc', 'Jeunes', 8),
  ('https://images.pexels.com/photos/6551105/pexels-photo-6551105.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Parcours 3D en forêt', 'Extérieur', 9),
  ('https://images.pexels.com/photos/7990344/pexels-photo-7990344.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Équipe du club', 'Vie du club', 10),
  ('https://images.pexels.com/photos/6551177/pexels-photo-6551177.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Flèches sur la cible', 'Entraînement', 11),
  ('https://images.pexels.com/photos/6551421/pexels-photo-6551421.jpeg?auto=compress&cs=tinysrgb&w=1200', 'Stage de perfectionnement', 'Vie du club', 12);
