-- Vue pour afficher les villes et le nombre de sinistres par région
CREATE OR REPLACE VIEW vue_villes_par_region AS
SELECT 
    r.nom,
    r.id,
    v.id_ville,
    v.nom_ville AS ville,
    v.nb_sinistres
FROM bngrc_region r
JOIN bngrc_ville v ON r.id = v.id_region
ORDER BY r.nom, v.nom_ville;

CREATE OR REPLACE VIEW v_liste_dons AS  
SELECT d.id_don, d.donateur, d.date_don, d.id_cat, c.nom AS categorie,
        d.id_article, a.nom AS article, d.quantite,
        d.id_etat, e.nom AS etat
FROM bngrc_don d
LEFT JOIN bngrc_categorie c ON d.id_cat = c.id
LEFT JOIN bngrc_article a ON d.id_article = a.id
LEFT JOIN bngrc_etat_don e ON d.id_etat = e.id
ORDER BY d.date_don DESC, d.id_don DESC;

CREATE OR REPLACE VIEW v_total_argent AS
SELECT SUM(quantite) as total FROM bngrc_don bd
WHERE bd.id_cat = (SELECT id FROM bngrc_categorie WHERE nom = 'Argent');

CREATE OR REPLACE VIEW v_total_depense AS
SELECT COALESCE(SUM(bad.quantite_attribuee),0)
FROM bngrc_attribution_don bad
JOIN bngrc_besoin bb ON bb.id_besoin = bad.id_besoin 
JOIN bngrc_article ba ON ba.id = bb.id_article
WHERE ba.id_cat = (SELECT id FROM bngrc_categorie WHERE nom = 'Argent');

CREATE OR REPLACE VIEW v_total_taxe AS
SELECT SUM(COALESCE( 
  bap.montant_total - (bap.montant_total / ( 1 + (bap.valeur_taux / 100) )) , 0 
  ))
   AS total_taxe
FROM bngrc_achat_produit bap;

