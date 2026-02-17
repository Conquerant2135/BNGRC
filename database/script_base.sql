CREATE DATABASE IF NOT EXISTS bngrc CHARACTER
SET
  utf8mb4 COLLATE utf8mb4_unicode_ci;

USE bngrc;

CREATE TABLE bngrc_region (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(50) NOT NULL
);

CREATE TABLE bngrc_ville (
  id_ville INT AUTO_INCREMENT PRIMARY KEY,
  id_region INT,
  nom_ville VARCHAR(100) NOT NULL,
  nb_sinistres INT DEFAULT 0,
  CONSTRAINT chk_sinistres CHECK (nb_sinistres >= 0),
  CONSTRAINT fk_region FOREIGN KEY (id_region) REFERENCES bngrc_region (id)
);

CREATE TABLE bngrc_unite (
  id INT AUTO_INCREMENT PRIMARY KEY,
  libelle VARCHAR(25) NOT NULL
);

CREATE TABLE bngrc_categorie (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL
);

CREATE TABLE bngrc_article (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  id_unite INT,
  prix_unitaire DECIMAL(10, 3) NOT NULL,
  id_cat INT,
  CONSTRAINT fk_unite FOREIGN KEY (id_unite) REFERENCES bngrc_unite (id),
  CONSTRAINT fk_categorie_article FOREIGN KEY (id_cat) REFERENCES bngrc_categorie (id)
);

CREATE TABLE bngrc_traboina (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100) NOT NULL,
  adresse VARCHAR(255) NOT NULL,
  numero VARCHAR(20) NOT NULL
);


CREATE TABLE bngrc_besoin (
  id_besoin INT AUTO_INCREMENT PRIMARY KEY,
  id_article INT,
  id_ville INT,
  quantite DECIMAL(15, 3) NOT NULL,
  montant_totale DECIMAL(20, 3) NOT NULL DEFAULT 0,
  id_traboina INT,
  date_demande DATE NOT NULL,
  est_satisfait TINYINT(1) NOT NULL DEFAULT 0,
  ordre INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_article FOREIGN KEY (id_article) REFERENCES bngrc_article (id),
  CONSTRAINT fk_ville FOREIGN KEY (id_ville) REFERENCES bngrc_ville (id_ville),
  CONSTRAINT fk_traboina FOREIGN KEY (id_traboina) REFERENCES bngrc_traboina (id)
);


CREATE TABLE bngrc_etat_don (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(50) NOT NULL
);

CREATE TABLE bngrc_don (
  id_don INT AUTO_INCREMENT PRIMARY KEY,
  donateur VARCHAR(100) NOT NULL,
  date_don DATE NOT NULL,
  id_cat INT,
  id_article INT,
  quantite DECIMAL(15, 3) NOT NULL,
  id_etat INT,
  CONSTRAINT fk_categorie FOREIGN KEY (id_cat) REFERENCES bngrc_categorie (id),
  CONSTRAINT fk_article_don FOREIGN KEY (id_article) REFERENCES bngrc_article (id),
  CONSTRAINT fk_etat_don FOREIGN KEY (id_etat) REFERENCES bngrc_etat_don (id)
);

CREATE TABLE bngrc_attribution_don (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_don INT NOT NULL,
  id_besoin INT NOT NULL,
  quantite_attribuee DECIMAL(15, 3) NOT NULL,
  date_attribution DATE NOT NULL,
  CONSTRAINT fk_attrib_don FOREIGN KEY (id_don) REFERENCES bngrc_don (id_don),
  CONSTRAINT fk_attrib_besoin FOREIGN KEY (id_besoin) REFERENCES bngrc_besoin (id_besoin)
);

CREATE TABLE bngrc_stock (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_article INT,
  quantite DECIMAL(15, 3) NOT NULL,
  CONSTRAINT fk_stock_article FOREIGN KEY (id_article) REFERENCES bngrc_article (id)
);

CREATE TABLE bngrc_montant_taxe (
  id INT AUTO_INCREMENT PRIMARY KEY , 
  valeur DECIMAL(15,3)
);

CREATE TABLE bngrc_achat_produit (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_article INT,
  quantite DECIMAL(15,3) NOT NULL,
  valeur_taux DECIMAL(5,2) NOT NULL DEFAULT 0,
  montant_total DECIMAL(12,3) NOT NULL,
  date_achat DATE NOT NULL,
  CONSTRAINT fk_id_article FOREIGN KEY  (id_article) REFERENCES bngrc_article(id)
);

USE bngrc;

-- Region (simple)
INSERT INTO bngrc_region (id, nom) VALUES
  (1, 'Region-1');

-- Villes
INSERT INTO bngrc_ville (id_ville, id_region, nom_ville, nb_sinistres) VALUES
  (1, 1, 'Toamasina', 1),
  (2, 1, 'Mananjary', 1),
  (3, 1, 'Farafangana', 1),
  (4, 1, 'Nosy Be', 1),
  (5, 1, 'Morondava', 1);

-- Unites
INSERT INTO bngrc_unite (id, libelle) VALUES
  (1, 'kg'),
  (2, 'L'),
  (3, 'unite'),
  (4, 'Ariary');

-- Categories
INSERT INTO bngrc_categorie (id, nom) VALUES
  (1, 'nature'),
  (2, 'materiel'),
  (3, 'argent');

-- Articles
INSERT INTO bngrc_article (id, nom, id_unite, prix_unitaire, id_cat) VALUES
  (1, 'Riz (kg)', 1, 3000, 1),
  (2, 'Eau (L)', 2, 1000, 1),
  (3, 'Huile (L)', 2, 6000, 1),
  (4, 'Haricots', 1, 4000, 1),
  (5, 'Tôle', 3, 25000, 2),
  (6, 'Bâche', 3, 15000, 2),
  (7, 'Clous (kg)', 1, 8000, 2),
  (8, 'Bois', 3, 10000, 2),
  (9, 'groupe', 3, 6750000, 2),
  (10, 'Argent', 4, 1, 3);

-- Besoins (données demandées)
-- ...existing code...

INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait, ordre) VALUES
  (1, 1, 800, 2400000, NULL, '2026-02-16', 0, 17),
  (2, 1, 1500, 1500000, NULL, '2026-02-15', 0, 4),
  (5, 1, 120, 3000000, NULL, '2026-02-16', 0, 23),
  (6, 1, 200, 3000000, NULL, '2026-02-15', 0, 1),
  (10, 1, 12000000, 12000000, NULL, '2026-02-16', 0, 12),

  (1, 2, 500, 1500000, NULL, '2026-02-15', 0, 9),
  (3, 2, 120, 720000, NULL, '2026-02-16', 0, 25),
  (5, 2, 80, 2000000, NULL, '2026-02-15', 0, 6),
  (7, 2, 60, 480000, NULL, '2026-02-16', 0, 19),
  (10, 2, 6000000, 6000000, NULL, '2026-02-15', 0, 3),

  (1, 3, 600, 1800000, NULL, '2026-02-16', 0, 21),
  (2, 3, 1000, 1000000, NULL, '2026-02-15', 0, 14),
  (6, 3, 150, 2250000, NULL, '2026-02-16', 0, 8),
  (8, 3, 100, 1000000, NULL, '2026-02-15', 0, 26),
  (10, 3, 8000000, 8000000, NULL, '2026-02-16', 0, 10),

  (1, 4, 300, 900000, NULL, '2026-02-15', 0, 5),
  (4, 4, 200, 800000, NULL, '2026-02-16', 0, 18),
  (5, 4, 40, 1000000, NULL, '2026-02-15', 0, 2),
  (7, 4, 30, 240000, NULL, '2026-02-16', 0, 24),
  (10, 4, 4000000, 4000000, NULL, '2026-02-15', 0, 7),

  (1, 5, 700, 2100000, NULL, '2026-02-16', 0, 11),
  (2, 5, 1200, 1200000, NULL, '2026-02-15', 0, 20),
  (6, 5, 180, 2700000, NULL, '2026-02-16', 0, 15),
  (8, 5, 150, 1500000, NULL, '2026-02-15', 0, 22),
  (10, 5, 10000000, 10000000, NULL, '2026-02-16', 0, 13),

  (9, 1, 3, 20250000, NULL, '2026-02-15', 0, 16);

-- ...existing code...
  -- ...existing code...

INSERT INTO bngrc_etat_don (id, nom) VALUES
  (1, 'En attente'),
  (2, 'Distribué'),
  (3, 'Partiellement distribué')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);


-- ...existing code...

INSERT INTO bngrc_don (donateur, date_don, id_cat, id_article, quantite, id_etat) VALUES
  ('Jean', '2026-02-16',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 5000000, 1),
  ('Jean', '2026-02-16',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 3000000, 1),
  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 4000000, 1),
  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 1500000, 1),
  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 6000000, 1),
  ('Jean', '2026-02-16',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Riz (kg)'), 400, 1),
  ('Jean', '2026-02-16',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Eau (L)'), 600, 1),

  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='materiel'),
   (SELECT id FROM bngrc_article WHERE nom='Tôle'), 50, 1),
  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='materiel'),
   (SELECT id FROM bngrc_article WHERE nom='Bâche'), 70, 1),

  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Haricots'), 100, 1),

  ('Jean', '2026-02-18',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Riz (kg)'), 2000, 1),
  ('Jean', '2026-02-18',
   (SELECT id FROM bngrc_categorie WHERE nom='materiel'),
   (SELECT id FROM bngrc_article WHERE nom='Tôle'), 300, 1),
  ('Jean', '2026-02-18',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Eau (L)'), 5000, 1),

  ('Jean', '2026-02-19',
   (SELECT id FROM bngrc_categorie WHERE nom='argent'),
   (SELECT id FROM bngrc_article WHERE nom='Argent'), 20000000, 1),

  ('Jean', '2026-02-19',
   (SELECT id FROM bngrc_categorie WHERE nom='materiel'),
   (SELECT id FROM bngrc_article WHERE nom='Bâche'), 500, 1),

  ('Jean', '2026-02-17',
   (SELECT id FROM bngrc_categorie WHERE nom='nature'),
   (SELECT id FROM bngrc_article WHERE nom='Haricots'), 88, 1);

-- ...existing code...
-- Vue pour afficher les villes et le nombre de sinistres par région
CREATE OR REPLACE VIEW vue_villes_par_region AS
SELECT 
    r.nom,
    r.id,
    v.id_ville,
    v.nom_ville AS ville,
    v.nb_sinistres
FROM bngrc_region r
JOIN bngrc_ville v ON r.id = v.id_region
ORDER BY r.nom, v.nom_ville;

CREATE OR REPLACE VIEW v_liste_dons AS  
SELECT d.id_don, d.donateur, d.date_don, d.id_cat, c.nom AS categorie,
        d.id_article, a.nom AS article, d.quantite,
        d.id_etat, e.nom AS etat
FROM bngrc_don d
LEFT JOIN bngrc_categorie c ON d.id_cat = c.id
LEFT JOIN bngrc_article a ON d.id_article = a.id
LEFT JOIN bngrc_etat_don e ON d.id_etat = e.id
ORDER BY d.date_don DESC, d.id_don DESC;

CREATE OR REPLACE VIEW v_total_argent AS
SELECT SUM(quantite) as total FROM bngrc_don bd
WHERE bd.id_cat = (SELECT id FROM bngrc_categorie WHERE nom = 'Argent');

CREATE OR REPLACE VIEW v_total_depense AS
SELECT COALESCE(SUM(bad.quantite_attribuee),0)
FROM bngrc_attribution_don bad
JOIN bngrc_besoin bb ON bb.id_besoin = bad.id_besoin 
JOIN bngrc_article ba ON ba.id = bb.id_article
WHERE ba.id_cat = (SELECT id FROM bngrc_categorie WHERE nom = 'Argent');

CREATE OR REPLACE VIEW v_total_taxe AS
SELECT SUM(COALESCE( 
  bap.montant_total - (bap.montant_total / ( 1 + (bap.valeur_taux / 100) )) , 0 
  ))
   AS total_taxe
FROM bngrc_achat_produit bap;

