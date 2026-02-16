<?php
$pageTitle = "Achat de produits";
$breadcrumb = ["Achat de produits" => null];
$baseUrl = BASE_URL;

$values = $values ?? [
  'id_article' => '',
  'quantite' => '',
  'date_achat' => date('Y-m-d')
];

$errors = $errors ?? [
  'id_article' => '',
  'quantite' => '',
  'date_achat' => '',
  'montant' => ''
];

$taxe = $taxe ?? 0;
$argentDisponible = $argentDisponible ?? 0;
$esc = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$fmtMoney = function($v) {
  return number_format((float)$v, 2, '.', ' ');
};
$fmtQty = function($v) {
  $n = number_format((float)$v, 3, '.', ' ');
  return rtrim(rtrim($n, '0'), '.');
};

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-cart-check me-2 text-primary"></i>Achat de produits</h4>
</div>

<div class="row g-3 mb-4">
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="text-muted mb-1">Frais d'achat</h6>
        <div class="fs-4 fw-semibold"><?= $fmtMoney($taxe) ?> %</div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card border-0 shadow-sm">
      <div class="card-body">
        <h6 class="text-muted mb-1">Dons en argent disponibles</h6>
        <div class="fs-4 fw-semibold"><?= $fmtMoney($argentDisponible) ?> Ar</div>
      </div>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Enregistrer un achat</h6>
  </div>
  <div class="card-body">
    <form method="post" action="<?= $baseUrl ?>/achats/valider" novalidate>
      <div class="row g-3">
        <div class="col-md-6">
          <label for="article" class="form-label fw-semibold">Article <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_article'] ? 'is-invalid' : '' ?>" id="article" name="id_article" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($articles ?? []) as $art): ?>
              <option value="<?= $esc($art['id']) ?>" <?= ((string)$art['id'] === (string)$values['id_article']) ? 'selected' : '' ?>>
                <?= $esc($art['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($errors['id_article']): ?><div class="invalid-feedback"><?= $esc($errors['id_article']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-3">
          <label for="quantite" class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
          <input type="number" step="0.001" min="0.001" class="form-control <?= $errors['quantite'] ? 'is-invalid' : '' ?>" id="quantite" name="quantite" value="<?= $esc($values['quantite']) ?>" required>
          <?php if ($errors['quantite']): ?><div class="invalid-feedback"><?= $esc($errors['quantite']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-3">
          <label for="dateAchat" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
          <input type="date" class="form-control <?= $errors['date_achat'] ? 'is-invalid' : '' ?>" id="dateAchat" name="date_achat" value="<?= $esc($values['date_achat']) ?>" required>
          <?php if ($errors['date_achat']): ?><div class="invalid-feedback"><?= $esc($errors['date_achat']) ?></div><?php endif; ?>
        </div>
      </div>

      <?php if (!empty($errors['montant'])): ?>
        <div class="alert alert-danger mt-3 mb-0"><?= $esc($errors['montant']) ?></div>
      <?php endif; ?>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg"></i> Valider l'achat
        </button>
        <button type="reset" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
        </button>
      </div>
    </form>
  </div>
</div>

<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Liste des achats</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Article</th>
            <th class="text-end">Quantité</th>
            <th class="text-end">Taux</th>
            <th class="text-end">Montant TTC</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($achats)): ?>
            <?php foreach ($achats as $row): ?>
              <tr>
                <td class="text-muted"><?= $esc($row['id']) ?></td>
                <td><?= $esc(date('d/m/Y', strtotime($row['date_achat']))) ?></td>
                <td><?= $esc($row['article']) ?></td>
                <td class="text-end"><?= $esc($fmtQty($row['quantite'])) ?> <?= $esc($row['unite'] ?? '') ?></td>
                <td class="text-end"><?= $esc($fmtMoney($row['valeur_taux'])) ?> %</td>
                <td class="text-end"><?= $esc($fmtMoney($row['montant_total'])) ?> Ar</td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="6" class="text-center text-muted py-4">Aucun achat enregistré.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="card border-0 shadow-sm">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-clipboard-pulse me-2 text-danger"></i>Besoins restants</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Ville</th>
            <th>Article</th>
            <th class="text-end">Restant</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($besoinsRestants)): ?>
            <?php foreach ($besoinsRestants as $row): ?>
              <tr>
                <td class="text-muted"><?= $esc($row['id_besoin']) ?></td>
                <td><?= $esc(date('d/m/Y', strtotime($row['date_demande']))) ?></td>
                <td><?= $esc($row['nom_ville']) ?></td>
                <td><?= $esc($row['article_nom']) ?></td>
                <td class="text-end"><?= $esc($fmtQty($row['restant'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Aucun besoin restant.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
