-- ============================================================
-- Données de test pour la simulation du dispatch
-- À exécuter après schema.sql et data.sql
-- ============================================================

USE bngrc;
INSERT INTO bngrc_region (nom) 
VALUES 
    ('Analamanga'),
    ('Diana');

INSERT INTO bngrc_ville (id_region, nom_ville, nb_sinistres) 
VALUES 
    (1, 'Antananarivo Renivohitra', 450),
    (1, 'Ambohidratrimo', 120);

INSERT INTO bngrc_ville (id_region, nom_ville, nb_sinistres) 
VALUES 
    (2, 'Antsiranana', 85),
    (2, 'Ambilobe', 210);
-- Unités
INSERT INTO bngrc_unite (id, libelle) VALUES
  (1, 'kg'),
  (2, 'L'),
  (3, 'pièces'),
  (4, 'sacs'),
  (5, 'Ar')
ON DUPLICATE KEY UPDATE libelle = VALUES(libelle);

-- Catégories
INSERT INTO bngrc_categorie (id, nom) VALUES
  (1, 'Nourriture'),
  (2, 'Matériaux'),
  (3, 'Argent')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- Articles
INSERT INTO bngrc_article (id, nom, id_unite, prix_unitaire, id_cat) VALUES
  (1, 'Riz',    1, 2.500, 1),
  (2, 'Huile',  2, 8.000, 1),
  (3, 'Eau',    2, 1.000, 1),
  (4, 'Tôle',   3, 25.000, 2),
  (5, 'Clous',  1, 5.000, 2),
  (6, 'Ciment', 4, 30.000, 2),
  (7, 'Bois',   3, 15.000, 2),
  (8, 'Argent', 5, 15.000, 3)
ON DUPLICATE KEY UPDATE nom = VALUES(nom);
INSERT INTO bngrc_article (id, nom, id_unite, prix_unitaire, id_cat) VALUES

(8, 'Argent', 5, 15.000, 3);

-- États de don
INSERT INTO bngrc_etat_don (id, nom) VALUES
  (1, 'En attente'),
  (2, 'Distribué'),
  (3, 'Partiellement distribué')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- Traboina (centres d'aide)
INSERT INTO bngrc_traboina (id, nom, adresse, numero) VALUES
  (1, 'Centre Aide Analamanga',  'Antananarivo',  '034 00 000 01'),
  (2, 'Centre Aide Diana',       'Antsiranana',   '034 00 000 02')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

-- =================== BESOINS ===================
-- Besoins pour Antananarivo Renivohitra (id_ville=1)
INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait) VALUES
  (1, 1, 500,  1250.000, 1, '2026-02-01', 0),   -- 500 kg Riz
  (4, 1, 80,   2000.000, 1, '2026-02-01', 0),   -- 80 pièces Tôle
  (2, 1, 100,  800.000,  1, '2026-02-02', 0);    -- 100 L Huile

-- Besoins pour Ambohidratrimo (id_ville=2)
INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait) VALUES
  (1, 2, 300,  750.000,  1, '2026-02-02', 0),   -- 300 kg Riz
  (6, 2, 50,   1500.000, 1, '2026-02-03', 0);    -- 50 sacs Ciment

-- Besoins pour Antsiranana (id_ville=3)
INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait) VALUES
  (1, 3, 200,  500.000,  2, '2026-02-03', 0),   -- 200 kg Riz
  (4, 3, 40,   1000.000, 2, '2026-02-04', 0),   -- 40 pièces Tôle
  (3, 3, 500,  500.000,  2, '2026-02-04', 0);    -- 500 L Eau

-- Besoins pour Ambilobe (id_ville=4)
INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait) VALUES
  (1, 4, 400,  1000.000, 2, '2026-02-05', 0),   -- 400 kg Riz
  (2, 4, 60,   480.000,  2, '2026-02-05', 0),    -- 60 L Huile
  (7, 4, 100,  1500.000, 2, '2026-02-06', 0);    -- 100 pièces Bois

-- =================== DONS ===================
INSERT INTO bngrc_don (donateur, date_don, id_cat, id_article, quantite, id_etat) VALUES
  ('Particulier anonyme',       '2026-02-05', 1, 1, 600,  1),   -- 600 kg Riz
  ('ONG Care International',    '2026-02-06', 1, 1, 400,  1),   -- 400 kg Riz
  ('Croix-Rouge Madagascar',    '2026-02-07', 2, 4, 100,  1),   -- 100 pièces Tôle
  ('Banque BOA',                '2026-02-08', 1, 2, 120,  1),   -- 120 L Huile
  ('Association Entraide',      '2026-02-09', 2, 6, 30,   1),   -- 30 sacs Ciment
  ('Ambassade de France',       '2026-02-10', 1, 3, 300,  1),   -- 300 L Eau
  ('Entreprise STAR',           '2026-02-11', 1, 1, 200,  1),   -- 200 kg Riz
  ('Don gouvernemental',        '2026-02-12', 2, 7, 80,   1);   -- 80 pièces Bois

INSERT INTO bngrc_stock (id_article, quantite)
VALUES
    (1, 120.000),
    (2, 30.000);

INSERT INTO bngrc_montant_taxe (valeur) VALUES ( 10 );