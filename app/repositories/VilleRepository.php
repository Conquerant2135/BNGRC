<?php
class VilleRepository {
  private $pdo;
  public function __construct(PDO $pdo) { $this->pdo = $pdo; }

  public function listAll() {
    $sql = "
      SELECT v.id_ville, v.nom_ville, v.id_region, r.nom AS region, v.nb_sinistres
      FROM bngrc_ville v
      LEFT JOIN bngrc_region r ON v.id_region = r.id
      ORDER BY v.nom_ville ASC
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function findById($id) {
    $st = $this->pdo->prepare("SELECT * FROM bngrc_ville WHERE id_ville = ? LIMIT 1");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function create(array $values) {
    $st = $this->pdo->prepare("
      INSERT INTO bngrc_ville(id_region, nom_ville, nb_sinistres)
      VALUES(?,?,?)
    ");
    $st->execute([
      (int)$values['id_region'],
      (string)$values['nom_ville'],
      (int)$values['nb_sinistres']
    ]);
    return $this->pdo->lastInsertId();
  }

  public function update($id, array $values) {
    $st = $this->pdo->prepare("
      UPDATE bngrc_ville
      SET id_region = ?, nom_ville = ?, nb_sinistres = ?
      WHERE id_ville = ?
    ");
    $st->execute([
      (int)$values['id_region'],
      (string)$values['nom_ville'],
      (int)$values['nb_sinistres'],
      (int)$id
    ]);
    return $st->rowCount();
  }

  public function delete($id) {
    $st = $this->pdo->prepare("DELETE FROM bngrc_ville WHERE id_ville = ?");
    $st->execute([(int)$id]);
    return $st->rowCount();
  }

  public function listRegions() {
    return $this->pdo->query("SELECT id, nom FROM bngrc_region ORDER BY nom")->fetchAll();
  }

  /**
   * Nombre de villes sinistrées (nb_sinistres > 0)
   */
  public function countSinistrees(): int
  {
      return (int) $this->pdo->query("SELECT COUNT(*) FROM bngrc_ville WHERE nb_sinistres > 0")->fetchColumn();
  }
}
