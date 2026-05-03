-- ============================================================
--  Arc Club Pechbonnieu — Script d'installation MySQL
--  À exécuter UNE SEULE FOIS dans phpMyAdmin sur OVH
-- ============================================================

SET NAMES utf8mb4;
SET foreign_key_checks = 0;

-- ─── Actualités ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `news` (
  `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `tag`          VARCHAR(50)  NOT NULL DEFAULT 'Annonce',
  `title`        VARCHAR(255) NOT NULL,
  `body`         TEXT         NOT NULL,
  `published_at` DATE         NOT NULL,
  `created_at`   DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_published` (`published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Photos ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `photos` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `url`        TEXT         NOT NULL,
  `caption`    VARCHAR(255) NOT NULL DEFAULT '',
  `category`   VARCHAR(100) NOT NULL DEFAULT 'Entraînement',
  `position`   INT UNSIGNED NOT NULL DEFAULT 0,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_position` (`position`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Préinscriptions ────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `preinscriptions` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom`           VARCHAR(150) NOT NULL,
  `email`         VARCHAR(150) NOT NULL,
  `categorie`     VARCHAR(50)  NOT NULL DEFAULT '',
  `age`           TINYINT UNSIGNED     DEFAULT NULL,
  `telephone`     VARCHAR(30)          DEFAULT NULL,
  `type_arc`      VARCHAR(50)          DEFAULT NULL,
  `niveau`        VARCHAR(50)          DEFAULT NULL,
  `commentaires`  TEXT                 DEFAULT NULL,
  `created_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Messages de contact ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS `contacts` (
  `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom`        VARCHAR(150) NOT NULL,
  `email`      VARCHAR(150) NOT NULL,
  `sujet`      VARCHAR(100) NOT NULL DEFAULT 'information',
  `message`    TEXT         NOT NULL,
  `created_at` DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Utilisateurs admin ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(150) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at`    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Compétitions ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `competitions` (
  `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `titre`            VARCHAR(255) NOT NULL,
  `type_competition` VARCHAR(100) NOT NULL,
  `lieu`             VARCHAR(255) NOT NULL DEFAULT '',
  `date`             DATE         NOT NULL,
  `lien_officiel`    VARCHAR(500) NOT NULL DEFAULT '',
  `created_at`       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Résultats des archers ───────────────────────────────────
CREATE TABLE IF NOT EXISTS `resultats` (
  `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `competition_id` INT UNSIGNED NOT NULL,
  `nom`            VARCHAR(150) NOT NULL,
  `categorie`      VARCHAR(100) NOT NULL DEFAULT '',
  `place`          SMALLINT UNSIGNED     DEFAULT NULL,
  `created_at`     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_competition` (`competition_id`),
  CONSTRAINT `fk_resultat_competition`
    FOREIGN KEY (`competition_id`) REFERENCES `competitions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Compte admin par défaut ─────────────────────────────────
-- Mot de passe : ChangeMe2024!  (CHANGEZ-LE immédiatement après connexion)
-- Hash généré avec password_hash('ChangeMe2024!', PASSWORD_BCRYPT)
INSERT IGNORE INTO `admin_users` (`email`, `password_hash`) VALUES (
  'admin@arcclubpechbonnieu.fr',
  '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

-- ─── Données de démonstration ────────────────────────────────
INSERT IGNORE INTO `news` (`id`, `tag`, `title`, `body`, `published_at`) VALUES
(1, 'Annonce',      'Bienvenue sur le nouveau site du club !', 'Le site de l\'Arc Club Pechbonnieu fait peau neuve. Retrouvez toutes les actualités, le calendrier des événements et les informations d\'inscription sur ce site.', CURDATE()),
(2, 'Compétition',  'Résultats du championnat régional', 'Nos archers ont brillé lors du championnat régional. Félicitations à tous les participants !', DATE_SUB(CURDATE(), INTERVAL 7 DAY)),
(3, 'Entraînement', 'Reprise des entraînements', 'Les entraînements reprennent après les vacances. Rendez-vous lundi à 21h au gymnase.', DATE_SUB(CURDATE(), INTERVAL 14 DAY));

SET foreign_key_checks = 1;
