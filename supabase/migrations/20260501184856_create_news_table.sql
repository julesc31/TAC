/*
  # Création de la table des actualités du club

  1. Nouvelle table
    - `news`
      - `id` (uuid, clé primaire)
      - `tag` (text) — catégorie : Entraînement, Compétition, Annonce, Club…
      - `title` (text) — titre de l'actualité
      - `body` (text) — contenu de l'actualité
      - `published_at` (timestamptz) — date de publication
      - `created_at` (timestamptz) — date de création en base

  2. Sécurité
    - RLS activé
    - Lecture publique (les actualités sont visibles par tous)
    - Écriture réservée aux utilisateurs authentifiés (admins)

  3. Données initiales
    - 4 actualités fictives pour démarrer
*/

CREATE TABLE IF NOT EXISTS news (
  id uuid PRIMARY KEY DEFAULT gen_random_uuid(),
  tag text NOT NULL DEFAULT 'Club',
  title text NOT NULL DEFAULT '',
  body text NOT NULL DEFAULT '',
  published_at timestamptz NOT NULL DEFAULT now(),
  created_at timestamptz NOT NULL DEFAULT now()
);

ALTER TABLE news ENABLE ROW LEVEL SECURITY;

CREATE POLICY "Actualités visibles par tous"
  ON news FOR SELECT
  TO anon, authenticated
  USING (true);

CREATE POLICY "Seuls les authentifiés peuvent créer des actualités"
  ON news FOR INSERT
  TO authenticated
  WITH CHECK (true);

CREATE POLICY "Seuls les authentifiés peuvent modifier des actualités"
  ON news FOR UPDATE
  TO authenticated
  USING (true)
  WITH CHECK (true);

CREATE POLICY "Seuls les authentifiés peuvent supprimer des actualités"
  ON news FOR DELETE
  TO authenticated
  USING (true);

CREATE INDEX IF NOT EXISTS news_published_at_idx ON news (published_at DESC);

INSERT INTO news (tag, title, body, published_at) VALUES
  ('Entraînement', 'Reprise des entraînements du lundi soir', 'Les entraînements du lundi reprennent à partir du 5 mai de 21h à 23h. Venez nombreux ! Tout le matériel est disponible sur place pour les débutants.', '2025-04-28T00:00:00Z'),
  ('Compétition', 'Résultats du Championnat Départemental', 'Bravo à nos archers qui ont brillé lors du championnat départemental de Haute-Garonne ! Trois podiums remportés dans les catégories juniors et seniors.', '2025-04-20T00:00:00Z'),
  ('Annonce', 'Journée portes ouvertes le 17 mai', 'Le club organise une journée portes ouvertes le samedi 17 mai de 9h à 12h. Initiations gratuites pour toute la famille, venez découvrir le tir à l''arc !', '2025-04-10T00:00:00Z'),
  ('Club', 'Inscriptions saison 2025-2026 ouvertes', 'Les inscriptions pour la nouvelle saison sont désormais ouvertes. Tarifs inchangés, nouveaux créneaux disponibles pour les enfants dès 10 ans.', '2025-04-01T00:00:00Z');
