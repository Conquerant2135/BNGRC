<?php
if (!function_exists('e')) {
    function e($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }
}

$pageTitle  = 'Récapitulation des dispatch';
$breadcrumb = ['Récapitulation dispatch' => null];
$baseUrl    = BASE_URL;

$global = $global ?? [];
$villes = $villes ?? [];

// Calculs globaux
$montantTotal     = (float)($global['montant_total'] ?? 0);
$montantSatisfait = (float)($global['montant_satisfait'] ?? 0) + (float)($global['montant_partiellement_couvert'] ?? 0);
$montantRestant   = max(0, $montantTotal - $montantSatisfait);
$nbBesoins        = (int)($global['nb_besoins'] ?? 0);
$nbSatisfaits     = (int)($global['nb_satisfaits'] ?? 0);
$nbInsatisfaits   = (int)($global['nb_insatisfaits'] ?? 0);
$tauxGlobal       = $montantTotal > 0 ? round(($montantSatisfait / $montantTotal) * 100, 1) : 0;

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-clipboard-data me-2 text-primary"></i>Récapitulation des dispatch</h4>
  <button id="btnActualiser" class="btn btn-outline-primary" onclick="actualiserRecap()">
    <i class="bi bi-arrow-clockwise me-1"></i>Actualiser
  </button>
</div>

<!-- ===== CARTES MONTANTS GLOBAUX ===== -->
<div id="recap-global" class="row g-3 mb-4">
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <div class="mb-2"><i class="bi bi-wallet2 fs-2 text-primary"></i></div>
        <h6 class="text-muted mb-1">Montant total des besoins</h6>
        <h3 class="fw-bold text-primary mb-1" id="val-total"><?= number_format($montantTotal, 0, ',', ' ') ?></h3>
        <small class="text-muted"><span id="val-nb-besoins"><?= $nbBesoins ?></span> besoin<?= $nbBesoins > 1 ? 's' : '' ?> enregistré<?= $nbBesoins > 1 ? 's' : '' ?></small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <div class="mb-2"><i class="bi bi-check-circle fs-2 text-success"></i></div>
        <h6 class="text-muted mb-1">Montant couvert (satisfaits)</h6>
        <h3 class="fw-bold text-success mb-1" id="val-satisfait"><?= number_format($montantSatisfait, 0, ',', ' ') ?></h3>
        <small class="text-muted"><span id="val-nb-satisfaits"><?= $nbSatisfaits ?></span> besoin<?= $nbSatisfaits > 1 ? 's' : '' ?> satisfait<?= $nbSatisfaits > 1 ? 's' : '' ?></small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card border-0 shadow-sm h-100">
      <div class="card-body text-center">
        <div class="mb-2"><i class="bi bi-exclamation-triangle fs-2 text-danger"></i></div>
        <h6 class="text-muted mb-1">Montant restant à couvrir</h6>
        <h3 class="fw-bold text-danger mb-1" id="val-restant"><?= number_format($montantRestant, 0, ',', ' ') ?></h3>
        <small class="text-muted"><span id="val-nb-insatisfaits"><?= $nbInsatisfaits ?></span> besoin<?= $nbInsatisfaits > 1 ? 's' : '' ?> non satisfait<?= $nbInsatisfaits > 1 ? 's' : '' ?></small>
      </div>
    </div>
  </div>
</div>

<!-- ===== BARRE DE PROGRESSION GLOBALE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <span class="fw-semibold">Taux de couverture global</span>
      <span class="fw-bold" id="val-taux"><?= $tauxGlobal ?>%</span>
    </div>
    <div class="progress" style="height: 25px;">
      <div id="bar-taux" class="progress-bar <?= $tauxGlobal >= 90 ? 'bg-success' : ($tauxGlobal >= 50 ? 'bg-warning' : 'bg-danger') ?>"
           role="progressbar" style="width: <?= min($tauxGlobal, 100) ?>%;">
        <?= $tauxGlobal ?>%
      </div>
    </div>
  </div>
</div>

<!-- ===== TABLEAU PAR VILLE ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-geo-alt me-2 text-warning"></i>Détail par ville</h6>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-hover mb-0 align-middle">
        <thead class="table-light">
          <tr>
            <th>Ville</th>
            <th class="text-center">Besoins</th>
            <th class="text-end">Montant total</th>
            <th class="text-end">Montant couvert</th>
            <th class="text-end">Montant restant</th>
            <th>Couverture</th>
          </tr>
        </thead>
        <tbody id="tbody-villes">
          <?php foreach ($villes as $v):
            $mt = (float)$v['montant_total_besoins'];
            $ms = (float)$v['montant_satisfait'] + (float)$v['montant_partiellement_couvert'];
            $mr = max(0, $mt - $ms);
            $tx = $mt > 0 ? round(($ms / $mt) * 100, 1) : 0;
            $bgClass = $tx >= 90 ? 'bg-success' : ($tx >= 50 ? 'bg-warning' : 'bg-danger');
          ?>
          <tr>
            <td class="fw-semibold"><?= e($v['nom_ville']) ?></td>
            <td class="text-center">
              <span class="badge bg-primary"><?= (int)$v['nb_besoins'] ?></span>
              <span class="badge bg-success"><?= (int)$v['nb_satisfaits'] ?> <i class="bi bi-check"></i></span>
              <?php if ((int)$v['nb_insatisfaits'] > 0): ?>
                <span class="badge bg-danger"><?= (int)$v['nb_insatisfaits'] ?> <i class="bi bi-x"></i></span>
              <?php endif; ?>
            </td>
            <td class="text-end"><?= number_format($mt, 0, ',', ' ') ?> Ar</td>
            <td class="text-end text-success fw-semibold"><?= number_format($ms, 0, ',', ' ') ?> Ar</td>
            <td class="text-end <?= $mr > 0 ? 'text-danger' : 'text-success' ?>"><?= number_format($mr, 0, ',', ' ') ?> Ar</td>
            <td style="min-width:160px;">
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar <?= $bgClass ?>" style="width: <?= min($tx, 100) ?>%;"><?= $tx ?>%</div>
                </div>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (empty($villes)): ?>
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="bi bi-inbox fs-3 d-block mb-2"></i>
              Aucune donnée de dispatch disponible.
            </td>
          </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function actualiserRecap() {
  const btn = document.getElementById('btnActualiser');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Chargement…';

  fetch('<?= $baseUrl ?>/dispatch/recap/json')
    .then(r => r.json())
    .then(data => {
      const g = data.global;
      const montantTotal = parseFloat(g.montant_total) || 0;
      const montantSatisfait = (parseFloat(g.montant_satisfait) || 0) + (parseFloat(g.montant_partiellement_couvert) || 0);
      const montantRestant = Math.max(0, montantTotal - montantSatisfait);
      const taux = montantTotal > 0 ? Math.round((montantSatisfait / montantTotal) * 1000) / 10 : 0;

      const fmt = n => new Intl.NumberFormat('fr-FR', {maximumFractionDigits: 0}).format(n);

      document.getElementById('val-total').textContent = fmt(montantTotal);
      document.getElementById('val-satisfait').textContent = fmt(montantSatisfait);
      document.getElementById('val-restant').textContent = fmt(montantRestant);
      document.getElementById('val-nb-besoins').textContent = g.nb_besoins;
      document.getElementById('val-nb-satisfaits').textContent = g.nb_satisfaits;
      document.getElementById('val-nb-insatisfaits').textContent = g.nb_insatisfaits;
      document.getElementById('val-taux').textContent = taux + '%';

      const bar = document.getElementById('bar-taux');
      bar.style.width = Math.min(taux, 100) + '%';
      bar.textContent = taux + '%';
      bar.className = 'progress-bar ' + (taux >= 90 ? 'bg-success' : (taux >= 50 ? 'bg-warning' : 'bg-danger'));

      // Tableau villes
      let html = '';
      if (data.villes.length === 0) {
        html = '<tr><td colspan="6" class="text-center text-muted py-4"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Aucune donnée de dispatch disponible.</td></tr>';
      } else {
        data.villes.forEach(v => {
          const mt = parseFloat(v.montant_total_besoins) || 0;
          const ms = (parseFloat(v.montant_satisfait) || 0) + (parseFloat(v.montant_partiellement_couvert) || 0);
          const mr = Math.max(0, mt - ms);
          const tx = mt > 0 ? Math.round((ms / mt) * 1000) / 10 : 0;
          const bg = tx >= 90 ? 'bg-success' : (tx >= 50 ? 'bg-warning' : 'bg-danger');

          html += '<tr>';
          html += '<td class="fw-semibold">' + v.nom_ville + '</td>';
          html += '<td class="text-center">';
          html += '<span class="badge bg-primary">' + v.nb_besoins + '</span> ';
          html += '<span class="badge bg-success">' + v.nb_satisfaits + ' <i class="bi bi-check"></i></span> ';
          if (parseInt(v.nb_insatisfaits) > 0) {
            html += '<span class="badge bg-danger">' + v.nb_insatisfaits + ' <i class="bi bi-x"></i></span>';
          }
          html += '</td>';
          html += '<td class="text-end">' + fmt(mt) + ' Ar</td>';
          html += '<td class="text-end text-success fw-semibold">' + fmt(ms) + ' Ar</td>';
          html += '<td class="text-end ' + (mr > 0 ? 'text-danger' : 'text-success') + '">' + fmt(mr) + ' Ar</td>';
          html += '<td style="min-width:160px;"><div class="d-flex align-items-center gap-2">';
          html += '<div class="progress flex-grow-1" style="height:20px;">';
          html += '<div class="progress-bar ' + bg + '" style="width:' + Math.min(tx, 100) + '%;">' + tx + '%</div>';
          html += '</div></div></td>';
          html += '</tr>';
        });
      }
      document.getElementById('tbody-villes').innerHTML = html;
    })
    .catch(err => {
      console.error(err);
      alert('Erreur lors de l\'actualisation.');
    })
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bi bi-arrow-clockwise me-1"></i>Actualiser';
    });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
