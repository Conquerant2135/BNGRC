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


-- Catégories


-- Articles



-- Categories


-- Articles

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


-- Dons


-- Attribution des dons


-- Stock
INSERT INTO bngrc_stock (id_article, quantite)
VALUES
    (1, 120.000),
    (2, 30.000);


