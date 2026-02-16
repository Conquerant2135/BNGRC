<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title . ' - BNGRC' : 'BNGRC'; ?></title>
    <link rel="stylesheet" href="<?=BASE_URL ?>/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=BASE_URL ?>/assets/css/style.css">
</head>
<body>

<header class="bg-light border-bottom">
    <div class="container-fluid py-3">
        <div class="row align-items-center">
            <div class="col-3 col-md-2 text-start">
                <img src="<?=BASE_URL ?>/assets/images/republique.png" alt="République" class="logo-small">
            </div>
            <div class="col-6 col-md-8 text-center">
                <img src="<?=BASE_URL ?>/assets/images/bngrc.png" alt="BNGRC" class="logo-main">
                <div class="site-title mt-2">BNGRC — Suivi des collectes et distributions</div>
            </div>
            <div class="col-3 col-md-2 text-end">
                <img src="<?=BASE_URL ?>/assets/images/logo-MI-2024-TRANSPARENT-NOIR.png" alt="MI" class="logo-small">
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?=BASE_URL ?>/">BNGRC</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="<?=BASE_URL ?>/dashboard">Tableau de bord</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?=BASE_URL ?>/besoins">Besoins</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?=BASE_URL ?>/dons">Dons</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?=BASE_URL ?>/villes">Villes</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container my-4">
