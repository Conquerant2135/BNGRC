<?php
$pageTitle = 'Gestion des villes';
$breadcrumb = ['Gestion des villes' => null];
$baseUrl = BASE_URL;

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-building me-2 text-primary"></i>Gestion des villes sinistrées</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalVille">
    <i class="bi bi-plus-lg"></i> Ajouter une ville
  </button>
</div>

<!-- ===== LISTE DES VILLES ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Villes enregistrées</h6>
    <input type="text" class="form-control form-control-sm" placeholder="Rechercher..." style="width:200px;">
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
            <th class="text-center">Date enregistrement</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-muted">1</td>
            <td class="fw-semibold">Antsirabe</td>
            <td>Vakinankaratra</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">1 250</span></td>
            <td class="text-center text-muted small">10/01/2026</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">2</td>
            <td class="fw-semibold">Mananjary</td>
            <td>Vatovavy</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">3 400</span></td>
            <td class="text-center text-muted small">10/01/2026</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">3</td>
            <td class="fw-semibold">Toamasina</td>
            <td>Atsinanana</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">2 100</span></td>
            <td class="text-center text-muted small">11/01/2026</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">4</td>
            <td class="fw-semibold">Morondava</td>
            <td>Menabe</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">890</span></td>
            <td class="text-center text-muted small">12/01/2026</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
          <tr>
            <td class="text-muted">5</td>
            <td class="fw-semibold">Farafangana</td>
            <td>Atsimo-Atsinanana</td>
            <td class="text-center"><span class="badge bg-danger-soft text-danger">1 750</span></td>
            <td class="text-center text-muted small">12/01/2026</td>
            <td class="text-center">
              <button class="btn btn-sm btn-outline-warning" title="Modifier"><i class="bi bi-pencil"></i></button>
              <button class="btn btn-sm btn-outline-danger" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ===== MODAL AJOUT VILLE ===== -->
<div class="modal fade" id="modalVille" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-building me-2"></i>Ajouter une ville</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formVille" method="post">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de la ville <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nom_ville" placeholder="Ex: Antsirabe" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Région <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="region" placeholder="Ex: Vakinankaratra" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Nombre de sinistrés estimé</label>
            <input type="number" class="form-control" name="nb_sinistres" min="0" placeholder="0">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="formVille" class="btn btn-primary">
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
