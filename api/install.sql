-- Script d'installation — à exécuter une seule fois dans phpMyAdmin ou via la console MySQL OVH

CREATE TABLE IF NOT EXISTS `news` (
  `id`           INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  `tag`          VARCHAR(50)     NOT NULL,
  `title`        VARCHAR(255)    NOT NULL,
  `body`         TEXT            NOT NULL,
  `published_at` DATE            NOT NULL,
  `created_at`   TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `photos` (
  `id`         INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
  `url`        VARCHAR(512)    NOT NULL,
  `caption`    VARCHAR(255)    NOT NULL DEFAULT '',
  `category`   VARCHAR(100)    NOT NULL DEFAULT '',
  `position`   INT             NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP       DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
