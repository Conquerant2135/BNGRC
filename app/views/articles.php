<?php
$pageTitle = "Types d'articles";
$breadcrumb = ["Types d'articles" => null];
$baseUrl = BASE_URL;

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-tags me-2 text-primary"></i>Gestion des types d'articles</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalArticle">
    <i class="bi bi-plus-lg"></i> Ajouter un article
  </button>
</div>

<!-- ===== CATÉGORIES ===== -->
<div class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm border-start border-4 border-info h-100">
      <div class="card-body text-center">
        <i class="bi bi-basket3 fs-1 text-info"></i>
        <h6 class="mt-2 fw-bold">En nature</h6>
        <span class="text-muted small">Riz, Huile, Eau, Savon...</span>
        <div class="mt-2"><span class="badge bg-info">4 articles</span></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm border-start border-4 border-secondary h-100">
      <div class="card-body text-center">
        <i class="bi bi-tools fs-1 text-secondary"></i>
        <h6 class="mt-2 fw-bold">En matériaux</h6>
        <span class="text-muted small">Tôle, Clous, Ciment, Bois...</span>
        <div class="mt-2"><span class="badge bg-secondary">4 articles</span></div>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm border-start border-4 border-primary h-100">
      <div class="card-body text-center">
        <i class="bi bi-cash-stack fs-1 text-primary"></i>
        <h6 class="mt-2 fw-bold">En argent</h6>
        <span class="text-muted small">Ariary (Ar)</span>
        <div class="mt-2"><span class="badge bg-primary">1 article</span></div>
      </div>
    </div>
  </div>
</div>

<!-- ===== LISTE ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Tous les articles</h6>
    <select class="form-select form-select-sm" style="width:180px;">
      <option value="">Toutes les catégories</option>
      <option value="nature">En nature</option>
      <option value="materiaux">En matériaux</option>
      <option value="argent">En argent</option>
    </select>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nom de l'article</th>
            <th>Catégorie</th>
            <th>Unité par défaut</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-muted">1</td>
            <td class="fw-semibold">Riz</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>kg</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">2</td>
            <td class="fw-semibold">Huile</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Litres</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">3</td>
            <td class="fw-semibold">Eau</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Litres</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">4</td>
            <td class="fw-semibold">Savon</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Pièces</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">5</td>
            <td class="fw-semibold">Tôle</td>
            <td><span class="badge bg-secondary">Matériaux</span></td>
            <td>Pièces</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">6</td>
            <td class="fw-semibold">Clous</td>
            <td><span class="badge bg-secondary">Matériaux</span></td>
            <td>kg</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">7</td>
            <td class="fw-semibold">Ciment</td>
            <td><span class="badge bg-secondary">Matériaux</span></td>
            <td>Sacs</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">8</td>
            <td class="fw-semibold">Bois</td>
            <td><span class="badge bg-secondary">Matériaux</span></td>
            <td>Pièces</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">9</td>
            <td class="fw-semibold">Argent</td>
            <td><span class="badge bg-primary">Argent</span></td>
            <td>Ariary</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ===== MODAL AJOUT ARTICLE ===== -->
<div class="modal fade" id="modalArticle" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-tags me-2"></i>Ajouter un article</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formArticle" method="post">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de l'article <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nom_article" placeholder="Ex: Riz" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
            <select class="form-select" name="categorie" required>
              <option value="">— Sélectionner —</option>
              <option value="nature">En nature</option>
              <option value="materiaux">En matériaux</option>
              <option value="argent">En argent</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Unité par défaut <span class="text-danger">*</span></label>
            <select class="form-select" name="unite_defaut" required>
              <option value="kg">kg</option>
              <option value="L">Litres</option>
              <option value="pièces">Pièces</option>
              <option value="sacs">Sacs</option>
              <option value="Ar">Ariary</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="formArticle" class="btn btn-primary">
          <i class="bi bi-check-lg"></i> Enregistrer
        </button>
      </div>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
