## Gestion des besoins

## Fonctionalites

- ajout d'un besoin , de n'importe quel type et de quantite
- listing des besoin (sur le tableau de bord)

## Comment

### Utile

- [ ] categorieRepository
  - [ ] liste des categories
- [ ] articleRepository
  - [ ] liste des articles (all)
  - [ ] article par categorie
- [ ] unite repository
  - [ ] liste des unites de mesures

## ajout de besoin

- [ ] traboinaRepository
  - [ ] inserer un traboina pour test

- [ ] besoinRepository
  - [ ] inserer un besoin : INSERT INTO id_article , id_ville , qte , en double , montant (calcul auto) , id_traboina , date_demande , false (car encore non satisfait )

- [ ] besoinService
  - [ ] point de passage entre le repo et le controller

- [ ] besoinController ( page besoins.php )
  - [ ] passage a la page des besoin : route GET / besoin (passer la liste des categories => 'cat' , article => 'article', ville => 'ville' )
  - [ ] recuperation des donnees sur le formulaire d'insertion d'un besoin
