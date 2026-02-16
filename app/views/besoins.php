<?php
$pageTitle = 'Saisie des besoins';
$breadcrumb = ['Saisie des besoins' => null];
$baseUrl = BASE_URL;

$values = $values ?? [
  'id_besoin' => '',
  'id_ville' => '',
  'id_cat' => '',
  'id_article' => '',
  'quantite' => '',
  'date_demande' => date('Y-m-d')
];

$errors = $errors ?? [
  'id_ville' => '',
  'id_cat' => '',
  'id_article' => '',
  'quantite' => '',
  'date_demande' => ''
];

$isEdit = $isEdit ?? false;
$esc = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };
$fmtQty = function($v) {
  $n = number_format((float)$v, 3, '.', ' ');
  $n = rtrim(rtrim($n, '0'), '.');
  return $n === '' ? '0' : $n;
};

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-clipboard-pulse me-2 text-danger"></i>Saisie des besoins par ville</h4>
</div>

<!-- ===== FORMULAIRE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0">
      <i class="bi bi-plus-circle me-2 text-primary"></i>
      <?= $isEdit ? 'Modifier le besoin' : 'Enregistrer un nouveau besoin' ?>
    </h6>
  </div>
  <div class="card-body">
    <form id="formBesoin" method="post" action="<?= $isEdit ? $baseUrl . '/besoins/' . $esc($values['id_besoin']) . '/update' : $baseUrl . '/besoins' ?>" novalidate>
      <div class="row g-3">
        <div class="col-md-4">
          <label for="ville" class="form-label fw-semibold">Ville <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_ville'] ? 'is-invalid' : '' ?>" id="ville" name="id_ville" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($ville ?? []) as $v) { ?>
              <option value="<?= $esc($v['id_ville']) ?>" <?= ((string)$v['id_ville'] === (string)$values['id_ville']) ? 'selected' : '' ?>>
                <?= $esc($v['nom_ville']) ?>
              </option>
            <?php } ?>
          </select>
          <?php if ($errors['id_ville']): ?><div class="invalid-feedback"><?= $esc($errors['id_ville']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="categorie" class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_cat'] ? 'is-invalid' : '' ?>" id="categorie" name="id_cat" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($cat ?? []) as $c) { ?>
              <option value="<?= $esc($c['id']) ?>" <?= ((string)$c['id'] === (string)$values['id_cat']) ? 'selected' : '' ?>>
                <?= $esc($c['nom']) ?>
              </option>
            <?php } ?>
          </select>
          <?php if ($errors['id_cat']): ?><div class="invalid-feedback"><?= $esc($errors['id_cat']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="article" class="form-label fw-semibold">Article <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_article'] ? 'is-invalid' : '' ?>" id="article" name="id_article" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($article ?? []) as $a) { ?>
              <option value="<?= $esc($a['id']) ?>" <?= ((string)$a['id'] === (string)$values['id_article']) ? 'selected' : '' ?>>
                <?= $esc($a['nom']) ?>
              </option>
            <?php } ?>
          </select>
          <?php if ($errors['id_article']): ?><div class="invalid-feedback"><?= $esc($errors['id_article']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="quantite" class="form-label fw-semibold">Quantité <span class="text-danger">*</span></label>
          <input type="number" step="0.001" min="0.001" class="form-control <?= $errors['quantite'] ? 'is-invalid' : '' ?>" id="quantite" name="quantite" value="<?= $esc($values['quantite']) ?>" required>
          <?php if ($errors['quantite']): ?><div class="invalid-feedback"><?= $esc($errors['quantite']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="dateDemande" class="form-label fw-semibold">Date <span class="text-danger">*</span></label>
          <input type="date" class="form-control <?= $errors['date_demande'] ? 'is-invalid' : '' ?>" id="dateDemande" name="date_demande" value="<?= $esc($values['date_demande']) ?>" required>
          <?php if ($errors['date_demande']): ?><div class="invalid-feedback"><?= $esc($errors['date_demande']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Mettre à jour' : 'Enregistrer le besoin' ?>
        </button>
        <?php if ($isEdit): ?>
          <a class="btn btn-outline-secondary" href="<?= $baseUrl ?>/besoins">
            <i class="bi bi-x-circle"></i> Annuler
          </a>
        <?php else: ?>
          <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
          </button>
        <?php endif; ?>
      </div>
    </form>
  </div>
</div>

<!-- ===== LISTE DES BESOINS ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Liste des besoins enregistrés</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Ville</th>
            <th>Catégorie</th>
            <th>Article</th>
            <th class="text-end">Quantité</th>
            <th>Unité</th>
            <th class="text-end">Montant</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($besoins)): ?>
            <?php foreach ($besoins as $row): ?>
              <?php
                $cat = (string)($row['categorie'] ?? '');
                $catLower = strtolower($cat);
                $catBadge = 'bg-secondary';
                if (strpos($catLower, 'nour') !== false) $catBadge = 'bg-info';
                elseif (strpos($catLower, 'mater') !== false) $catBadge = 'bg-secondary';
                elseif (strpos($catLower, 'argent') !== false) $catBadge = 'bg-primary';
              ?>
              <tr>
                <td class="text-muted"><?= $esc($row['id_besoin']) ?></td>
                <td><?= $esc(date('d/m/Y', strtotime($row['date_demande']))) ?></td>
                <td class="fw-semibold"><?= $esc($row['ville']) ?></td>
                <td><span class="badge <?= $catBadge ?>"><?= $esc($cat !== '' ? $cat : '—') ?></span></td>
                <td><?= $esc($row['article']) ?></td>
                <td class="text-end"><?= $esc($fmtQty($row['quantite'])) ?></td>
                <td><?= $esc($row['unite'] ?? '—') ?></td>
                <td class="text-end"><?= $esc($fmtQty($row['montant_totale'])) ?></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline-warning" href="<?= $baseUrl ?>/besoins?edit=<?= $esc($row['id_besoin']) ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                  <form method="post" action="<?= $baseUrl ?>/besoins/<?= $esc($row['id_besoin']) ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer ce besoin ?');">
                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9" class="text-center text-muted py-4">Aucun besoin enregistré.</td>
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
