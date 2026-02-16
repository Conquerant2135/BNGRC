<?php
$pageTitle = 'Tableau de bord';
$breadcrumb = ['Tableau de bord' => null];
$baseUrl = BASE_URL;

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
          <h3 class="mb-0 fw-bold">12</h3>
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
          <h3 class="mb-0 fw-bold">348</h3>
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
          <h3 class="mb-0 fw-bold">187</h3>
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
          <h6 class="text-muted mb-1 small text-uppercase">Dispatches effectués</h6>
          <h3 class="mb-0 fw-bold">64</h3>
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
      <button class="btn btn-sm btn-outline-primary" title="Exporter">
        <i class="bi bi-download"></i>
      </button>
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
            <th colspan="3" class="text-center bg-danger bg-opacity-75">Besoins</th>
            <th colspan="3" class="text-center bg-success bg-opacity-75">Dons attribués</th>
            <th class="text-center">Couverture</th>
          </tr>
          <tr class="table-secondary small">
            <th></th>
            <th></th>
            <th></th>
            <th class="text-center">Nature</th>
            <th class="text-center">Matériaux</th>
            <th class="text-center">Argent (Ar)</th>
            <th class="text-center">Nature</th>
            <th class="text-center">Matériaux</th>
            <th class="text-center">Argent (Ar)</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <!-- Ligne 1 -->
          <tr>
            <td class="fw-semibold">Antsirabe</td>
            <td>Vakinankaratra</td>
            <td class="text-center">1 250</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Riz: 500kg, Huile: 200L</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Tôle: 150, Clous: 50kg</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">2 500 000</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Riz: 300kg, Huile: 100L</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Tôle: 80, Clous: 30kg</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">1 200 000</span></td>
            <td class="text-center">
              <div class="progress" style="height: 20px; min-width: 80px;">
                <div class="progress-bar bg-warning" style="width: 52%;">52%</div>
              </div>
            </td>
          </tr>
          <!-- Ligne 2 -->
          <tr>
            <td class="fw-semibold">Mananjary</td>
            <td>Vatovavy</td>
            <td class="text-center">3 400</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Riz: 1200kg, Huile: 600L</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Tôle: 400, Clous: 120kg</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">8 000 000</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Riz: 1100kg, Huile: 550L</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Tôle: 380, Clous: 110kg</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">7 500 000</span></td>
            <td class="text-center">
              <div class="progress" style="height: 20px; min-width: 80px;">
                <div class="progress-bar bg-success" style="width: 91%;">91%</div>
              </div>
            </td>
          </tr>
          <!-- Ligne 3 -->
          <tr>
            <td class="fw-semibold">Toamasina</td>
            <td>Atsinanana</td>
            <td class="text-center">2 100</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Riz: 800kg, Huile: 350L</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Tôle: 250, Clous: 80kg</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">5 000 000</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Riz: 200kg, Huile: 50L</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Tôle: 40, Clous: 10kg</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">800 000</span></td>
            <td class="text-center">
              <div class="progress" style="height: 20px; min-width: 80px;">
                <div class="progress-bar bg-danger" style="width: 18%;">18%</div>
              </div>
            </td>
          </tr>
          <!-- Ligne 4 -->
          <tr>
            <td class="fw-semibold">Morondava</td>
            <td>Menabe</td>
            <td class="text-center">890</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Riz: 350kg, Huile: 150L</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Tôle: 100, Clous: 40kg</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">1 800 000</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Riz: 350kg, Huile: 150L</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Tôle: 100, Clous: 40kg</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">1 800 000</span></td>
            <td class="text-center">
              <div class="progress" style="height: 20px; min-width: 80px;">
                <div class="progress-bar bg-success" style="width: 100%;">100%</div>
              </div>
            </td>
          </tr>
          <!-- Ligne 5 -->
          <tr>
            <td class="fw-semibold">Farafangana</td>
            <td>Atsimo-Atsinanana</td>
            <td class="text-center">1 750</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Riz: 650kg, Huile: 280L</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">Tôle: 200, Clous: 60kg</span></td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">4 200 000</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Riz: 450kg, Huile: 200L</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">Tôle: 130, Clous: 40kg</span></td>
            <td class="text-center"><span class="badge bg-success-soft text-success">2 800 000</span></td>
            <td class="text-center">
              <div class="progress" style="height: 20px; min-width: 80px;">
                <div class="progress-bar bg-warning" style="width: 65%;">65%</div>
              </div>
            </td>
          </tr>
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
                <th>Type</th>
                <th>Détails</th>
                <th class="text-center">Statut</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-muted small">15/02/2026</td>
                <td>Croix-Rouge Madagascar</td>
                <td><span class="badge bg-info">Nature</span></td>
                <td>Riz: 500kg, Huile: 200L</td>
                <td class="text-center"><span class="badge bg-success">Distribué</span></td>
              </tr>
              <tr>
                <td class="text-muted small">14/02/2026</td>
                <td>Association Entraide</td>
                <td><span class="badge bg-secondary">Matériaux</span></td>
                <td>Tôle: 100 pièces, Clous: 30kg</td>
                <td class="text-center"><span class="badge bg-warning text-dark">En attente</span></td>
              </tr>
              <tr>
                <td class="text-muted small">13/02/2026</td>
                <td>Banque BOA</td>
                <td><span class="badge bg-primary">Argent</span></td>
                <td>5 000 000 Ar</td>
                <td class="text-center"><span class="badge bg-success">Distribué</span></td>
              </tr>
              <tr>
                <td class="text-muted small">12/02/2026</td>
                <td>ONG Care International</td>
                <td><span class="badge bg-info">Nature</span></td>
                <td>Riz: 1000kg, Eau: 500L</td>
                <td class="text-center"><span class="badge bg-warning text-dark">En attente</span></td>
              </tr>
              <tr>
                <td class="text-muted small">11/02/2026</td>
                <td>Particulier anonyme</td>
                <td><span class="badge bg-primary">Argent</span></td>
                <td>2 000 000 Ar</td>
                <td class="text-center"><span class="badge bg-success">Distribué</span></td>
              </tr>
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
