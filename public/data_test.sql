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