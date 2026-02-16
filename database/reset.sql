
-- Désactiver les contraintes pour éviter les erreurs de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Vider les tables de données transactionnelles
TRUNCATE TABLE bngrc_attribution_don;
TRUNCATE TABLE bngrc_stock;
TRUNCATE TABLE bngrc_don;
TRUNCATE TABLE bngrc_besoin;

-- Vider les tables de référence
TRUNCATE TABLE bngrc_article;
TRUNCATE TABLE bngrc_traboina;
TRUNCATE TABLE bngrc_ville;
TRUNCATE TABLE bngrc_region;
TRUNCATE TABLE bngrc_categorie;
TRUNCATE TABLE bngrc_unite;
TRUNCATE TABLE bngrc_etat_don;

-- Réactiver les contraintes
SET FOREIGN_KEY_CHECKS = 1;
