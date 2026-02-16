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
 	besoin :
 		+ [ ] id_besoin 
 		+ [ ] type { nature , materiaux ,argent}
 		+ [ ] nom [ riz ,  huile ,tole ,clou  ]
 		+ [ ] prix unitaire fixe 
 		+ [ ] quantite 
 		+ [ ] montant totale 
 		
 	Don : 
 
		+ [ ] id_don 
		+ [ ] date_don

		+ [ ] type (nature / materiaux / argent)

		+ [ ] nom

		+ [ ] quantite			//diminue a chaque 

  		+ [ ] montant (si argent)
  		
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