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

  public function getTaxeValeur() {
    $stmt = $this->pdo->query("SELECT valeur FROM bngrc_montant_taxe ORDER BY id DESC LIMIT 1");
    $val = $stmt->fetchColumn();
    return $val !== false ? (float)$val : 0.0;
  }

  public function getArticleById($id) {
    $st = $this->pdo->prepare("SELECT id, nom, prix_unitaire, id_cat FROM bngrc_article WHERE id = ? LIMIT 1");
    $st->execute([(int)$id]);
    return $st->fetch();
  }

  public function getCategorieArgentId() {
    $st = $this->pdo->query("SELECT id FROM bngrc_categorie WHERE LOWER(nom) LIKE '%argent%' LIMIT 1");
    $id = $st->fetchColumn();
    return $id !== false ? (int)$id : null;
  }

  public function getTotalArgent() {
    $idCat = $this->getCategorieArgentId();
    if (!$idCat) return 0.0;
    $st = $this->pdo->prepare("SELECT COALESCE(SUM(quantite), 0) FROM bngrc_don WHERE id_cat = ?");
    $st->execute([$idCat]);
    return (float)$st->fetchColumn();
  }

  public function getTotalAchats() {
    $st = $this->pdo->query("SELECT COALESCE(SUM(montant_total), 0) FROM bngrc_achat_produit");
    return (float)$st->fetchColumn();
  }

  public function getArgentDisponible() {
    return $this->getTotalArgent() - $this->getTotalAchats();
  }

  public function listAchats() {
    $sql = "
      SELECT ap.id, ap.id_article, ap.quantite, ap.valeur_taux, ap.montant_total, ap.date_achat,
             a.nom AS article, a.prix_unitaire,
             u.libelle AS unite
      FROM bngrc_achat_produit ap
      JOIN bngrc_article a ON a.id = ap.id_article
      LEFT JOIN bngrc_unite u ON u.id = a.id_unite
      ORDER BY ap.date_achat DESC, ap.id DESC
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function listBesoinsRestants() {
    $sql = "
      SELECT b.id_besoin, b.id_article, b.id_ville, b.quantite, b.date_demande,
             v.nom_ville, a.nom AS article_nom,
             COALESCE(att_sum.total_attribue, 0) AS deja_attribue,
             (b.quantite - COALESCE(att_sum.total_attribue, 0)) AS restant
      FROM bngrc_besoin b
      JOIN bngrc_ville v ON v.id_ville = b.id_ville
      JOIN bngrc_article a ON a.id = b.id_article
      LEFT JOIN (
          SELECT id_besoin, SUM(quantite_attribuee) AS total_attribue
          FROM bngrc_attribution_don
          GROUP BY id_besoin
      ) att_sum ON att_sum.id_besoin = b.id_besoin
      WHERE b.est_satisfait = 0
        AND (b.quantite - COALESCE(att_sum.total_attribue, 0)) > 0
      ORDER BY b.date_demande ASC, b.id_besoin ASC
    ";
    return $this->pdo->query($sql)->fetchAll();
  }

  public function getDefaultEtatId() {
    $st = $this->pdo->query("SELECT id FROM bngrc_etat_don ORDER BY id ASC LIMIT 1");
    $id = $st->fetchColumn();
    return $id !== false ? (int)$id : null;
  }

  public function achatProduit(array $data) {
    $this->pdo->beginTransaction();
    try {
      $stmtAchat = $this->pdo->prepare("
        INSERT INTO bngrc_achat_produit (id_article, quantite, valeur_taux, montant_total, date_achat)
        VALUES (?, ?, ?, ?, ?)
      ");
      $stmtAchat->execute([
        (int)$data['id_article'],
        (float)$data['quantite'],
        (float)$data['valeur_taux'],
        (float)$data['montant_total'],
        (string)$data['date_achat']
      ]);
      $achatId = $this->pdo->lastInsertId();

      $stmtDon = $this->pdo->prepare("
        INSERT INTO bngrc_don (donateur, date_don, id_cat, id_article, quantite, id_etat)
        VALUES (?, ?, ?, ?, ?, ?)
      ");
      $stmtDon->execute([
        (string)$data['donateur'],
        (string)$data['date_achat'],
        (int)$data['id_cat'],
        (int)$data['id_article'],
        (float)$data['quantite'],
        $data['id_etat'] !== null ? (int)$data['id_etat'] : null
      ]);

      $this->pdo->commit();
      return $achatId;
    } catch (Exception $e) {
      $this->pdo->rollBack();
      throw $e;
    }
  }
}
