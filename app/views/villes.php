<?php
$pageTitle = 'Gestion des villes';
$breadcrumb = ['Gestion des villes' => null];
$baseUrl = BASE_URL;

$values = $values ?? [
  'id_ville' => '',
  'nom_ville' => '',
  'id_region' => '',
  'nb_sinistres' => '0'
];

$errors = $errors ?? [
  'nom_ville' => '',
  'id_region' => '',
  'nb_sinistres' => ''
];

$isEdit = $isEdit ?? false;
$esc = function($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); };

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Gestion des villes sinistrées</h4>
</div>

<!-- ===== FORMULAIRE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0">
      <i class="bi bi-plus-circle me-2 text-primary"></i>
      <?= $isEdit ? 'Modifier la ville' : 'Ajouter une ville' ?>
    </h6>
  </div>
  <div class="card-body">
    <form id="formVille" method="post" action="<?= $isEdit ? $baseUrl . '/villes/' . $esc($values['id_ville']) . '/update' : $baseUrl . '/villes' ?>" novalidate>
      <div class="row g-3">
        <div class="col-md-5">
          <label class="form-label fw-semibold">Nom de la ville <span class="text-danger">*</span></label>
          <input type="text" class="form-control <?= $errors['nom_ville'] ? 'is-invalid' : '' ?>" name="nom_ville" value="<?= $esc($values['nom_ville']) ?>" placeholder="Ex: Antsirabe" required>
          <?php if ($errors['nom_ville']): ?><div class="invalid-feedback"><?= $esc($errors['nom_ville']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-4">
          <label class="form-label fw-semibold">Région <span class="text-danger">*</span></label>
          <select class="form-select <?= $errors['id_region'] ? 'is-invalid' : '' ?>" name="id_region" required>
            <option value="">— Sélectionner —</option>
            <?php foreach (($regions ?? []) as $region): ?>
              <option value="<?= $esc($region['id']) ?>" <?= ((string)$region['id'] === (string)$values['id_region']) ? 'selected' : '' ?>>
                <?= $esc($region['nom']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($errors['id_region']): ?><div class="invalid-feedback"><?= $esc($errors['id_region']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold">Nb sinistrés estimé</label>
          <input type="number" class="form-control <?= $errors['nb_sinistres'] ? 'is-invalid' : '' ?>" name="nb_sinistres" min="0" step="1" value="<?= $esc($values['nb_sinistres']) ?>" placeholder="0">
          <?php if ($errors['nb_sinistres']): ?><div class="invalid-feedback"><?= $esc($errors['nb_sinistres']) ?></div><?php endif; ?>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Mettre à jour' : 'Enregistrer' ?>
        </button>
        <?php if ($isEdit): ?>
          <a class="btn btn-outline-secondary" href="<?= $baseUrl ?>/villes">
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

<!-- ===== LISTE DES VILLES ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Villes enregistrées</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nom de la ville</th>
            <th>Région</th>
            <th class="text-center">Nb Sinistrés</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($villes)): ?>
            <?php foreach ($villes as $row): ?>
              <tr>
                <td class="text-muted"><?= $esc($row['id_ville']) ?></td>
                <td class="fw-semibold"><?= $esc($row['nom_ville']) ?></td>
                <td><?= $esc($row['region'] ?? '—') ?></td>
                <td class="text-center"><span class="badge bg-danger-soft text-danger"><?= $esc($row['nb_sinistres']) ?></span></td>
                <td class="text-center">
                  <a class="btn btn-sm btn-outline-warning" href="<?= $baseUrl ?>/villes?edit=<?= $esc($row['id_ville']) ?>" title="Modifier"><i class="bi bi-pencil"></i></a>
                  <form method="post" action="<?= $baseUrl ?>/villes/<?= $esc($row['id_ville']) ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer cette ville ?');">
                    <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" class="text-center text-muted py-4">Aucune ville enregistrée.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>



<!-- ===== LISTE DES REGIONS ===== -->
<div class="card border-0 shadow-sm mt-4">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-map me-2"></i>Régions enregistrées</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nom de la région</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($regions)): ?>
            <?php foreach ($regions as $region): ?>
              <tr>
                <td class="text-muted"><?= $esc($region['id']) ?></td>
                <td class="fw-semibold"><?= $esc($region['nom']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="2" class="text-center text-muted py-4">Aucune région enregistrée.</td>
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
