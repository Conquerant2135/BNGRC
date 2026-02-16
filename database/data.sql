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

-- Unités de mesure
INSERT INTO bngrc_unite (libelle)
VALUES
    ('kg'),
    ('L'),
    ('pièces'),
    ('sacs'),
    ('Ar');

-- Catégories
INSERT INTO bngrc_categorie (nom)
VALUES
    ('nature'),
    ('materiaux'),
    ('argent');

-- Articles
INSERT INTO bngrc_article (nom, id_unite, prix_unitaire, id_cat)
VALUES
    ('Riz', (SELECT id FROM bngrc_unite WHERE libelle = 'kg'), 2500.000, (SELECT id FROM bngrc_categorie WHERE nom = 'nature')),
    ('Huile', (SELECT id FROM bngrc_unite WHERE libelle = 'L'), 8000.000, (SELECT id FROM bngrc_categorie WHERE nom = 'nature')),
    ('Eau', (SELECT id FROM bngrc_unite WHERE libelle = 'L'), 1500.000, (SELECT id FROM bngrc_categorie WHERE nom = 'nature')),
    ('Tôle', (SELECT id FROM bngrc_unite WHERE libelle = 'pièces'), 30000.000, (SELECT id FROM bngrc_categorie WHERE nom = 'materiaux')),
    ('Clous', (SELECT id FROM bngrc_unite WHERE libelle = 'pièces'), 200.000, (SELECT id FROM bngrc_categorie WHERE nom = 'materiaux')),
    ('Ciment', (SELECT id FROM bngrc_unite WHERE libelle = 'sacs'), 35000.000, (SELECT id FROM bngrc_categorie WHERE nom = 'materiaux')),
    ('Bois', (SELECT id FROM bngrc_unite WHERE libelle = 'pièces'), 12000.000, (SELECT id FROM bngrc_categorie WHERE nom = 'materiaux')),
    ('Argent', (SELECT id FROM bngrc_unite WHERE libelle = 'Ar'), 1.000, (SELECT id FROM bngrc_categorie WHERE nom = 'argent'));


