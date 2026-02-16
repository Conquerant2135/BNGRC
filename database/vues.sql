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
