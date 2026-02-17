
+ [X] Simulation discpatcher :
    + [X] 3 modes :
   
      + [X] mode date (FIFO)  : simule les besoins les plus anciens orde de date et de saisie 
        + [X] exemple : 
          + [X] besoin 1 : date 01/01/2024  1 er 
          + [X] besoin 2 : date 02/01/2024  2 eme 
          + [X] besoin 3 : date 03/01/2024  3eme 
      + [X] mode stock : simule les besoins de plus  petites quantités (quantité croissante petites qunatites d abord, date inutile)
        + [X] besoin 1 : date 01/01/2024   Quantite  5 --> position 2 eme
          + [X] besoin 2 : date 02/01/2024  Quanntite 1 --> position 1 er  
          + [X] besoin 3 : date 03/01/2024  Qunatite 6 --> position 3 eme  
      + [X] mode proportionnelle : simule les besoins en fonction de la quantité demandée :6   répartis au prorata (avec arrondi inférieur)
        + [X] exemple :reparti quantite 6 
          + [X] besoin 1 : 1 --> 1/6 = arrondissement inferieur 
          + [X] besoin 2 : 3 --> 3/6 = arrondissement inferieur 
          + [X] besoin 3 : 6  --> 6/6 = arraondissement inferieur  
+ [X] test.sql   (deja fait )
+ [X] Dons disponibles: calcule le restant d’un don (quantité don - attribuée).
+ [X] Besoins non satisfaits: filtre les besoins encore ouverts.
+ [X] Insertion attribution: logge les dispatchs dans bngrc_attribution_don.
+ [X] Marquer satisfait: met est_satisfait = 1 quand attribution >= besoin.
+ [X] Marquer don distribué: met l’état du don à “Distribué” si totalement utilisé.
+ [X] Tableau de bord: agrège besoins vs dons par ville et catégorie.

+[X]schema.sql (deja fait)
+ [X] bngrc_don = source
+ [X] bngrc_besoin = demandes
+ [X] bngrc_attribution_don = historique dispatch
+ [X] bngrc_stock = utile pour mode stock si tu veux simuler sur stock total
+ [X] bngrc_achat_produit, bngrc_montant_taxe = achat produit

+ [X] DispacthController 
  + [X] switch($mode) : 
  + [X] fifo → besoins triés par date_demande, id_besoin
  + [X] stock → besoins triés par quantite ASC
  + [X] proportionnelle → regroupe par article et répartis au prorata dans un tour
    + [X] Pour un don (article X, quantité Q) :
    + [X] prends tous les besoins non satisfaits de l'article X
            + [X] totalBesoin = somme(quantites restantes)
            + [X] pour chaque besoin: qteAttrib = floor(Q * besoinRestant / totalBesoin)
            + [X] calcul décimale et arrondi >= 0.5
            + [X] si reste > 0 après arrondi, distribue le reste en FIFO
  
+ [X] Requete SQL :
-- Dons disponibles (FIFO)
SELECT d.id_don, d.id_article, d.quantite, d.date_don,
       COALESCE(att_sum.total_attribue, 0) AS deja_attribue
FROM bngrc_don d
LEFT JOIN (
  SELECT id_don, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_don
) att_sum ON att_sum.id_don = d.id_don
WHERE (d.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY d.date_don ASC, d.id_don ASC;

-- Besoins non satisfaits (FIFO)
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
ORDER BY b.date_demande ASC, b.id_besoin ASC;


+ [X] Mode stock (petites quantites d'abord)

Même logique, mais besoins triés par quantite ASC:
-- Besoins non satisfaits (stock)
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

+ [X] Mode proportionnelle (repartition au prorata):

-- Besoins restants par article (pour prorata)
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

+ [ ]en php 
total = somme(restant)
attribution = floor(don_qte * restant / total)
si reste > 0, repartir le reste en FIFO
Insert / Update communs (tous modes)

INSERT INTO bngrc_attribution_don (id_don, id_besoin, quantite_attribuee, date_attribution)
VALUES (:id_don, :id_besoin, :qte, CURDATE());

UPDATE bngrc_besoin b
SET est_satisfait = 1
WHERE (
  SELECT COALESCE(SUM(a.quantite_attribuee), 0)
  FROM bngrc_attribution_don a
  WHERE a.id_besoin = b.id_besoin
) >= b.quantite;

UPDATE bngrc_don d
SET id_etat = :id_etat
WHERE (
  SELECT COALESCE(SUM(a.quantite_attribuee), 0)
  FROM bngrc_attribution_don a
  WHERE a.id_don = d.id_don
) >= d.quantite;