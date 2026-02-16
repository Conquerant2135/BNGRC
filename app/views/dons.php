<?php
$pageTitle = 'Saisie des dons';
$breadcrumb = ['Saisie des dons' => null];
$baseUrl = BASE_URL;

$values = $values ?? [
  'id_don' => '',
  'donateur' => '',
  'date_don' => date('Y-m-d'),
  'id_cat' => '',
  'id_article' => '',
  'quantite' => '',
  'id_etat' => ''
];

$errors = $errors ?? [
  'donateur' => '',
  'date_don' => '',
  'id_cat' => '',
  'id_article' => '',
  'quantite' => '',
  'id_etat' => ''
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
  <h4 class="mb-0"><i class="bi bi-box-seam me-2 text-success"></i>Saisie des dons</h4>
</div>

<!-- ===== FORMULAIRE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0">
      <i class="bi bi-plus-circle me-2 text-success"></i>
      <?= $isEdit ? 'Modifier le don' : 'Enregistrer un nouveau don' ?>
    </h6>
  </div>
  <div class="card-body">
    <form id="formDon" method="post" action="<?= $isEdit ? $baseUrl . '/dons/' . $esc($values['id_don']) . '/update' : $baseUrl . '/dons' ?>" novalidate>
      <div class="row g-3">
        <div class="col-md-4">
          <label for="donateur" class="form-label fw-semibold">Donateur <span class="text-danger">*</span></label>
          <input type="text" class="form-control <?= $errors['donateur'] ? 'is-invalid' : '' ?>" id="donateur" name="donateur" value="<?= $esc($values['donateur']) ?>" placeholder="Nom du donateur ou organisme" required>
          <?php if ($errors['donateur']): ?><div class="invalid-feedback"><?= $esc($errors['donateur']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="categorie" class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_cat'] ? 'is-invalid' : '' ?>" id="categorie" name="id_cat" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($categories ?? []) as $cat): ?>
              <option value="<?= $esc($cat['id']) ?>" <?= ((string)$cat['id'] === (string)$values['id_cat']) ? 'selected' : '' ?>>
                <?= $esc($cat['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($errors['id_cat']): ?><div class="invalid-feedback"><?= $esc($errors['id_cat']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-4">
          <label for="dateDon" class="form-label fw-semibold">Date du don <span class="text-danger">*</span></label>
          <input type="date" class="form-control <?= $errors['date_don'] ? 'is-invalid' : '' ?>" id="dateDon" name="date_don" value="<?= $esc($values['date_don']) ?>" required>
          <?php if ($errors['date_don']): ?><div class="invalid-feedback"><?= $esc($errors['date_don']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="row g-3 mt-1">
        <div class="col-md-6">
          <label for="article" class="form-label fw-semibold">Article</label>
          <select class="form-select <?= $errors['id_article'] ? 'is-invalid' : '' ?>" id="article" name="id_article">
            <option value="">— Aucun (ex: don en argent) —</option>
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
          <input type="number" class="form-control <?= $errors['quantite'] ? 'is-invalid' : '' ?>" id="quantite" name="quantite" step="0.001" min="0" value="<?= $esc($values['quantite']) ?>" placeholder="0" required>
          <?php if ($errors['quantite']): ?><div class="invalid-feedback"><?= $esc($errors['quantite']) ?></div><?php endif; ?>
        </div>

        <div class="col-md-3">
          <label for="etat" class="form-label fw-semibold">État <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_etat'] ? 'is-invalid' : '' ?>" id="etat" name="id_etat" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($etats ?? []) as $etat): ?>
              <option value="<?= $esc($etat['id']) ?>" <?= ((string)$etat['id'] === (string)$values['id_etat']) ? 'selected' : '' ?>>
                <?= $esc($etat['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($errors['id_etat']): ?><div class="invalid-feedback"><?= $esc($errors['id_etat']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Mettre à jour' : 'Enregistrer le don' ?>
        </button>
        <?php if ($isEdit): ?>
          <a class="btn btn-outline-secondary" href="<?= $baseUrl ?>/dons">
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

<!-- ===== LISTE DES DONS ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2 text-success"></i>Liste des dons enregistrés</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Donateur</th>
            <th>Type</th>
            <th>Article</th>
            <th class="text-end">Quantité</th>
            <th class="text-center">État</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dons)): ?>
            <?php foreach ($dons as $row): ?>
              <?php
                $cat = (string)($row['categorie'] ?? '');
                $catLower = strtolower($cat);
                $catBadge = 'bg-secondary';
                if (strpos($catLower, 'nature') !== false) $catBadge = 'bg-info';
                elseif (strpos($catLower, 'mater') !== false) $catBadge = 'bg-secondary';
                elseif (strpos($catLower, 'argent') !== false) $catBadge = 'bg-primary';

                $etat = (string)($row['etat'] ?? '');
                $etatLower = strtolower($etat);
                $etatBadge = 'bg-secondary';
                if (strpos($etatLower, 'attente') !== false) $etatBadge = 'bg-warning text-dark';
                elseif (strpos($etatLower, 'distrib') !== false) $etatBadge = 'bg-success';
              ?>
              <tr>
                <td class="text-muted"><?= $esc($row['id_don']) ?></td>
                <td><?= $esc(date('d/m/Y', strtotime($row['date_don']))) ?></td>
                <td class="fw-semibold"><?= $esc($row['donateur']) ?></td>
                <td><span class="badge <?= $catBadge ?>"><?= $esc($cat !== '' ? $cat : '—') ?></span></td>
                <td><?= $esc($row['article'] ?? '—') ?></td>
                <td class="text-end"><?= $esc($fmtQty($row['quantite'])) ?></td>
                <td class="text-center"><span class="badge <?= $etatBadge ?>"><?= $esc($etat !== '' ? $etat : '—') ?></span></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline-warning" href="<?= $baseUrl ?>/dons?edit=<?= $esc($row['id_don']) ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                  <form method="post" action="<?= $baseUrl ?>/dons/<?= $esc($row['id_don']) ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer ce don ?');">
                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="8" class="text-center text-muted py-4">Aucun don enregistré.</td>
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
