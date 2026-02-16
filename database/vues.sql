--
SELECT 
    r.nom AS region,
    v.nom_ville AS ville,
    v.nb_sinistres
FROM bngrc_region r
JOIN bngrc_ville v ON r.id = v.id_region
ORDER BY r.nom, v.nom_ville;
