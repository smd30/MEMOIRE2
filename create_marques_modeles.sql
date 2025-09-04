-- Création des tables marques et modèles
CREATE TABLE IF NOT EXISTS `marques` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nom` varchar(191) NOT NULL,
  `pays_origine` varchar(191) DEFAULT NULL,
  `logo_url` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `marques_nom_unique` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `modeles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `marque_id` bigint unsigned NOT NULL,
  `nom` varchar(191) NOT NULL,
  `annee_debut` varchar(191) DEFAULT NULL,
  `annee_fin` varchar(191) DEFAULT NULL,
  `categorie_vehicule` varchar(191) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `modeles_marque_id_nom_index` (`marque_id`, `nom`),
  CONSTRAINT `modeles_marque_id_foreign` FOREIGN KEY (`marque_id`) REFERENCES `marques` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insertion des marques populaires au Sénégal
INSERT INTO `marques` (`nom`, `pays_origine`, `description`, `created_at`, `updated_at`) VALUES
('Toyota', 'Japon', 'Constructeur automobile japonais leader mondial', NOW(), NOW()),
('Nissan', 'Japon', 'Constructeur automobile japonais', NOW(), NOW()),
('Honda', 'Japon', 'Constructeur automobile japonais', NOW(), NOW()),
('Suzuki', 'Japon', 'Constructeur automobile japonais', NOW(), NOW()),
('Mitsubishi', 'Japon', 'Constructeur automobile japonais', NOW(), NOW()),
('Peugeot', 'France', 'Constructeur automobile français', NOW(), NOW()),
('Renault', 'France', 'Constructeur automobile français', NOW(), NOW()),
('Citroën', 'France', 'Constructeur automobile français', NOW(), NOW()),
('Volkswagen', 'Allemagne', 'Constructeur automobile allemand', NOW(), NOW()),
('BMW', 'Allemagne', 'Constructeur automobile allemand de luxe', NOW(), NOW()),
('Mercedes-Benz', 'Allemagne', 'Constructeur automobile allemand de luxe', NOW(), NOW()),
('Audi', 'Allemagne', 'Constructeur automobile allemand de luxe', NOW(), NOW()),
('Hyundai', 'Corée du Sud', 'Constructeur automobile coréen', NOW(), NOW()),
('Kia', 'Corée du Sud', 'Constructeur automobile coréen', NOW(), NOW()),
('Ford', 'États-Unis', 'Constructeur automobile américain', NOW(), NOW()),
('Opel', 'Allemagne', 'Constructeur automobile allemand', NOW(), NOW()),
('Fiat', 'Italie', 'Constructeur automobile italien', NOW(), NOW()),
('Yamaha', 'Japon', 'Constructeur de motos japonais', NOW(), NOW());

-- Insertion des modèles Toyota
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(1, 'Corolla', 'vehicules_particuliers', NOW(), NOW()),
(1, 'Camry', 'vehicules_particuliers', NOW(), NOW()),
(1, 'Hilux', 'transport_marchandises', NOW(), NOW()),
(1, 'Land Cruiser', 'vehicules_particuliers', NOW(), NOW()),
(1, 'Yaris', 'vehicules_particuliers', NOW(), NOW()),
(1, 'Avensis', 'vehicules_particuliers', NOW(), NOW()),
(1, 'Hiace', 'transport_commun', NOW(), NOW()),
(1, 'Coaster', 'transport_commun', NOW(), NOW());

-- Insertion des modèles Nissan
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(2, 'Sunny', 'vehicules_particuliers', NOW(), NOW()),
(2, 'Almera', 'vehicules_particuliers', NOW(), NOW()),
(2, 'Primera', 'vehicules_particuliers', NOW(), NOW()),
(2, 'Patrol', 'vehicules_particuliers', NOW(), NOW()),
(2, 'Navara', 'transport_marchandises', NOW(), NOW()),
(2, 'Urvan', 'transport_commun', NOW(), NOW());

-- Insertion des modèles Honda
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(3, 'Civic', 'vehicules_particuliers', NOW(), NOW()),
(3, 'Accord', 'vehicules_particuliers', NOW(), NOW()),
(3, 'CR-V', 'vehicules_particuliers', NOW(), NOW()),
(3, 'City', 'vehicules_particuliers', NOW(), NOW()),
(3, 'Jazz', 'vehicules_particuliers', NOW(), NOW());

-- Insertion des modèles Peugeot
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(6, '206', 'vehicules_particuliers', NOW(), NOW()),
(6, '207', 'vehicules_particuliers', NOW(), NOW()),
(6, '307', 'vehicules_particuliers', NOW(), NOW()),
(6, '308', 'vehicules_particuliers', NOW(), NOW()),
(6, '406', 'vehicules_particuliers', NOW(), NOW()),
(6, '407', 'vehicules_particuliers', NOW(), NOW()),
(6, 'Partner', 'transport_marchandises', NOW(), NOW()),
(6, 'Boxer', 'transport_marchandises', NOW(), NOW());

-- Insertion des modèles Renault
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(7, 'Clio', 'vehicules_particuliers', NOW(), NOW()),
(7, 'Megane', 'vehicules_particuliers', NOW(), NOW()),
(7, 'Laguna', 'vehicules_particuliers', NOW(), NOW()),
(7, 'Kangoo', 'transport_marchandises', NOW(), NOW()),
(7, 'Master', 'transport_marchandises', NOW(), NOW()),
(7, 'Trafic', 'transport_commun', NOW(), NOW());

-- Insertion des modèles Volkswagen
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(9, 'Golf', 'vehicules_particuliers', NOW(), NOW()),
(9, 'Passat', 'vehicules_particuliers', NOW(), NOW()),
(9, 'Polo', 'vehicules_particuliers', NOW(), NOW()),
(9, 'Jetta', 'vehicules_particuliers', NOW(), NOW()),
(9, 'Tiguan', 'vehicules_particuliers', NOW(), NOW()),
(9, 'Transporter', 'transport_commun', NOW(), NOW());

-- Insertion des modèles BMW
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(10, 'Série 3', 'vehicules_particuliers', NOW(), NOW()),
(10, 'Série 5', 'vehicules_particuliers', NOW(), NOW()),
(10, 'Série 7', 'vehicules_particuliers', NOW(), NOW()),
(10, 'X3', 'vehicules_particuliers', NOW(), NOW()),
(10, 'X5', 'vehicules_particuliers', NOW(), NOW());

-- Insertion des modèles Mercedes-Benz
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(11, 'Classe A', 'vehicules_particuliers', NOW(), NOW()),
(11, 'Classe C', 'vehicules_particuliers', NOW(), NOW()),
(11, 'Classe E', 'vehicules_particuliers', NOW(), NOW()),
(11, 'Classe S', 'vehicules_particuliers', NOW(), NOW()),
(11, 'Sprinter', 'transport_commun', NOW(), NOW()),
(11, 'Vito', 'transport_commun', NOW(), NOW());

-- Insertion des modèles Hyundai
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(13, 'Accent', 'vehicules_particuliers', NOW(), NOW()),
(13, 'Elantra', 'vehicules_particuliers', NOW(), NOW()),
(13, 'Sonata', 'vehicules_particuliers', NOW(), NOW()),
(13, 'Tucson', 'vehicules_particuliers', NOW(), NOW()),
(13, 'Santa Fe', 'vehicules_particuliers', NOW(), NOW()),
(13, 'H100', 'transport_marchandises', NOW(), NOW());

-- Insertion des modèles Ford
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(15, 'Focus', 'vehicules_particuliers', NOW(), NOW()),
(15, 'Mondeo', 'vehicules_particuliers', NOW(), NOW()),
(15, 'Fiesta', 'vehicules_particuliers', NOW(), NOW()),
(15, 'Ranger', 'transport_marchandises', NOW(), NOW()),
(15, 'Transit', 'transport_commun', NOW(), NOW());

-- Insertion des modèles Yamaha (motos)
INSERT INTO `modeles` (`marque_id`, `nom`, `categorie_vehicule`, `created_at`, `updated_at`) VALUES
(18, 'YBR 125', 'deux_trois_roues', NOW(), NOW()),
(18, 'YBR 250', 'deux_trois_roues', NOW(), NOW()),
(18, 'FZ 150', 'deux_trois_roues', NOW(), NOW()),
(18, 'FZ 250', 'deux_trois_roues', NOW(), NOW()),
(18, 'MT-07', 'deux_trois_roues', NOW(), NOW());
