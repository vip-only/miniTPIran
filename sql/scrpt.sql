-- ============================================================
-- SCRIPT DE CRÉATION DE LA BASE DE DONNÉES
-- Projet : Mini-projet Web Design (Mars 2026)
-- Cible : MySQL / MariaDB
-- ============================================================

-- 1. Création de la table des utilisateurs (Administration)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Création de la table des articles
CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE, -- Utilisé pour l'URL Rewriting
    `content` TEXT NOT NULL,
    `image_url` VARCHAR(255),            -- Chemin vers l'image dans le dossier public
    `image_alt` VARCHAR(155),            -- Crucial pour le SEO (consigne PDF)
    `status` ENUM('draft', 'published') DEFAULT 'published',
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`slug`),
    INDEX (`status`, `published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Création de la table des métadonnées SEO
-- Liée à un article (Relation 1:1)
CREATE TABLE IF NOT EXISTS `seo_metadata` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `article_id` INT NOT NULL UNIQUE,
    `meta_title` VARCHAR(70),            -- Recommandé < 70 caractères
    `meta_description` VARCHAR(160),     -- Recommandé < 160 caractères
    `meta_robots` VARCHAR(50) DEFAULT 'index, follow',
    `canonical_url` VARCHAR(255),
    FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SCRIPT DE SEED (DONNÉES D'EXEMPLE)
-- ============================================================

-- Insertion d'un admin par défaut (Password: 'admin123' - hashé ici)
-- Note: Dans ton code PHP, utilise password_hash() pour générer le hash.
INSERT INTO `users` (`username`, `password_hash`) 
VALUES ('admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe.9W6I.B1qI7vV8T1S5X5f8D8d8D8d8D');

-- Insertion d'un article d'exemple sur la Guerre en Iran
INSERT INTO `articles` (`title`, `slug`, `content`, `image_alt`) 
VALUES (
    'Les enjeux géopolitiques en Iran en 2026', 
    'enjeux-geopolitiques-iran-2026', 
    '<p>Contenu détaillé sur la situation actuelle...</p><h2>Historique du conflit</h2><p>Détails...</p>',
    'Carte stratégique des tensions en Iran'
);

-- Insertion des métadonnées SEO correspondantes
INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`) 
VALUES (
    1, 
    'Guerre en Iran 2026 : Enjeux et Analyse Géopolitique', 
    'Découvrez notre analyse complète sur les tensions actuelles et la guerre en Iran. Décryptage des enjeux de puissance pour l\'année 2026.'
);

-- Articles supplémentaires pour valider le tri DESC en Front-Office
INSERT INTO `articles` (`title`, `slug`, `content`, `image_alt`, `status`, `published_at`)
VALUES
(
    'Chronologie des tensions diplomatiques (Mars 2026)',
    'chronologie-tensions-diplomatiques-mars-2026',
    '<p>Chronologie des événements diplomatiques les plus marquants de mars 2026.</p>',
    'Chronologie des tensions diplomatiques en Iran',
    'published',
    '2026-03-28 09:30:00'
),
(
    'Impact économique régional : pétrole et sanctions',
    'impact-economique-petrole-sanctions',
    '<p>Analyse des effets économiques régionaux liés au pétrole et aux sanctions internationales.</p>',
    'Analyse économique des sanctions en Iran',
    'published',
    '2026-03-29 15:45:00'
),
(
    'Scénarios possibles pour la stabilité en 2027',
    'scenarios-stabilite-iran-2027',
    '<p>Présentation de scénarios de stabilisation politique et sécuritaire pour 2027.</p>',
    'Scénarios de stabilité pour l Iran en 2027',
    'draft',
    '2026-03-30 08:00:00'
);

-- Métadonnées SEO des nouveaux articles publiés
INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`)
VALUES
(
    2,
    'Chronologie Iran Mars 2026 : points clés',
    'Retrouvez la chronologie complète des tensions diplomatiques en Iran sur mars 2026.'
),
(
    3,
    'Iran : impacts économiques du pétrole et des sanctions',
    'Comprenez les conséquences économiques régionales des sanctions et des tensions pétrolières autour de l Iran.'
),
(
    4,
    'Scénarios Iran 2027 : stabilité et risques',
    'Tour d horizon des scénarios de stabilité et des principaux risques géopolitiques pour 2027.'
);