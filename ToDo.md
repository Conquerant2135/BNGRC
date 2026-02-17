deploiement sur le serveur 
BGNRC 

+[X] suivi des  collectes et des suivies  de dons pour les sinistres 


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
		+[X] prix unitaire  // ne change jamais 
		+[X] quantite

 -------------------
+ [X] Base de donnee:
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
		+[X] id 
		+[X] nom
		+[X] id_unite
		+[X] prix_unitaire
 
	traboina : 
		+[X] id
		+[X] nom 
		+[X] adresse
		+[X] numero

 	besoin :
 		+[X] id_besoin 
		+[X] id_article 
		+[X] id_ville
 		+[X] quantite
 		+[X] montant_total 
		+[X] id_traboina
		+[X] date_demande
		+[X] est_satisfait

	etat_don :
		+[X] id
		+[X] nom

 	don :
		+[X] id_don
		+[X] donateur 
		+[X] date_don
		+[X] id_ville
		+[X] id_article
		+[X] quantite
		+[X] id_etat

<!-- gestion du dispatch -->

	attribution_don :
		+[X] id
		+[X] id_don  
		+[X] id_besoin
		+[X] quantite
		+[X] date_attribution 

	stock : 
		+[X] id
		+[X] id_article
		+[X] quantite

  		  Le sujet dit :

		" On simule le dispatch des dons par ordre de date et de saisie "


		Trier les dons par date ASC

			Pour chaque don :

				chercher les besoins correspondants

				diminuer la quantité du besoin

				enregistrer combien a été attribué
				
	Page Tableau de Bord (obligatoire)



		Ville	Besoins totaux	Dons reçus	Reste à couvrir

			+[X] Ville
	
			+[X] Liste des besoins

			+[X] Quantité restante

			+[X] Total dons attribués



 + [X] Don CRUD 
     + [X] DonRepostitory.php
       + [X] listAll()
       + [X] findById($id)
       + [X] create($data)
	   + [X] update($id, $data)
  	   + [X] delete($id)
  	   + [X] lisCategories()
  	   + [X] listArticles()
  	   + [x] listEtats()
     + [X] DonService.php
       + [X] validate($data)
     + [X] DonController.php
       + [X] index() // Affiche la liste des dons
       + [X] update($id) // Met à jour un don existant
	   + [X] delete($id) // Supprime un don
+ [X]ajout data.sql 

	+ [X] Gestion villes.php (CRUD)
		+ [X] VilleController.php
    		+ [X] index() // Affiche la liste des villes
			+ [X] store() // Crée une nouvelle ville
			+ [X] update($id) // Met à jour une ville existante
			+ [X] delete($id) // Supprime une ville
		+ [X] VilleRepository.php
    		+ [X] listAll()
    		+ [X] findById($id)
    		+ [X] create($data)
    		+ [X] update($id, $data)
			+ [X] delete($id)
			+ [X]listRegions()
		+ [X] VilleService.php
    		+ [X] validate($data)
+ [X]ajout data design css <!-- Sarobidy -->
	+ [x] recupérationn des images utiles
	+ [x] utilisation de bootstrap
	+ [x] mise en page des view
	+ [x] création de son propre template 
+[X] deploiement :
  +[X] http://172.16.7.108/ETU004367/BNGRC 