<?php
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
}

$pageTitle = 'Tableau de bord';
$breadcrumb = ['Tableau de bord' => null];
$baseUrl = BASE_URL;

// Variables passées par le contrôleur
$nbVilles     = $nbVilles     ?? 0;
$nbBesoins    = $nbBesoins    ?? 0;
$nbDons       = $nbDons       ?? 0;
$nbDispatch   = $nbDispatch   ?? 0;
$villes       = $villes       ?? [];
$derniersDons = $derniersDons ?? [];
$categories   = $categories   ?? [];

ob_start();
?>

<!-- ===== STATS CARDS ===== -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card card-stat border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-primary-soft text-primary me-3">
          <i class="bi bi-building"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1 small text-uppercase">Villes sinistrées</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($nbVilles, 0, ',', ' ') ?></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-stat border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-danger-soft text-danger me-3">
          <i class="bi bi-clipboard-pulse"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1 small text-uppercase">Besoins enregistrés</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($nbBesoins, 0, ',', ' ') ?></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-stat border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-success-soft text-success me-3">
          <i class="bi bi-box-seam"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1 small text-uppercase">Dons reçus</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($nbDons, 0, ',', ' ') ?></h3>
        </div>
      </div>
    </div>
  </div>
  <div class="col-xl-3 col-md-6">
    <div class="card card-stat border-0 shadow-sm h-100">
      <div class="card-body d-flex align-items-center">
        <div class="stat-icon bg-warning-soft text-warning me-3">
          <i class="bi bi-truck"></i>
        </div>
        <div>
          <h6 class="text-muted mb-1 small text-uppercase">Attributions effectuées</h6>
          <h3 class="mb-0 fw-bold"><?= number_format($nbDispatch, 0, ',', ' ') ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== MAIN TABLE: Besoins par ville ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h5 class="mb-0"><i class="bi bi-table me-2 text-primary"></i>Besoins par ville</h5>
    <div class="d-flex gap-2">
      <input type="text" class="form-control form-control-sm" placeholder="Rechercher une ville..." style="width: 220px;" id="searchVille">
    </div>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-dark">
          <tr>
            <th>Ville</th>
            <th>Région</th>
            <th class="text-center">Nb Sinistrés</th>
            <?php foreach ($categories as $cat): ?>
              <th class="text-center bg-danger bg-opacity-75">Besoin <?= e($cat['nom']) ?></th>
              <th class="text-center bg-success bg-opacity-75">Reçu <?= e($cat['nom']) ?></th>
            <?php endforeach; ?>
            <th class="text-center">Couverture</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($villes)): ?>
            <tr>
              <td colspan="<?= 3 + count($categories) * 2 + 1 ?>" class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucune donnée disponible
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($villes as $v): ?>
              <tr>
                <td class="fw-semibold"><?= e($v['nom_ville']) ?></td>
                <td><?= e($v['region']) ?></td>
                <td class="text-center"><?= number_format($v['nb_sinistres'], 0, ',', ' ') ?></td>
                <?php foreach ($categories as $cat): ?>
                  <?php
                    $catNom = $cat['nom'];
                    $besoin = $v['categories'][$catNom]['besoin'] ?? 0;
                    $recu   = $v['categories'][$catNom]['recu'] ?? 0;
                    $fmtB   = number_format($besoin, ($besoin == intval($besoin)) ? 0 : 2, ',', ' ');
                    $fmtR   = number_format($recu, ($recu == intval($recu)) ? 0 : 2, ',', ' ');
                  ?>
                  <td class="text-center">
                    <?php if ($besoin > 0): ?>
                      <span class="badge bg-danger-soft text-danger"><?= $fmtB ?></span>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="text-center">
                    <?php if ($recu > 0): ?>
                      <span class="badge bg-success-soft text-success"><?= $fmtR ?></span>
                    <?php else: ?>
                      <span class="text-muted">—</span>
                    <?php endif; ?>
                  </td>
                <?php endforeach; ?>
                <td class="text-center">
                  <?php
                    $taux    = $v['taux'];
                    $bgClass = $taux >= 90 ? 'bg-success' : ($taux >= 50 ? 'bg-warning' : 'bg-danger');
                  ?>
                  <div class="progress" style="height: 20px; min-width: 80px;">
                    <div class="progress-bar <?= $bgClass ?>" style="width: <?= min($taux, 100) ?>%;"><?= $taux ?>%</div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ===== SECONDARY ROW: Derniers dons + Images ===== -->
<div class="row g-3">
  <!-- Derniers dons -->
  <div class="col-lg-7">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-clock-history me-2 text-success"></i>Derniers dons enregistrés</h5>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
              <tr>
                <th>Date</th>
                <th>Donateur</th>
                <th>Catégorie</th>
                <th>Détails</th>
                <th class="text-center">Statut</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($derniersDons)): ?>
                <tr>
                  <td colspan="5" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun don enregistré
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($derniersDons as $don): ?>
                  <?php
                    $catNom = $don['categorie'] ?? 'Autre';
                    $catBadge = 'bg-info';
                    if (stripos($catNom, 'Matéri') !== false) $catBadge = 'bg-secondary';
                    elseif (stripos($catNom, 'Argent') !== false) $catBadge = 'bg-primary';

                    $etat = $don['etat'] ?? 'En attente';
                    $etatBadge = 'bg-warning text-dark';
                    if (stripos($etat, 'Distribu') !== false) $etatBadge = 'bg-success';
                    elseif (stripos($etat, 'Partiel') !== false) $etatBadge = 'bg-info';

                    $qte = number_format($don['quantite'], ($don['quantite'] == intval($don['quantite'])) ? 0 : 2, ',', ' ');
                  ?>
                  <tr>
                    <td class="text-muted small"><?= date('d/m/Y', strtotime($don['date_don'])) ?></td>
                    <td><?= e($don['donateur']) ?></td>
                    <td><span class="badge <?= $catBadge ?>"><?= e($catNom) ?></span></td>
                    <td><?= e($don['article_nom']) ?>: <?= $qte ?> <?= e($don['unite'] ?? '') ?></td>
                    <td class="text-center"><span class="badge <?= $etatBadge ?>"><?= e($etat) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Images / Situation -->
  <div class="col-lg-5">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-header bg-white py-3">
        <h5 class="mb-0"><i class="bi bi-image me-2 text-danger"></i>Situation sur le terrain</h5>
      </div>
      <div class="card-body">
        <div id="carouselSituation" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner rounded">
            <div class="carousel-item active">
              <img src="<?= $baseUrl ?>/assets/images/dégat.jpg" class="d-block w-100" alt="Dégât" style="height:260px; object-fit:cover;">
              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-2 py-1">
                <p class="mb-0 small">Dégâts causés par le cyclone</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="<?= $baseUrl ?>/assets/images/dégat2.jpg" class="d-block w-100" alt="Dégât 2" style="height:260px; object-fit:cover;">
              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-2 py-1">
                <p class="mb-0 small">Zones inondées</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="<?= $baseUrl ?>/assets/images/aide1.jpg" class="d-block w-100" alt="Aide" style="height:260px; object-fit:cover;">
              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-2 py-1">
                <p class="mb-0 small">Distribution d'aide</p>
              </div>
            </div>
            <div class="carousel-item">
              <img src="<?= $baseUrl ?>/assets/images/camion.jpg" class="d-block w-100" alt="Camion" style="height:260px; object-fit:cover;">
              <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded px-2 py-1">
                <p class="mb-0 small">Acheminement des dons</p>
              </div>
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselSituation" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselSituation" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
        <div class="mt-3 text-muted small text-center">
          <i class="bi bi-info-circle"></i> Photos de la situation actuelle dans les zones sinistrées
        </div>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
