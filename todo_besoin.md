## Gestion des besoins

## Fonctionalites

- ajout d'un besoin , de n'importe quel type et de quantite
- listing des besoin (sur le tableau de bord)

## Comment

### Utile

- [X] categorieRepository
  -[X] liste des categories
-[X] articleRepository
  -[X] liste des articles (all)
  -[X] article par categorie
-[X] unite repository
  -[X] liste des unites de mesures

## ajout de besoin

-[X] traboinaRepository
  -[X] inserer un traboina pour test

-[X] besoinRepository
  -[X] inserer un besoin : INSERT INTO id_article , id_ville , qte , en double , montant (calcul auto) , id_traboina , date_demande , false (car encore non satisfait )

-[X] besoinService
  -[X] point de passage entre le repo et le controller

-[X] besoinController ( page besoins.php )
  -[X] passage a la page des besoin : route GET / besoin (passer la liste des categories => 'cat' , article => 'article', ville => 'ville' )
  -[X] recuperation des donnees sur le formulaire d'insertion d'un besoin
