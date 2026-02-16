<?php
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
}

$pageTitle = "Types d'articles";
$breadcrumb = ["Types d'articles" => null];
$baseUrl = BASE_URL;

// Variables passées par le contrôleur
$articles   = $articles   ?? [];
$categories = $categories ?? [];
$unites     = $unites     ?? [];
$success    = $success    ?? ($_GET['success'] ?? null);
$error      = $error      ?? ($_GET['error']   ?? null);

// Icônes / couleurs par catégorie
$catStyles = [
    'Nourriture' => ['icon' => 'bi-basket3',    'color' => 'info',      'badge' => 'bg-info'],
    'Matériaux'  => ['icon' => 'bi-tools',       'color' => 'secondary', 'badge' => 'bg-secondary'],
    'Argent'     => ['icon' => 'bi-cash-stack',  'color' => 'primary',   'badge' => 'bg-primary'],
];
$defaultStyle = ['icon' => 'bi-tag', 'color' => 'dark', 'badge' => 'bg-dark'];

ob_start();
?>

<?php if ($success): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><?= e($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= e($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-tags me-2 text-primary"></i>Gestion des types d'articles</h4>
  <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalArticle">
    <i class="bi bi-plus-lg"></i> Ajouter un article
  </button>
</div>

<!-- ===== CATÉGORIES ===== -->
<div class="row g-3 mb-4">
  <?php foreach ($categories as $cat): ?>
    <?php $style = $catStyles[$cat['nom']] ?? $defaultStyle; ?>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm border-start border-4 border-<?= $style['color'] ?> h-100">
        <div class="card-body text-center">
          <i class="bi <?= $style['icon'] ?> fs-1 text-<?= $style['color'] ?>"></i>
          <h6 class="mt-2 fw-bold"><?= e($cat['nom']) ?></h6>
          <div class="mt-2"><span class="badge <?= $style['badge'] ?>"><?= (int) $cat['nb_articles'] ?> article<?= $cat['nb_articles'] > 1 ? 's' : '' ?></span></div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- ===== LISTE ===== -->
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
    <h6 class="mb-0"><i class="bi bi-list-ul me-2"></i>Tous les articles</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover table-striped mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Nom de l'article</th>
            <th>Catégorie</th>
            <th>Unité</th>
            <th class="text-end">Prix unitaire</th>
            <th class="text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($articles)): ?>
            <tr>
              <td colspan="6" class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucun article enregistré
              </td>
            </tr>
          <?php else: ?>
            <?php $i = 0; foreach ($articles as $art): $i++; ?>
              <?php $style = $catStyles[$art['categorie'] ?? ''] ?? $defaultStyle; ?>
              <tr>
                <td class="text-muted"><?= $i ?></td>
                <td class="fw-semibold"><?= e($art['nom']) ?></td>
                <td><span class="badge <?= $style['badge'] ?>"><?= e($art['categorie'] ?? 'Autre') ?></span></td>
                <td><?= e($art['unite'] ?? '—') ?></td>
                <td class="text-end"><?= number_format($art['prix_unitaire'], ($art['prix_unitaire'] == intval($art['prix_unitaire'])) ? 0 : 3, ',', ' ') ?></td>
                <td class="text-center">
                  <button class="btn btn-sm btn-outline-warning"
                          data-bs-toggle="modal" data-bs-target="#modalEditArticle"
                          onclick="fillEditModal(<?= (int)$art['id'] ?>, '<?= e($art['nom']) ?>', <?= (int)($art['id_cat'] ?? 0) ?>, <?= (int)($art['id_unite'] ?? 0) ?>, <?= (float)$art['prix_unitaire'] ?>)">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form method="post" action="<?= $baseUrl ?>/articles/supprimer" class="d-inline"
                        onsubmit="return confirm('Supprimer cet article ?');">
                    <input type="hidden" name="id" value="<?= (int)$art['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
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
        <form id="formArticle" method="post" action="<?= $baseUrl ?>/articles/ajouter">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de l'article <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nom_article" placeholder="Ex: Riz" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
            <select class="form-select" name="categorie" required>
              <option value="">— Sélectionner —</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['id'] ?>"><?= e($cat['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
            <select class="form-select" name="unite_defaut" required>
              <option value="">— Sélectionner —</option>
              <?php foreach ($unites as $u): ?>
                <option value="<?= (int) $u['id'] ?>"><?= e($u['libelle']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Prix unitaire</label>
            <input type="number" class="form-control" name="prix_unitaire" step="0.001" min="0" value="0" placeholder="0">
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

<!-- ===== MODAL MODIFIER ARTICLE ===== -->
<div class="modal fade" id="modalEditArticle" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier l'article</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditArticle" method="post" action="<?= $baseUrl ?>/articles/modifier">
          <input type="hidden" name="id" id="edit_id">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nom de l'article <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="nom_article" id="edit_nom" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
            <select class="form-select" name="categorie" id="edit_categorie" required>
              <option value="">— Sélectionner —</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['id'] ?>"><?= e($cat['nom']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Unité <span class="text-danger">*</span></label>
            <select class="form-select" name="unite_defaut" id="edit_unite" required>
              <option value="">— Sélectionner —</option>
              <?php foreach ($unites as $u): ?>
                <option value="<?= (int) $u['id'] ?>"><?= e($u['libelle']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Prix unitaire</label>
            <input type="number" class="form-control" name="prix_unitaire" id="edit_prix" step="0.001" min="0">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" form="formEditArticle" class="btn btn-warning">
          <i class="bi bi-check-lg"></i> Modifier
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function fillEditModal(id, nom, idCat, idUnite, prix) {
  document.getElementById('edit_id').value = id;
  document.getElementById('edit_nom').value = nom;
  document.getElementById('edit_categorie').value = idCat;
  document.getElementById('edit_unite').value = idUnite;
  document.getElementById('edit_prix').value = prix;
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
