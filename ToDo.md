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
+  [ ] Base de donnee:
   +  [X] region 
         +  [X] id_region
         +  [X] nom_region
   +  [X] ville 
		 +  [X] id_ville
		 +  [X] nom_ville
		 +  [X] id_region
		 +  [X] nb_sinistres


	+ [X] unite :
		+ [X] id 
		+ [X] libelle

	+ [X] categorie :
		+ [X] id
		+ [X] nom

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
 		+ [ ] montant_total 
		+ [ ] id_traboina
		+ [ ] date_demande
		+ [ ] est_satisfait

 	don :
		+ [ ] id_don
		+ [ ] donateur 
		+ [ ] date_don
		+ [ ] id_ville
		+ [ ] id_article
		+ [ ] quantite

<!-- gestion du dispatch -->

	attribution_don :
		+ [ ] id
		+ [ ] id_don  
		+ [ ] id_besoin
		+ [ ] quantite
		+ [ ] date_attribution 

	stock : 
		+ [ ] id
		+ [ ] id_article
		+ [ ] quantite

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


