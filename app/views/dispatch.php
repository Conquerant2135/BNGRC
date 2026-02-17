<?php
// Helper d'échappement (défini ici car utilisé avant l'inclusion du layout)
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
}

$pageTitle = 'Simulation du dispatch';
$breadcrumb = ['Simulation dispatch' => null];
$baseUrl = BASE_URL;

// Variables passées par le contrôleur
$resultats = $resultats ?? [];
$resume    = $resume    ?? [];
$dateDebut = $dateDebut ?? '';
$dateFin   = $dateFin   ?? date('Y-m-d');
$mode      = $mode      ?? 'fifo';
$lancer    = $lancer    ?? false;
$nbOps     = $nbOps     ?? 0;

// Messages flash
$success = $_GET['success'] ?? null;
$error   = $_GET['error']   ?? null;

ob_start();
?>

<?php if ($success): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><strong><?= $success === '1' ? 'Dispatch validé avec succès ! Les attributions ont été enregistrées en base de données.' : e($success) ?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<?php if ($error): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><strong>Erreur :</strong> <?= e($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-truck me-2 text-warning"></i>Simulation du dispatch des dons</h4>
  <form method="post" action="<?= $baseUrl ?>/dispatch/reset" onsubmit="return confirm('Réinitialiser toutes les attributions, stocks et achats ? Les besoins et dons seront remis à leur état initial.');">
    <button type="submit" class="btn btn-outline-danger">
      <i class="bi bi-arrow-counterclockwise me-1"></i>Réinitialiser
    </button>
  </form>
</div>

<!-- ===== INFO ===== -->
<div class="alert alert-info border-0 shadow-sm">
  <div class="d-flex align-items-center">
    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
    <div>
      <strong>Comment fonctionne le dispatch ?</strong><br>
      Choisissez un mode de repartition : <strong>FIFO</strong> (besoins les plus anciens),
      <strong>Stock</strong> (petites quantites d'abord) ou <strong>Proportionnel</strong> (au prorata des besoins).
      Les dons sont toujours traites par ordre de date de saisie.
    </div>
  </div>
</div>

<!-- ===== PARAMÈTRES ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-sliders me-2 text-primary"></i>Paramètres de simulation</h6>
  </div>
  <div class="card-body">
    <form class="row g-3 align-items-end" method="get" action="<?= $baseUrl ?>/dispatch">
      <div class="col-md-3">
        <label class="form-label fw-semibold">Date début</label>
        <input type="date" class="form-control" name="date_debut" value="<?= e($dateDebut) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Date fin</label>
        <input type="date" class="form-control" name="date_fin" value="<?= e($dateFin) ?>">
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Mode de répartition</label>
        <select class="form-select" name="mode">
          <option value="fifo" <?= $mode === 'fifo' ? 'selected' : '' ?>>FIFO — Premier arrive, premier servi</option>
          <option value="stock" <?= $mode === 'stock' ? 'selected' : '' ?>>Stock — Petites quantites d'abord</option>
          <option value="proportionnel" <?= $mode === 'proportionnel' ? 'selected' : '' ?>>Proportionnel aux besoins</option>
        </select>
      </div>
      <div class="col-md-3">
        <input type="hidden" name="lancer" value="1">
        <button type="submit" class="btn btn-warning w-100">
          <i class="bi bi-play-fill"></i> Lancer la simulation
        </button>
      </div>
    </form>
  </div>
</div>

<?php if ($lancer): ?>

  <!-- ===== RÉSULTAT : Timeline ===== -->
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
      <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Résultat du dispatch (ordre chronologique)</h6>
      <span class="badge bg-warning text-dark"><?= $nbOps ?> attribution<?= $nbOps > 1 ? 's' : '' ?> simulée<?= $nbOps > 1 ? 's' : '' ?></span>
    </div>
    <div class="card-body p-0">
      <?php if (empty($resultats)): ?>
        <div class="text-center py-5 text-muted">
          <i class="bi bi-inbox fs-1 d-block mb-2"></i>
          <p class="mb-0">Aucune attribution possible.<br>
          <small>Vérifiez qu'il existe des dons et des besoins non satisfaits pour les mêmes articles dans la période sélectionnée.</small></p>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>Ordre</th>
                <th>Date du don</th>
                <th>Donateur</th>
                <th>Article</th>
                <th class="text-end">Qté disponible</th>
                <th><i class="bi bi-arrow-right"></i> Ville destinataire</th>
                <th class="text-end">Qté attribuée</th>
                <th class="text-center">Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $ordre       = 0;
                $currentDon  = null;
                $resteDon    = 0;
              ?>
              <?php foreach ($resultats as $r): ?>
                <?php
                  $isNewDon = ($r['id_don'] !== $currentDon);
                  if ($isNewDon) {
                      $ordre++;
                      $currentDon = $r['id_don'];
                      $resteDon   = $r['reste_don_avant'];
                  }
                  $qteAff   = number_format($r['quantite_attribuee'], ($r['quantite_attribuee'] == intval($r['quantite_attribuee'])) ? 0 : 2, ',', ' ');
                  $unite     = e($r['unite']);
                  $resteDon -= $r['quantite_attribuee'];
                ?>
                <?php if ($isNewDon): ?>
                  <tr>
                    <td><span class="badge bg-secondary rounded-pill"><?= $ordre ?></span></td>
                    <td><?= date('d/m/Y', strtotime($r['date_don'])) ?></td>
                    <td><?= e($r['donateur']) ?></td>
                    <td><?= e($r['article_nom']) ?></td>
                    <td class="text-end"><?= number_format($r['reste_don_avant'], ($r['reste_don_avant'] == intval($r['reste_don_avant'])) ? 0 : 2, ',', ' ') ?> <?= $unite ?></td>
                    <td class="fw-semibold text-primary">
                      <i class="bi bi-arrow-right-circle text-warning"></i> <?= e($r['nom_ville']) ?>
                    </td>
                    <td class="text-end fw-semibold text-success"><?= $qteAff ?> <?= $unite ?></td>
                    <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
                  </tr>
                <?php else: ?>
                  <tr class="table-light">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-end text-muted">Reste: <?= number_format($resteDon + $r['quantite_attribuee'], ($resteDon + $r['quantite_attribuee'] == intval($resteDon + $r['quantite_attribuee'])) ? 0 : 2, ',', ' ') ?> <?= $unite ?></td>
                    <td class="fw-semibold text-primary">
                      <i class="bi bi-arrow-right-circle text-warning"></i> <?= e($r['nom_ville']) ?>
                    </td>
                    <td class="text-end fw-semibold text-success"><?= $qteAff ?> <?= $unite ?></td>
                    <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
                  </tr>
                <?php endif; ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- ===== RÉSUMÉ PAR VILLE après dispatch ===== -->
  <?php if (!empty($resume)): ?>
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
      <h6 class="mb-0"><i class="bi bi-bar-chart me-2 text-primary"></i>Résumé après dispatch</h6>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Ville</th>
              <th class="text-center">Total besoins</th>
              <th class="text-center">Total reçu</th>
              <th class="text-center">Reste à couvrir</th>
              <th>Taux couverture</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($resume as $r): ?>
              <?php
                $taux     = $r['taux'];
                $bgClass  = $taux >= 90 ? 'bg-success' : ($taux >= 50 ? 'bg-warning' : 'bg-danger');
                $txtClass = $r['reste'] > 0 ? 'text-danger' : 'text-success';
              ?>
              <tr>
                <td class="fw-semibold"><?= e($r['nom_ville']) ?></td>
                <td class="text-center"><?= number_format($r['total_besoin'], ($r['total_besoin'] == intval($r['total_besoin'])) ? 0 : 2, ',', ' ') ?></td>
                <td class="text-center text-success fw-semibold"><?= number_format($r['total_recu'], ($r['total_recu'] == intval($r['total_recu'])) ? 0 : 2, ',', ' ') ?></td>
                <td class="text-center <?= $txtClass ?>"><?= number_format($r['reste'], ($r['reste'] == intval($r['reste'])) ? 0 : 2, ',', ' ') ?></td>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div class="progress flex-grow-1" style="height: 20px;">
                      <div class="progress-bar <?= $bgClass ?>" style="width: <?= min($taux, 100) ?>%;"><?= $taux ?>%</div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- ===== BOUTON VALIDER ===== -->
  <?php if (!empty($resultats)): ?>
  <div class="card border-0 shadow-sm mb-4">
    <div class="card-body text-center py-4">
      <p class="text-muted mb-3">
        <i class="bi bi-exclamation-circle me-1"></i>
        Cette simulation n'a pas encore été enregistrée. Cliquez ci-dessous pour valider et sauvegarder les attributions en base de données.
      </p>
      <form method="post" action="<?= $baseUrl ?>/dispatch/valider" onsubmit="return confirm('Confirmer la validation du dispatch ? Cette action enregistrera toutes les attributions en base de données.');">
        <input type="hidden" name="date_debut" value="<?= e($dateDebut) ?>">
        <input type="hidden" name="date_fin" value="<?= e($dateFin) ?>">
        <input type="hidden" name="mode" value="<?= e($mode) ?>">
        <button type="submit" class="btn btn-success btn-lg">
          <i class="bi bi-check2-all me-2"></i>Valider et enregistrer le dispatch
        </button>
      </form>
    </div>
  </div>
  <?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
