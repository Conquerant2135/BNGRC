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

  /**
   * Nombre total de dons
   */
  public function countAll(): int
  {
      return (int) $this->pdo->query("SELECT COUNT(*) FROM bngrc_don")->fetchColumn();
  }

  /**
   * Derniers dons avec détails (pour le dashboard)
   */
  public function derniers(int $limit = 10): array
  {
      $sql = "SELECT d.date_don, d.donateur, d.quantite,
                     a.nom AS article_nom, u.libelle AS unite,
                     c.nom AS categorie,
                     e.nom AS etat
              FROM bngrc_don d
              JOIN bngrc_article a ON a.id = d.id_article
              LEFT JOIN bngrc_unite u ON u.id = a.id_unite
              LEFT JOIN bngrc_categorie c ON c.id = d.id_cat
              LEFT JOIN bngrc_etat_don e ON e.id = d.id_etat
              ORDER BY d.date_don DESC, d.id_don DESC
              LIMIT " . (int) $limit;
      return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Dons disponibles (avec stock restant) pour le dispatch
   */
  public function disponibles(?string $dateDebut, ?string $dateFin): array
  {
      $sql = "SELECT d.id_don, d.donateur, d.date_don, d.id_article, d.quantite,
                     a.nom AS article_nom, u.libelle AS unite,
                     COALESCE(att_sum.total_attribue, 0) AS deja_attribue
              FROM bngrc_don d
              JOIN bngrc_article a ON a.id = d.id_article
              LEFT JOIN bngrc_unite u ON u.id = a.id_unite
              LEFT JOIN (
                  SELECT id_don, SUM(quantite_attribuee) AS total_attribue
                  FROM bngrc_attribution_don
                  GROUP BY id_don
              ) att_sum ON att_sum.id_don = d.id_don";

      $conditions = [];
      $params     = [];

      if ($dateDebut) {
          $conditions[]          = "d.date_don >= :date_debut";
          $params[':date_debut'] = $dateDebut;
      }
      if ($dateFin) {
          $conditions[]        = "d.date_don <= :date_fin";
          $params[':date_fin'] = $dateFin;
      }
      $conditions[] = "(d.quantite - COALESCE(att_sum.total_attribue, 0)) > 0";

      $sql .= " WHERE " . implode(' AND ', $conditions);
      $sql .= " ORDER BY d.date_don ASC, d.id_don ASC";

      $stmt = $this->pdo->prepare($sql);
      $stmt->execute($params);
      return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * Trouver l'ID de l'état "distribué/attribué"
   */
  public function findEtatDistribue(): ?int
  {
      $id = $this->pdo->query(
          "SELECT id FROM bngrc_etat_don WHERE nom LIKE '%distribu%' OR nom LIKE '%attribu%' LIMIT 1"
      )->fetchColumn();
      return $id !== false ? (int) $id : null;
  }

  /**
   * Marquer les dons entièrement distribués avec l'état donné
   */
  public function marquerDistribues(int $idEtat): void
  {
      $this->pdo->exec("
          UPDATE bngrc_don d
          SET id_etat = {$idEtat}
          WHERE (
              SELECT COALESCE(SUM(a.quantite_attribuee), 0)
              FROM bngrc_attribution_don a
              WHERE a.id_don = d.id_don
          ) >= d.quantite
      ");
  }
}
