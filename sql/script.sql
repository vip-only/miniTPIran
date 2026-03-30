-- ============================================================
-- SCRIPT DE CReATION DE LA BASE DE DONNeES
-- Projet : Mini-projet Web Design (Mars 2026)
-- Cible : MySQL / MariaDB
-- ============================================================

-- 1. Creation de la table des utilisateurs (Administration)
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(20) DEFAULT 'admin',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Creation de la table des articles
CREATE TABLE IF NOT EXISTS `articles` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL UNIQUE, -- Utilise pour l'URL Rewriting
    `content` TEXT NOT NULL,
    `image_url` VARCHAR(255),            -- Chemin vers l'image dans le dossier public
    `image_alt` VARCHAR(155),            -- Crucial pour le SEO (consigne PDF)
    `status` ENUM('draft', 'published') DEFAULT 'published',
    `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX (`slug`),
    INDEX (`status`, `published_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Creation de la table des metadonnees SEO
-- Liee a un article (Relation 1:1)
CREATE TABLE IF NOT EXISTS `seo_metadata` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `article_id` INT NOT NULL UNIQUE,
    `meta_title` VARCHAR(70),            -- Recommande < 70 caractères
    `meta_description` VARCHAR(160),     -- Recommande < 160 caractères
    `meta_robots` VARCHAR(50) DEFAULT 'index, follow',
    `canonical_url` VARCHAR(255),
    FOREIGN KEY (`article_id`) REFERENCES `articles`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SCRIPT DE SEED (DONNeES D'EXEMPLE)
-- ============================================================

-- Insertion d'un admin par defaut (Password: 'admin123' - hashe ici)
-- Note: Dans ton code PHP, utilise password_hash() pour generer le hash.
INSERT INTO `users` (`username`, `password_hash`) 
VALUES ('admin', '$2y$10$HDucGaes3eC/1vMxiv8pnuFD507qAp.X1PhVxfaDEAWUB8fJIlg.a');


INSERT INTO `articles` (`title`, `slug`, `content`, `image_url`, `image_alt`, `status`, `published_at`)
VALUES
(
    'Tensions autour du detroit d Ormuz : pourquoi la zone reste strategique',
    'tensions-detroit-ormuz-zone-strategique',
    '<p>Le detroit d Ormuz reste un point de passage essentiel pour le commerce mondial. Dans le contexte des tensions entre l Iran et ses adversaires regionaux, la zone concentre les risques d incident militaire, de blocage maritime et de hausse brutale des prix de l energie.</p><p>Les analystes soulignent que chaque mouvement naval ou aerien dans cette region est observe de près par les grandes puissances.</p>',
    '/assets/images/uploads/article_20260330_1.jpg',
    'Detroit d Ormuz et tensions regionales',
    'published',
    '2026-03-30 08:00:00'
),
(
    'Nucleaire iranien : les discussions qui bloquent la desescalade',
    'nucleaire-iranien-discussions-bloquent-desescalade',
    '<p>Le dossier nucleaire iranien continue de structurer les rapports de force avec les puissances occidentales. Entre sanctions, inspections et declarations contradictoires, les negociations peinent a avancer.</p><p>Chaque blocage diplomatique alimente la mefiance et renforce le risque d une nouvelle escalade militaire.</p>',
    '/assets/images/uploads/article_20260330_2.jpg',
    'Discussion diplomatique sur le nucleaire iranien',
    'published',
    '2026-03-30 09:00:00'
),
(
    'Sanctions economiques : l impact sur la population et l appareil d etat',
    'sanctions-economiques-impact-population-appareil-etat',
    '<p>Les sanctions internationales pèsent lourdement sur l economie iranienne. Elles affectent les importations, la monnaie, les services publics et le quotidien des familles.</p><p>Le pouvoir cherche a maintenir la stabilite interne malgre une pression economique croissante et durable.</p>',
    '/assets/images/uploads/article_20260330_3.jpg',
    'Effets des sanctions economiques en Iran',
    'published',
    '2026-03-30 10:00:00'
),
(
    'Le role des milices regionales dans l escalade militaire',
    'role-milices-regionales-escalade-militaire',
    '<p>Dans le conflit autour de l Iran, les groupes armes allies ou soutenus dans la region jouent un role central. Leurs actions peuvent provoquer des ripostes en chaîne et elargir le conflit au-dela des frontières iraniennes.</p><p>Cette multiplication des acteurs rend la situation plus difficile a controler.</p>',
    '/assets/images/uploads/article_20260330_4.jpg',
    'Milices regionales et tensions militaires',
    'published',
    '2026-03-30 11:00:00'
),
(
    'Attaques de drones et defense aerienne : la guerre de distance',
    'attaques-drones-defense-aerienne-guerre-distance',
    '<p>Les drones ont profondement transforme les conflits modernes. Autour de l Iran, ils sont utilises pour des frappes ciblees, des reconnaissances et des operations psychologiques.</p><p>En reponse, la defense aerienne se modernise pour intercepter des menaces plus rapides, plus petites et plus difficiles a detecter.</p>',
    '/assets/images/uploads/article_20260330_5.jpg',
    'Defense aerienne face aux drones',
    'published',
    '2026-03-30 12:00:00'
),
(
    'Diplomatie sous pression : les mediations europeennes et asiatiques',
    'diplomatie-sous-pression-mediations-europeennes-asiatiques',
    '<p>Face au risque de conflit ouvert, plusieurs etats tentent de jouer les mediateurs. L Europe et certains acteurs asiatiques proposent des canaux de dialogue pour eviter une nouvelle rupture diplomatique.</p><p>Mais la confiance entre les parties reste faible et les marges de negociation limitees.</p>',
    '/assets/images/uploads/article_20260330_6.jpg',
    'Mediation diplomatique autour de l Iran',
    'published',
    '2026-03-30 13:00:00'
),
(
    'Hydrocarbures et logistique : quand la guerre menace les exportations',
    'hydrocarbures-logistique-guerre-menace-exportations',
    '<p>La guerre et les tensions regionales menacent directement les routes d exportation du petrole et du gaz. Les ports, les raffineries et les infrastructures de transport deviennent des cibles strategiques.</p><p>Cette pression sur la logistique energetique alimente l instabilite des marches internationaux.</p>',
    '/assets/images/uploads/article_20260330_7.jpg',
    'Infrastructures petrolières et logistique',
    'published',
    '2026-03-30 14:00:00'
),
(
    'Cybersecurite et propagande : le front invisible du conflit',
    'cybersecurite-propagande-front-invisible-conflit',
    '<p>Le conflit ne se joue pas seulement sur le terrain militaire. Les cyberattaques, les campagnes de desinformation et la propagande numerique participent aussi a l escalade.</p><p>Les institutions iraniennes comme leurs adversaires renforcent leurs capacites de defense et de communication.</p>',
    '/assets/images/uploads/article_20260330_8.jpg',
    'Cyberattaque et communication de guerre',
    'published',
    '2026-03-30 15:00:00'
),
(
    'equilibre interieur : societe civile, repression et fatigue de guerre',
    'equilibre-interieur-societe-civile-repression-fatigue-guerre',
    '<p>a l interieur du pays, la population subit a la fois les consequences economiques et la pression politique. La societe civile tente de conserver des espaces d expression malgre la surveillance et la repression.</p><p>La fatigue de guerre devient un facteur majeur dans l evolution du climat social.</p>',
    '/assets/images/uploads/article_20260330_9.jpg',
    'Population civile et tensions internes en Iran',
    'published',
    '2026-03-30 16:00:00'
),
(
    'Scenarios de sortie de crise pour l Iran et la region en 2026',
    'scenarios-sortie-crise-iran-region-2026',
    '<p>Plusieurs scenarios sont envisages par les analystes : reprise du dialogue, escalade militaire limitee ou conflit regional prolonge. Chacun depend de facteurs diplomatiques, economiques et militaires.</p><p>La sortie de crise reste incertaine tant que la confiance entre les acteurs demeure fragilisee.</p>',
    '/assets/images/uploads/article_20260330_10.jpg',
    'Scenarios de sortie de crise en Iran',
    'published',
    '2026-03-30 17:00:00'
);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Tensions autour du detroit d Ormuz : enjeu strategique', 'Analyse des enjeux militaires et economiques du detroit d Ormuz dans le contexte des tensions avec l Iran.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'tensions-detroit-ormuz-zone-strategique'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Nucleaire iranien : blocages et desescalade', 'Retour sur les negociations autour du nucleaire iranien et leurs consequences geopolitiques.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'nucleaire-iranien-discussions-bloquent-desescalade'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Sanctions contre l Iran : impacts economiques', 'Comprendre les effets des sanctions economiques sur la population iranienne et les institutions.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'sanctions-economiques-impact-population-appareil-etat'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Milices regionales et escalade militaire', 'Analyse du role des milices regionales dans l elargissement du conflit autour de l Iran.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'role-milices-regionales-escalade-militaire'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Drones et defense aerienne en Iran', 'Les drones et la defense aerienne au cœur de la nouvelle guerre de distance.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'attaques-drones-defense-aerienne-guerre-distance'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Mediation diplomatique autour de l Iran', 'Les mediations europeennes et asiatiques face a la tension croissante avec l Iran.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'diplomatie-sous-pression-mediations-europeennes-asiatiques'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Petrole, logistique et guerre en Iran', 'Les infrastructures energetiques menacees par les tensions militaires dans la region.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'hydrocarbures-logistique-guerre-menace-exportations'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Cybersecurite et propagande : front invisible', 'Le role des cyberattaques et de la propagande numerique dans le conflit iranien.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'cybersecurite-propagande-front-invisible-conflit'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Societe civile et fatigue de guerre en Iran', 'La societe civile iranienne face a la repression et a l usure du conflit.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'equilibre-interieur-societe-civile-repression-fatigue-guerre'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

INSERT INTO `seo_metadata` (`article_id`, `meta_title`, `meta_description`, `meta_robots`, `canonical_url`)
SELECT `id`, 'Scenarios de sortie de crise pour l Iran', 'Trois hypothèses pour l evolution du conflit et les perspectives de stabilisation.', 'index, follow', NULL
FROM `articles` WHERE `slug` = 'scenarios-sortie-crise-iran-region-2026'
ON DUPLICATE KEY UPDATE
`meta_title` = VALUES(`meta_title`), `meta_description` = VALUES(`meta_description`), `meta_robots` = VALUES(`meta_robots`);

