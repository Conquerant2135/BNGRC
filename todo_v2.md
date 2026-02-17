## Gestion d'achat de prouduit

on peut acheter un produit avec les dons en espece . 

Quand on achete un produit on doit acheter ce produit et payer des taxes (fixe )

necessaire : 

table : 

montant_taxe 
 - id 
 - montant (10% ex)

achat_produit :
 - id
 - id_produit
 - qte
 - montant_total
 - valeur_taux
 - date_achat

quand on achete un produit : 

don++ (il y a un don au nom de l'entreprise qui apparait)

on calcule le prix total de la qte et on envoie une erreure si on a pas assez d'argent TTC 

achat_produit ++ on calcule le taux et tout avce la taxe 

+ [x] page achat produit
    + [x] formulaire : choix produit - qte - categorie
+ [x] verification 
    + [x] voir dans si on a assez d'argent - argent_don  - (argent_distr + taxes + achat )
        + [x] vue argent total - argent donnee - total taxe - achat 
    + [x] voir si un achat similaire est deja dans la base
