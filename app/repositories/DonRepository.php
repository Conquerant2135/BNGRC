<?php
class DonRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

  public function listAll() {
    $sql = "
      SELECT * FROM v_liste_dons
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function findById($id) {
    $st = $this->pdo->prepare("SELECT * FROM bngrc_don WHERE id_don = ? LIMIT 1");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function create(array $values) {
    $st = $this->pdo->prepare("
      INSERT INTO bngrc_don(donateur, date_don, id_cat, id_article, quantite, id_etat)
      VALUES(?,?,?,?,?,?)
    ");
    $st->execute([
      (string)$values['donateur'],
      (string)$values['date_don'],
      (int)$values['id_cat'],
      $values['id_article'] !== '' ? (int)$values['id_article'] : null,
      (float)$values['quantite'],
      (int)$values['id_etat']
    ]);
    return $this->pdo->lastInsertId();
  }

  public function update($id, array $values) {
    $st = $this->pdo->prepare("
      UPDATE bngrc_don
      SET donateur = ?, date_don = ?, id_cat = ?, id_article = ?, quantite = ?, id_etat = ?
      WHERE id_don = ?
    ");
    $st->execute([
      (string)$values['donateur'],
      (string)$values['date_don'],
      (int)$values['id_cat'],
      $values['id_article'] !== '' ? (int)$values['id_article'] : null,
      (float)$values['quantite'],
      (int)$values['id_etat'],
      (int)$id
    ]);
    return $st->rowCount();
  }

  public function delete($id) {
    $st = $this->pdo->prepare("DELETE FROM bngrc_don WHERE id_don = ?");
    $st->execute([(int)$id]);
    return $st->rowCount();
  }

  public function listCategories() {
    return $this->pdo->query("SELECT id, nom FROM bngrc_categorie ORDER BY nom")->fetchAll();
  }

  public function listArticles() {
    return $this->pdo->query("SELECT id, nom, id_cat FROM bngrc_article ORDER BY nom")->fetchAll();
  }

  public function listEtats() {
    return $this->pdo->query("SELECT id, nom FROM bngrc_etat_don ORDER BY id")->fetchAll();
  }
}
