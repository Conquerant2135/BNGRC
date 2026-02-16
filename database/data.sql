-- Insertion de deux régions de Madagascar

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

-- Unites
INSERT INTO bngrc_unite (libelle)
VALUES
    ('kg'),
    ('L');

-- Categories
INSERT INTO bngrc_categorie (nom)
VALUES
    ('Nature'),
    ('Argent');

-- Articles
INSERT INTO bngrc_article (nom, id_unite, prix_unitaire, id_cat)
VALUES
    ('Riz', 1, 4500.000, 1),
    ('Huile', 2, 12000.000, 1);

-- Traboina
INSERT INTO bngrc_traboina (nom, adresse, numero)
VALUES
    ('Rasoa', 'Antananarivo', '0341234567'),
    ('Rakoto', 'Antsiranana', '0327654321');

-- Etats de don
INSERT INTO bngrc_etat_don (nom)
VALUES
    ('En attente'),
    ('Distribue');

-- Besoins
INSERT INTO bngrc_besoin (id_article, id_ville, quantite, montant_totale, id_traboina, date_demande, est_satisfait)
VALUES
    (1, 1, 100.000, 450000.000, 1, '2026-02-10', 0),
    (2, 3, 50.000, 600000.000, 2, '2026-02-11', 1);

-- Dons
INSERT INTO bngrc_don (donateur, date_don, id_cat, id_article, quantite, id_etat)
VALUES
    ('Croix-Rouge Madagascar', '2026-02-12', 1, 1, 200.000, 1),
    ('Banque BOA', '2026-02-13', 2, NULL, 5000000.000, 2);

-- Attribution des dons
INSERT INTO bngrc_attribution_don (id_don, id_besoin, quantite_attribuee, date_attribution)
VALUES
    (1, 1, 80.000, '2026-02-14'),
    (1, 2, 20.000, '2026-02-14');

-- Stock
INSERT INTO bngrc_stock (id_article, quantite)
VALUES
    (1, 120.000),
    (2, 30.000);


