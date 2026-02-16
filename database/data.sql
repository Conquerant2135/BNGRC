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


