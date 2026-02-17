
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

-- ============================================================
-- MODE PROPORTIONNELLE TESTS
-- ============================================================
-- Logique proportionnelle expliquée:
-- Distribuer Q unités sur N besoins de quantités variées
-- 
-- EXEMPLE: Q=5, besoins=[1, 3, 5], total=9
--
-- ÉTAPE 1 - Calculer FLOOR pour chaque besoin:
--   Besoin 1: floor(1×5/9) = floor(0.556) = 0,  décimale=0.556
--   Besoin 3: floor(3×5/9) = floor(1.667) = 1,  décimale=0.667
--   Besoin 5: floor(5×5/9) = floor(2.778) = 2,  décimale=0.778
--   Total floor = 0+1+2 = 3, Reste = 5-3 = 2
--
-- ÉTAPE 2 - Distribuer le RESTE aux plus grandes décimales:
--   Trier décimales décroissantes: [0.778, 0.667, 0.556]
--   Besoin 5 (décimale 0.778): 2+1 = 3 ✓ (reste: 1)
--   Besoin 3 (décimale 0.667): 1+1 = 2 ✓ (reste: 0)
--   Besoin 1 (décimale 0.556): 0+0 = 0   (reste épuisé)
--
-- RÉSULTAT FINAL: Besoin 1→0, Besoin 3→2, Besoin 5→3
-- VÉRIFICATION: 0+2+3 = 5 ✓ (exactement la quantité!)
--
-- Test 1: Vérifier la répartition proportionnelle par article
-- Exemple: article X, 5 quantités à répartir sur besoins de [1, 3, 5] = total 9
-- Résultat attendu: [0, 2, 3] = 5 exactement

SELECT '=== MODE PROPORTIONNELLE: Besoins non satisfaits par article ===' AS test_name;

SELECT 
    b.id_besoin, 
    b.id_article, 
    b.quantite,
    av.nom AS article_nom,
    b.date_demande,
    COALESCE(att_sum.total_attribue, 0) AS deja_attribue,
    (b.quantite - COALESCE(att_sum.total_attribue, 0)) AS restant
FROM bngrc_besoin b
LEFT JOIN bngrc_article av ON av.id = b.id_article
LEFT JOIN (
  SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
  FROM bngrc_attribution_don
  GROUP BY id_besoin
) att_sum ON att_sum.id_besoin = b.id_besoin
WHERE b.est_satisfait = 0
  AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
ORDER BY b.id_article, b.id_besoin ASC;

-- Test 2: Dons disponibles (pour voir ce qui sera distribué)
SELECT '=== Dons disponibles pour distribution proportionnelle ===' AS test_name;

SELECT d.id_don, d.donateur, d.date_don, d.id_article,
       a.nom AS article_nom, u.libelle AS unite,
       d.quantite,
       COALESCE(att_sum.total_attribue, 0) AS deja_attribue,
       (d.quantite - COALESCE(att_sum.total_attribue, 0)) AS restant
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

-- Test 3: Vérification après simulation proportionnelle
-- (À exécuter après avoir lancer une simulation et validé)
SELECT '=== Historique attributions (après proportionnelle) ===' AS test_name;

SELECT 
    ad.id_attribution,
    ad.id_don,
    d.donateur,
    ad.id_besoin,
    b.quantite AS besoin_original,
    ad.quantite_attribuee,
    ad.date_attribution,
    CASE 
        WHEN b.quantite - COALESCE(att_sum.total_attribue, 0) = 0 THEN 'SATISFAIT'
        ELSE 'EN ATTENTE'
    END AS status
FROM bngrc_attribution_don ad
JOIN bngrc_don d ON d.id_don = ad.id_don
JOIN bngrc_besoin b ON b.id_besoin = ad.id_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
    FROM bngrc_attribution_don
    GROUP BY id_besoin
) att_sum ON att_sum.id_besoin = b.id_besoin
ORDER BY ad.date_attribution DESC, ad.id_attribution DESC;

-- Test 4: Somme des attributions par don (vérifier que rien n'est perdu)
SELECT '=== Vérification: Total attribué par don ===' AS test_name;

SELECT 
    d.id_don,
    d.donateur,
    d.quantite AS quantite_don_total,
    COALESCE(SUM(ad.quantite_attribuee), 0) AS total_attribue,
    CASE 
        WHEN COALESCE(SUM(ad.quantite_attribuee), 0) = d.quantite THEN '✓ OK - Tout distribué'
        WHEN COALESCE(SUM(ad.quantite_attribuee), 0) < d.quantite THEN '⚠ Partiel'
        ELSE '✗ ERREUR - Trop distribué!'
    END AS verification
FROM bngrc_don d
LEFT JOIN bngrc_attribution_don ad ON ad.id_don = d.id_don
GROUP BY d.id_don, d.donateur, d.quantite
ORDER BY d.id_don;
