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

	+ [X] article : 
		+ [X] id 
		+ [X] nom
		+ [X] id_unite
		+ [X] prix_unitaire
		+ id_cat

	+ [X]traboina : 
		+ [X] id
		+ [X] nom 
		+ [X] adresse
		+ [X] numero

    + [X] besoin :
 		+ [X] id_besoin 
		+ [X] id_article 
		+ [X] id_ville
 		+ [X] quantite
 		+ [X] montant_totale 
		+ [X] id_traboina
		+ [X] date_demande 

   	+ [X] don :
		+ [X] id_don
		+ [X] donateur 
		+ [X] date_don
		+ [X] id_cat
		+ [X] id_article
		+ [X] quantite

	+ [X] attribution_don :
		+ [X] id 
		+ [X] id_traboina
		+ [X] id_don

	+ [X] stock : 
		+ [X] id
		+ id_article
		+ [X] quantite

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


