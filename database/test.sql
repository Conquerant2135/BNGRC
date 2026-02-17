
--disponible  (reste >0 )
SELECT d.id_don, d.donateur, d.date_don, d.id_article, d.quantite,
       a.nom AS article_nom, u.libelle AS unite,
       COALESCE(att_sum.total_attribue, 0) AS deja_attribue
FROM bngrc_don d
JOIN bngrc_article a ON a.id = d.id_article
LEFT JOIN bngrc_unite u ON u.id = a.id_unite
LEFT JOIN (
  SELECT id_don, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_don
) att_sum ON att_sum.id_don = d.id_don
WHERE (d.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY d.date_don ASC, d.id_don ASC;
-- besoins non satisfaits
SELECT b.id_besoin, b.id_article, b.id_ville, b.quantite, b.date_demande,
       v.nom_ville, a.nom AS article_nom,
       COALESCE(att_sum.total_attribue, 0) AS deja_attribue
FROM bngrc_besoin b
JOIN bngrc_ville v ON v.id_ville = b.id_ville
JOIN bngrc_article a ON a.id = b.id_article
LEFT JOIN (
  SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_besoin
) att_sum ON att_sum.id_besoin = b.id_besoin
WHERE b.est_satisfait = 0
  AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY b.date_demande ASC, b.id_besoin ASC;
 
--insertion des attributions : 
INSERT INTO bngrc_attribution_don (id_don, id_besoin, quantite_attribuee, date_attribution)
VALUES (:id_don, :id_besoin, :qte, CURDATE());

-- marquer besoin satisfait 
UPDATE bngrc_besoin b
SET est_satisfait = 1
WHERE (
  SELECT COALESCE(SUM(a.quantite_attribuee), 0)
  FROM bngrc_attribution_don a
  WHERE a.id_besoin = b.id_besoin
) >= b.quantite;

-- marquer don ditribue : 
UPDATE bngrc_don d
SET id_etat = {id_etat}
WHERE (
  SELECT COALESCE(SUM(a.quantite_attribuee), 0)
  FROM bngrc_attribution_don a
  WHERE a.id_don = d.id_don
) >= d.quantite;

-- tableau de bord 
SELECT v.id_ville, v.nom_ville, r.nom AS region, v.nb_sinistres,
       c.nom AS categorie,
       COALESCE(SUM(b.quantite), 0) AS total_besoin,
       COALESCE(SUM(att.total_attribue), 0) AS total_recu
FROM bngrc_ville v
JOIN bngrc_region r ON r.id = v.id_region
LEFT JOIN bngrc_besoin b ON b.id_ville = v.id_ville
LEFT JOIN bngrc_article a ON a.id = b.id_article
LEFT JOIN bngrc_categorie c ON c.id = a.id_cat
LEFT JOIN (
  SELECT ad.id_besoin, SUM(ad.quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don ad
  GROUP BY ad.id_besoin
) att ON att.id_besoin = b.id_besoin
WHERE v.nb_sinistres > 0
GROUP BY v.id_ville, v.nom_ville, r.nom, v.nb_sinistres, c.nom
ORDER BY v.nom_ville, c.nom;

-- mode stock : prioriser les besoins les plus petits (quantite)
SELECT b.id_besoin, b.id_article, b.quantite, b.date_demande,
       COALESCE(att_sum.total_attribue, 0) AS deja_attribue
FROM bngrc_besoin b
LEFT JOIN (
  SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_besoin
) att_sum ON att_sum.id_besoin = b.id_besoin
WHERE b.est_satisfait = 0
  AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY b.quantite ASC,id_besoin ASC;
--+ [ ] Mode proportionnelle (repartition au prorata): 
SELECT b.id_besoin, b.id_article,
       (b.quantite - COALESCE(att_sum.total_attribue, 0)) AS restant
FROM bngrc_besoin b
LEFT JOIN (
  SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_besoin
) att_sum ON att_sum.id_besoin = b.id_besoin
WHERE b.est_satisfait = 0
  AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY b.date_demande ASC, b.id_besoin ASC;
