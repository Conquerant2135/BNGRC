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
  montant_totale DECIMAL(10, 3) NOT NULL DEFAULT 0,
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