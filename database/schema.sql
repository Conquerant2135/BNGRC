CREATE DATABASE IF NOT EXISTS bngrc;
use bngrc;

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
    CONSTRAINT fk_region FOREIGN KEY (id_region) REFERENCES bngrc_region(id) 
);
