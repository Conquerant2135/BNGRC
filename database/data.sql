-- ============================================================
-- Donnees minimales (seed) pour demarrer l'application
-- Date uniquement (pas d'heure)
-- ============================================================

USE bngrc;

INSERT INTO bngrc_region (id, nom) VALUES
	(1, 'Analamanga'),
	(2, 'Diana')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO bngrc_ville (id_ville, id_region, nom_ville, nb_sinistres) VALUES
	(1, 1, 'Antananarivo Renivohitra', 200),
	(2, 2, 'Antsiranana', 80)
ON DUPLICATE KEY UPDATE nom_ville = VALUES(nom_ville), nb_sinistres = VALUES(nb_sinistres);

INSERT INTO bngrc_unite (id, libelle) VALUES
	(1, 'kg'),
	(2, 'L'),
	(3, 'pieces')
ON DUPLICATE KEY UPDATE libelle = VALUES(libelle);

INSERT INTO bngrc_categorie (id, nom) VALUES
	(1, 'Nourriture'),
	(2, 'Materiaux'),
	(3, 'Argent')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO bngrc_article (id, nom, id_unite, prix_unitaire, id_cat) VALUES
	(1, 'Riz', 1, 2.500, 1),
	(2, 'Huile', 2, 8.000, 1),
	(3, 'Tole', 3, 25.000, 2),
	(4, 'Argent', 3, 1.000, 3)
ON DUPLICATE KEY UPDATE nom = VALUES(nom), prix_unitaire = VALUES(prix_unitaire), id_cat = VALUES(id_cat);

INSERT INTO bngrc_etat_don (id, nom) VALUES
	(1, 'En attente'),
	(2, 'Distribue'),
	(3, 'Partiellement distribue')
ON DUPLICATE KEY UPDATE nom = VALUES(nom);

INSERT INTO bngrc_traboina (id, nom, adresse, numero) VALUES
	(1, 'Centre Aide Analamanga', 'Antananarivo', '034 00 000 01')
ON DUPLICATE KEY UPDATE nom = VALUES(nom), adresse = VALUES(adresse), numero = VALUES(numero);

INSERT INTO bngrc_besoin (id_besoin, id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait) VALUES
	(1, 1, 1, 200, 500.000, 1, '2026-02-01', 0),
	(2, 3, 1, 40, 1000.000, 1, '2026-02-02', 0),
	(3, 2, 2, 60, 480.000, 1, '2026-02-03', 0)
ON DUPLICATE KEY UPDATE quantite = VALUES(quantite), montant_totale = VALUES(montant_totale), est_satisfait = VALUES(est_satisfait);

INSERT INTO bngrc_don (id_don, donateur, date_don, id_cat, id_article, quantite, id_etat) VALUES
	(1, 'Association A', '2026-02-05', 1, 1, 150, 1),
	(2, 'ONG B', '2026-02-06', 2, 3, 50, 1),
	(3, 'Entreprise C', '2026-02-07', 1, 2, 80, 1)
ON DUPLICATE KEY UPDATE quantite = VALUES(quantite), id_etat = VALUES(id_etat);

INSERT INTO bngrc_montant_taxe (id, valeur) VALUES
	(1, 10.000)
ON DUPLICATE KEY UPDATE valeur = VALUES(valeur);

