-- ColocLomé — schéma MySQL 8+
CREATE DATABASE IF NOT EXISTS coloclome CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE coloclome;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS visits;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS listing_photos;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS conversations;
DROP TABLE IF EXISTS neighborhoods;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    role ENUM('student', 'host') NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(80) NOT NULL,
    last_name VARCHAR(80) NOT NULL,
    phone VARCHAR(30) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT 'avatar-default.jpg',
    school VARCHAR(160) DEFAULT NULL,
    program VARCHAR(160) DEFAULT NULL,
    matricule VARCHAR(60) DEFAULT NULL,
    verified TINYINT(1) NOT NULL DEFAULT 0,
    lifestyle ENUM('calm', 'social', 'party') DEFAULT 'calm',
    budget_max INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE neighborhoods (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    zone VARCHAR(80) NOT NULL,
    details VARCHAR(190) DEFAULT NULL
);

CREATE TABLE listings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    host_id INT UNSIGNED NOT NULL,
    neighborhood_id INT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(190) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    housing_type VARCHAR(80) NOT NULL,
    room_surface DECIMAL(6,1) NOT NULL,
    rooms_total TINYINT DEFAULT 3,
    rent INT NOT NULL,
    charges INT NOT NULL DEFAULT 0,
    deposit_months TINYINT NOT NULL DEFAULT 2,
    water_included TINYINT(1) NOT NULL DEFAULT 1,
    fiber TINYINT(1) NOT NULL DEFAULT 1,
    cash_power ENUM('individual', 'shared') DEFAULT 'individual',
    ceet_included TINYINT(1) NOT NULL DEFAULT 0,
    walk_minutes TINYINT DEFAULT 12,
    lifestyle ENUM('calm', 'social', 'party') DEFAULT 'calm',
    gender_policy ENUM('mixed', 'female', 'male') DEFAULT 'mixed',
    smoker TINYINT(1) NOT NULL DEFAULT 0,
    verified TINYINT(1) NOT NULL DEFAULT 1,
    status ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    match_score TINYINT DEFAULT 90,
    available_from DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (host_id) REFERENCES users(id),
    FOREIGN KEY (neighborhood_id) REFERENCES neighborhoods(id)
);

CREATE TABLE listing_photos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    filename VARCHAR(190) NOT NULL,
    alt VARCHAR(190) DEFAULT NULL,
    sort_order TINYINT DEFAULT 0,
    FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
);

CREATE TABLE conversations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    host_id INT UNSIGNED NOT NULL,
    last_message_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uniq_conv (listing_id, student_id),
    FOREIGN KEY (listing_id) REFERENCES listings(id),
    FOREIGN KEY (student_id) REFERENCES users(id),
    FOREIGN KEY (host_id) REFERENCES users(id)
);

CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT UNSIGNED NOT NULL,
    sender_id INT UNSIGNED NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES users(id)
);

CREATE TABLE applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    listing_id INT UNSIGNED NOT NULL,
    student_id INT UNSIGNED NOT NULL,
    conversation_id INT UNSIGNED DEFAULT NULL,
    status ENUM('pending', 'accepted', 'visit', 'rejected', 'waitlist') NOT NULL DEFAULT 'pending',
    match_score TINYINT DEFAULT 80,
    note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (listing_id) REFERENCES listings(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE visits (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL,
    scheduled_at DATETIME NOT NULL,
    location VARCHAR(190) NOT NULL,
    code VARCHAR(20) DEFAULT NULL,
    status ENUM('proposed', 'confirmed', 'done', 'cancelled') NOT NULL DEFAULT 'proposed',
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE
);

-- mot de passe commun : password
-- hash bcrypt
INSERT INTO users (role, email, password, first_name, last_name, phone, avatar, school, program, matricule, verified, lifestyle, budget_max) VALUES
('host', 'koffi.mensah@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Koffi', 'Mensah', '+228 90 12 34 56', 'koffi.jpg', 'Université de Lomé', 'Master Économie', NULL, 1, 'calm', NULL),
('host', 'afiwa.d@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Afiwa', 'Dossou', '+228 91 00 11 22', 'avatar-2.jpg', 'Université de Lomé', 'Licence FASEG', NULL, 1, 'social', NULL),
('host', 'messan.e@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Messan', 'E.', '+228 92 33 44 55', 'avatar-3.jpg', 'CHU Sylvanus Olympio', '6e année Médecine', NULL, 1, 'calm', NULL),
('host', 'babatounde.t@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Babatoundé', 'T.', '+228 93 66 77 88', 'avatar-4.jpg', 'Université de Lomé', 'Doctorant Droit Public', NULL, 1, 'calm', NULL),
('student', 'etudiant@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kodjo', 'Amegah', '+228 70 11 22 33', 'koffi.jpg', 'Université de Lomé', 'FASEG — Économie', 'UL-2024-8841', 1, 'calm', 40000),
('student', 'folly.lawson@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Folly', 'Lawson', '+228 70 44 55 66', 'avatar-5.jpg', 'Université de Lomé', 'L3 Sciences Économiques', 'UL-2023-1022', 1, 'calm', 45000),
('student', 'akossiva.teko@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Akossiwa', 'Téko', '+228 70 77 88 99', 'avatar-6.jpg', 'FDD Université de Lomé', 'M1 Droit des Affaires', 'UL-2022-4410', 1, 'calm', 45000),
('student', 'koffi.edoh@coloclome.tg', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Koffi', 'Edoh', '+228 71 12 13 14', 'avatar-7.jpg', 'Université de Lomé', 'L2 Informatique Appliquée', 'UL-2024-2211', 1, 'social', 45000);

INSERT INTO neighborhoods (name, slug, zone, details) VALUES
('Campus Universitaire de Lomé', 'campus-ul', 'Nord', 'Amphi, Facs, Campus Nord/Sud'),
('Adidogomé / Amadahomè', 'adidogome', 'Ouest', 'Proximité IPNETP, EAMAU'),
('Agoè-Nyivé / Carrefour 2 Lions', 'agoe-nyive', 'Nord', 'Accès rapide rocade & minibus'),
('Tokoin / Hôpital', 'tokoin', 'Centre', 'FSS, CHU Sylvanus Olympio'),
('Bè-Kpota / Hédzranawoé', 'be-kpota', 'Est', 'Bè-Kpota, Hédzranawoé, Kégué');

INSERT INTO listings (host_id, neighborhood_id, title, slug, description, housing_type, room_surface, rooms_total, rent, charges, deposit_months, water_included, fiber, cash_power, ceet_included, walk_minutes, lifestyle, verified, match_score, available_from) VALUES
(1, 2, 'Grande chambre climatisée dans villa étudiante 3 pièces - Adidogomé', 'chambre-clim-villa-adidogome',
 'Logement convenant aux étudiants dans une villa calme à Adidogomé Amadahomè, à 100 mètres du point de ramassage moto et à 12 minutes du campus UL. Chambre individuelle climatisée, bureau d’études et accès aux espaces communs (cuisine, terrasse). Bail ColocLomé, caution séquestrée, compteur Cash Power individuel.',
 'Villa partagée', 16.0, 3, 38000, 0, 2, 1, 1, 'individual', 0, 12, 'calm', 1, 92, '2024-11-01'),
(2, 3, 'Chambre autonome avec salle d’eau — Agoè-Nyivé', 'chambre-autonome-agoe',
 'Villa moderne avec salon partagé, cuisine aménagée et véranda calme. Quartier sécurisé à 300 mètres des terminus des zemidjans.',
 'Villa', 14.0, 4, 35000, 0, 2, 1, 1, 'individual', 0, 12, 'calm', 1, 95, '2024-10-15'),
(2, 2, 'Chambre dans Duplex clôturé — Adidogomé', 'chambre-duplex-adidogome',
 'Idéal pour étudiant en droit ou sciences économiques cherchant un cadre d’étude discipliné. Charges eau forfaitaires 2 500 FCFA.',
 'Duplex', 12.0, 4, 42500, 2500, 2, 0, 1, 'shared', 0, 6, 'calm', 1, 91, '2024-11-01'),
(3, 4, 'Chambre semi-meublée révision calme — Tokoin Solidarité', 'chambre-tokoin-solidarite',
 'À 5 minutes du CHU Tokoin et ligne directe vers le campus. Environnement très paisible réservé aux étudiants sérieux.',
 'Maison', 11.0, 3, 30000, 0, 2, 1, 0, 'shared', 0, 18, 'calm', 1, 88, '2024-10-20'),
(4, 3, 'Chambre individuelle dans villa F3 — Agoè-Nyivé Sud', 'chambre-villa-f3-agoe',
 'Partage de la villa avec un doctorant en droit. Grand salon commun carrelé, cour extérieure pavée, parking moto sécurisé.',
 'Villa F3', 13.0, 3, 38000, 0, 2, 1, 1, 'shared', 0, 10, 'calm', 1, 86, '2024-11-01');

INSERT INTO listing_photos (listing_id, filename, alt, sort_order) VALUES
(1, 'listing-1a.jpg', 'Chambre climatisée', 1),
(1, 'listing-1b.jpg', 'Bureau d’études', 2),
(1, 'listing-1c.jpg', 'Cuisine partagée', 3),
(2, 'listing-2.jpg', 'Chambre autonome Agoè', 1),
(3, 'listing-3.jpg', 'Chambre duplex', 1),
(4, 'listing-4.jpg', 'Chambre Tokoin', 1),
(5, 'listing-5.jpg', 'Cuisine villa F3', 1);

INSERT INTO conversations (id, listing_id, student_id, host_id, last_message_at) VALUES
(1, 1, 5, 1, NOW()),
(2, 2, 5, 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 4, 5, 3, DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO messages (conversation_id, sender_id, body, created_at) VALUES
(1, 1, 'Salut ! J’ai bien reçu ton dossier ColocLomé. Ton profil d’étudiant en économie colle nickel avec le rythme de révision à la villa. La chambre est disponible à compter du 1er Novembre.', DATE_SUB(NOW(), INTERVAL 90 MINUTE)),
(1, 5, 'Bonjour Koffi, merci pour ton retour rapide. Est-ce que le compteur électrique CEET Cash Power est partagé entre les trois colocataires ou y a-t-il un sous-compteur individuel pour la chambre climatisée ?', DATE_SUB(NOW(), INTERVAL 70 MINUTE)),
(1, 1, 'Chacun a son propre sous-compteur pour la chambre. Pour les espaces communs (cuisine et terrasse extérieure), on divise la recharge CEET en parts égales à la fin de chaque mois via T-Money ou Mixx by Yas.', DATE_SUB(NOW(), INTERVAL 45 MINUTE)),
(1, 1, 'Parfait pour jeudi après ton TD de droit. Je t’envoie la géolocalisation dès que tu as validé ta présence sur le créneau ci-dessus !', DATE_SUB(NOW(), INTERVAL 20 MINUTE)),
(2, 2, 'La caution n’est pas deux mois de loyer comme convenu dans l’accord ColocLomé.', DATE_SUB(NOW(), INTERVAL 1 DAY)),
(3, 3, 'Le reçu de versement a bien été validé par l’admin.', DATE_SUB(NOW(), INTERVAL 2 DAY));

INSERT INTO applications (listing_id, student_id, conversation_id, status, match_score, note) VALUES
(1, 5, 1, 'visit', 94, 'Visite confirmée par le référent'),
(2, 5, 2, 'accepted', 91, 'Créneau proposé'),
(4, 5, 3, 'pending', 88, 'Dossier transmis'),
(5, 5, NULL, 'rejected', 72, 'Non retenu — file collective'),
(1, 6, 1, 'visit', 94, 'CNI et carte UL certifiées'),
(1, 7, NULL, 'pending', 91, 'Passeport et attestation valides'),
(1, 8, NULL, 'pending', 82, 'Carte étudiant 2024 active');

INSERT INTO visits (application_id, scheduled_at, location, code, status) VALUES
(1, '2024-10-24 16:00:00', 'Adidogomé Amadahomè — Devant la pharmacie de la Paix', 'VIS-892', 'confirmed'),
(5, '2024-10-24 16:00:00', 'Adidogomé Amadahomè — Devant la pharmacie de la Paix', 'VIS-892', 'confirmed'),
(6, '2024-10-25 11:30:00', 'Adidogomé Assiyéyé', 'VIS-901', 'proposed'),
(7, '2024-10-26 09:00:00', 'Adidogomé Assiyéyé', 'VIS-910', 'proposed');
