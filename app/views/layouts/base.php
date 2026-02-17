<?php
if (!function_exists('e')) {
    function e($v){ return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
}
$baseUrl = BASE_URL;
$currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
function isActive($path) {
    global $currentUri, $baseUrl;
    $full = $baseUrl . $path;
    return ($currentUri === $full || $currentUri === $full . '/') ? 'active' : '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle ?? 'BNGRC') ?> — Suivi des Dons</title>
  <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/images/bngrc.png">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/style.css">
</head>
<body>

  <!-- ========== TOP NAVBAR ========== -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-bngrc sticky-top shadow-sm">
    <div class="container-fluid px-4">
      <!-- Logos -->
      <a class="navbar-brand d-flex align-items-center gap-3" href="<?= $baseUrl ?>/">
        <img src="<?= $baseUrl ?>/assets/images/republique.png" alt="République" height="48">
        <img src="<?= $baseUrl ?>/assets/images/bngrc.png" alt="BNGRC" height="48">
        <img src="<?= $baseUrl ?>/assets/images/logo-MI-2024-TRANSPARENT-NOIR.png" alt="Ministère Intérieur" height="48" class="logo-mi">
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        <div class="ms-auto d-flex align-items-center">
          <span class="navbar-text text-white-50 me-3 d-none d-lg-inline">
            <i class="bi bi-geo-alt-fill"></i> Suivi des collectes et distributions
          </span>
          <span class="badge bg-light text-dark">
            <i class="bi bi-calendar3"></i> <?= date('d/m/Y') ?>
          </span>
        </div>
      </div>
    </div>
  </nav>

  <div class="d-flex" id="wrapper">

    <!-- ========== SIDEBAR ========== -->
    <nav id="sidebar" class="sidebar bg-sidebar">
      <div class="sidebar-header text-center py-3">
        <h6 class="text-uppercase text-white-50 mb-0 small">Navigation</h6>
      </div>
      <ul class="nav flex-column px-2">
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/') ?>" href="<?= $baseUrl ?>/">
            <i class="bi bi-speedometer2"></i>
            <span>Tableau de bord</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/besoins') ?>" href="<?= $baseUrl ?>/besoins">
            <i class="bi bi-clipboard-pulse"></i>
            <span>Saisie des besoins</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/dons') ?>" href="<?= $baseUrl ?>/dons">
            <i class="bi bi-box-seam"></i>
            <span>Saisie des dons</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/dispatch') ?>" href="<?= $baseUrl ?>/dispatch">
            <i class="bi bi-truck"></i>
            <span>Simulation dispatch</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/dispatch/recap') ?>" href="<?= $baseUrl ?>/dispatch/recap">
            <i class="bi bi-clipboard-data"></i>
            <span>Récap dispatch</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/achats') ?>" href="<?= $baseUrl ?>/achats">
            <i class="bi bi-cart-check"></i>
            <span>Achat de produits</span>
          </a>
        </li>
      </ul>

      <hr class="mx-3 border-secondary">

      <ul class="nav flex-column px-2">
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/villes') ?>" href="<?= $baseUrl ?>/villes">
            <i class="bi bi-building"></i>
            <span>Gestion des villes</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link sidebar-link <?= isActive('/articles') ?>" href="<?= $baseUrl ?>/articles">
            <i class="bi bi-tags"></i>
            <span>Types d'articles</span>
          </a>
        </li>
      </ul>

      <!-- Sidebar footer -->
      <div class="sidebar-footer mt-auto p-3 text-center">
        <small class="text-white-50">ETU003951 ETU003993 ETU004367 &copy; <?= date('Y') ?></small>
      </div>
    </nav>

    <!-- ========== MAIN CONTENT ========== -->
    <div id="page-content" class="flex-grow-1">
      <!-- Breadcrumb -->
      <div class="content-header bg-white border-bottom px-4 py-2">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="<?= $baseUrl ?>/"><i class="bi bi-house"></i> Accueil</a></li>
            <?php if (!empty($breadcrumb)): ?>
              <?php foreach ($breadcrumb as $label => $url): ?>
                <?php if ($url): ?>
                  <li class="breadcrumb-item"><a href="<?= e($url) ?>"><?= e($label) ?></a></li>
                <?php else: ?>
                  <li class="breadcrumb-item active" aria-current="page"><?= e($label) ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>
          </ol>
        </nav>
      </div>

      <!-- Page content -->
      <main class="p-4">
        <?php if (!empty($flashSuccess)): ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill"></i> <?= e($flashSuccess) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= e($flashError) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <?php echo $content ?? ''; ?>
      </main>

      <!-- Footer -->
      <footer class="bg-white border-top py-3 px-4 mt-auto">
        <div class="d-flex flex-wrap justify-content-between align-items-center small text-muted">
          <span>
            <strong>BNGRC</strong> — Bureau National de Gestion des Risques et des Catastrophes
          </span>
          <span>
            ETU003951 ETU003993 ETU004367 &copy; <?= date('Y') ?>
          </span>
        </div>
      </footer>
    </div>
  </div>

  <script src="<?= $baseUrl ?>/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= $baseUrl ?>/assets/js/app.js"></script>
</body>
</html>
