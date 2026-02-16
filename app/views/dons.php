<?php
$pageTitle = 'Saisie des dons';
$breadcrumb = ['Saisie des dons' => null];
$baseUrl = BASE_URL;

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-box-seam me-2 text-success"></i>Saisie des dons</h4>
</div>

<!-- ===== FORMULAIRE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-plus-circle me-2 text-success"></i>Enregistrer un nouveau don</h6>
  </div>
  <div class="card-body">
    <form id="formDon" method="post" novalidate>
      <div class="row g-3">
        <!-- Donateur -->
        <div class="col-md-4">
          <label for="donateur" class="form-label fw-semibold">Donateur <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="donateur" name="donateur" placeholder="Nom du donateur ou organisme" required>
          <div class="invalid-feedback">Nom du donateur requis.</div>
        </div>

        <!-- Type de don -->
        <div class="col-md-4">
          <label for="typeDon" class="form-label fw-semibold">Type de don <span class="text-danger">*</span></label>
          <select class="form-select" id="typeDon" name="type_don" required>
            <option value="">— Sélectionner —</option>
            <option value="nature">🍚 En nature (riz, huile, ...)</option>
            <option value="materiaux">🔧 En matériaux (tôle, clous, ...)</option>
            <option value="argent">💰 En argent</option>
          </select>
          <div class="invalid-feedback">Veuillez choisir un type.</div>
        </div>

        <!-- Date -->
        <div class="col-md-4">
          <label for="dateDon" class="form-label fw-semibold">Date du don <span class="text-danger">*</span></label>
          <input type="date" class="form-control" id="dateDon" name="date_don" value="<?= date('Y-m-d') ?>" required>
          <div class="invalid-feedback">Date requise.</div>
        </div>
      </div>

      <!-- Dynamic article lines -->
      <div class="mt-4">
        <label class="form-label fw-semibold">Détails du don</label>
        <div id="lignesDons">
          <div class="row g-2 mb-2 ligne-don align-items-end">
            <div class="col-md-5">
              <label class="form-label small text-muted">Article</label>
              <select class="form-select form-select-sm" name="article[]">
                <option value="">— Article —</option>
                <option value="riz">Riz</option>
                <option value="huile">Huile</option>
                <option value="eau">Eau</option>
                <option value="tole">Tôle</option>
                <option value="clous">Clous</option>
                <option value="ciment">Ciment</option>
                <option value="bois">Bois</option>
              </select>
            </div>
            <div class="col-md-3">
              <label class="form-label small text-muted">Quantité</label>
              <input type="number" class="form-control form-control-sm" name="quantite[]" min="1" placeholder="0">
            </div>
            <div class="col-md-3">
              <label class="form-label small text-muted">Unité</label>
              <select class="form-select form-select-sm" name="unite[]">
                <option value="kg">kg</option>
                <option value="L">Litres</option>
                <option value="pièces">Pièces</option>
                <option value="sacs">Sacs</option>
                <option value="Ar">Ariary</option>
              </select>
            </div>
            <div class="col-md-1">
              <button type="button" class="btn btn-sm btn-outline-danger w-100 btn-remove-line" title="Supprimer">
                <i class="bi bi-trash"></i>
              </button>
            </div>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-success mt-2" id="btnAddLineDon">
          <i class="bi bi-plus-lg"></i> Ajouter une ligne
        </button>
      </div>

      <!-- Observations -->
      <div class="mt-3">
        <label for="observationDon" class="form-label fw-semibold">Observations</label>
        <textarea class="form-control" id="observationDon" name="observation" rows="2" placeholder="Remarques éventuelles..."></textarea>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-lg"></i> Enregistrer le don
        </button>
        <button type="reset" class="btn btn-outline-secondary">
          <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
        </button>
      </div>
    </form>
  </div>
</div>

<!-- ===== LISTE DES DONS ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2 text-success"></i>Liste des dons enregistrés</h6>
    <div class="d-flex gap-2">
      <input type="text" class="form-control form-control-sm" placeholder="Rechercher un donateur..." style="width:200px;">
      <select class="form-select form-select-sm" style="width:150px;">
        <option value="">Tous les types</option>
        <option value="nature">En nature</option>
        <option value="materiaux">En matériaux</option>
        <option value="argent">En argent</option>
      </select>
    </div>
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
            <th>Unité</th>
            <th class="text-center">Statut</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-muted">1</td>
            <td>15/02/2026</td>
            <td class="fw-semibold">Croix-Rouge Madagascar</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Riz</td>
            <td class="text-end">500</td>
            <td>kg</td>
            <td class="text-center"><span class="badge bg-success">Distribué</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">2</td>
            <td>15/02/2026</td>
            <td class="fw-semibold">Croix-Rouge Madagascar</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Huile</td>
            <td class="text-end">200</td>
            <td>L</td>
            <td class="text-center"><span class="badge bg-success">Distribué</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">3</td>
            <td>14/02/2026</td>
            <td class="fw-semibold">Association Entraide</td>
            <td><span class="badge bg-secondary">Matériaux</span></td>
            <td>Tôle</td>
            <td class="text-end">100</td>
            <td>Pièces</td>
            <td class="text-center"><span class="badge bg-warning text-dark">En attente</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">4</td>
            <td>13/02/2026</td>
            <td class="fw-semibold">Banque BOA</td>
            <td><span class="badge bg-primary">Argent</span></td>
            <td>—</td>
            <td class="text-end">5 000 000</td>
            <td>Ar</td>
            <td class="text-center"><span class="badge bg-success">Distribué</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">5</td>
            <td>12/02/2026</td>
            <td class="fw-semibold">ONG Care International</td>
            <td><span class="badge bg-info">Nature</span></td>
            <td>Riz</td>
            <td class="text-end">1 000</td>
            <td>kg</td>
            <td class="text-center"><span class="badge bg-warning text-dark">En attente</span></td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card-footer bg-white d-flex justify-content-between align-items-center">
    <small class="text-muted">Affichage 1-5 sur 5 résultats</small>
    <nav>
      <ul class="pagination pagination-sm mb-0">
        <li class="page-item disabled"><a class="page-link" href="#">&laquo;</a></li>
        <li class="page-item active"><a class="page-link" href="#">1</a></li>
        <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
      </ul>
    </nav>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
