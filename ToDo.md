deploiement sur le serveur 
BGNRC 

+ [ ] suivi des  collectes et des suivies  de dons pour les sinistres 


region : 
	-ville 1:
		-sinistres  (100)
		-besoin :
			nature :
				-riz
				-huile
				-.. eau potable  
			en materiaux :
				-taule 
				-clou 
			en argent 
				
	-ville 2 :
		-sinistres (50) 
	-ville 3 : 
		
		
	par ville <--> besoin <--> dons
		-dons :
			date et saisie 
			
			
	[ ] page  tableau bord :
		liste villes +(besoins ) +dons attribues a chaque ville
	
	[ ] besoin  possede un prix unitaire 
	
	besoin 
		+ [ ] prix unitaire  // ne change jamais 
		+ [ ] quantite

 -------------------
 	region : 
 		+ [ ] id_ region  
 		+ [ ] plusieurs villes 
 		
 	ville :
 		+ [ ] id_ville 
 		+ [ ] nombres de sinistres  
 		+ [ ] besoins    
 		+ [ ] dons

<!-- c'est ici que les problemes commencent -->

	unite :
		+ [ ] id 
		+ [ ] libelle

	categorie :
		+ [ ] id
		+ [ ] nom

	article : 
		+ [ ] id 
		+ [ ] nom
		+ [ ] id_unite
		+ [ ] prix_unitaire

	traboina : 
		+ [ ] id
		+ [ ] nom 
		+ [ ] adresse
		+ [ ] numero

 	besoin :
 		+ [ ] id_besoin 
		+ [ ] id_article 
		+ [ ] id_ville
 		+ [ ] quantite
 		+ [ ] montant_totale 
		+ [ ] id_traboina
		+ [ ] date_demande 

 	don :
		+ [ ] id_don
		+ [ ] donateur 
		+ [ ] date_don
		+ [ ] id_cat
		+ [ ] id_article
		+ [ ] quantite

	attribution_don :
		+ [ ] id 
		+ [ ] id_traboina
		+ [ ] id_don

	stock : 
		+ [ ] id
		+ [ ] quantite
		+ [ ] 

  		  Le sujet dit :

		" On simule le dispatch des dons par ordre de date et de saisie "


		Trier les dons par date ASC

			Pour chaque don :

				chercher les besoins correspondants

				diminuer la quantité du besoin

				enregistrer combien a été attribué
				
	Page Tableau de Bord (obligatoire)



		Ville	Besoins totaux	Dons reçus	Reste à couvrir

			+ [ ] Ville
	
			+ [ ] Liste des besoins

			+ [ ] Quantité restante

			+ [ ] Total dons attribués