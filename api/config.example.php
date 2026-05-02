<?php
// Copiez ce fichier en config.php et remplissez vos valeurs.
// Ce fichier est un modèle — config.php n'est PAS versionné (voir .gitignore).

// Base de données MySQL OVH
define('DB_HOST', 'localhost');
define('DB_NAME', 'votre_nom_de_base');
define('DB_USER', 'votre_utilisateur');
define('DB_PASS', 'votre_mot_de_passe');

// Identifiants administrateur du site
// Pour générer le hash du mot de passe, exécutez en PHP :
//   php -r "echo password_hash('votre_mot_de_passe', PASSWORD_DEFAULT);"
define('ADMIN_EMAIL', 'admin@votre-domaine.fr');
define('ADMIN_PASSWORD_HASH', '$2y$10$CHANGEME_HASH_ICI');

// Google Calendar (agenda public du club)
define('GOOGLE_CALENDAR_ID', 'pechbonnieu.tiralarc@gmail.com');
define('GOOGLE_CALENDAR_API_KEY', 'VOTRE_CLE_API_GOOGLE');
