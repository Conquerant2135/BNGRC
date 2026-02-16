<?php
$pageTitle = 'Simulation du dispatch';
$breadcrumb = ['Simulation dispatch' => null];
$baseUrl = BASE_URL;

ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
  <h4 class="mb-0"><i class="bi bi-truck me-2 text-warning"></i>Simulation du dispatch des dons</h4>
  <button class="btn btn-warning" id="btnLancerDispatch">
    <i class="bi bi-play-fill"></i> Lancer la simulation
  </button>
</div>

<!-- ===== INFO ===== -->
<div class="alert alert-info border-0 shadow-sm">
  <div class="d-flex align-items-center">
    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
    <div>
      <strong>Comment fonctionne le dispatch ?</strong><br>
      Les dons sont distribués aux villes par <strong>ordre de date de saisie</strong> (les dons les plus anciens sont distribués en premier). 
      Chaque ville reçoit des dons proportionnellement à ses besoins restants.
    </div>
  </div>
</div>

<!-- ===== PARAMÈTRES ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0"><i class="bi bi-sliders me-2 text-primary"></i>Paramètres de simulation</h6>
  </div>
  <div class="card-body">
    <form class="row g-3 align-items-end">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Date début</label>
        <input type="date" class="form-control" name="date_debut" value="2026-02-01">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Date fin</label>
        <input type="date" class="form-control" name="date_fin" value="<?= date('Y-m-d') ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Mode de répartition</label>
        <select class="form-select" name="mode">
          <option value="fifo">FIFO — Premier arrivé, premier servi</option>
          <option value="proportionnel">Proportionnel aux besoins</option>
        </select>
      </div>
    </form>
  </div>
</div>

<!-- ===== RÉSULTAT : Timeline ===== -->
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
    <h6 class="mb-0"><i class="bi bi-clock-history me-2 text-warning"></i>Résultat du dispatch (ordre chronologique)</h6>
    <span class="badge bg-warning text-dark">5 opérations simulées</span>
  </div>
  <div class="card-body p-0">
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
          <tr>
            <td><span class="badge bg-secondary rounded-pill">1</span></td>
            <td>11/02/2026</td>
            <td>Particulier anonyme</td>
            <td>Argent</td>
            <td class="text-end">2 000 000 Ar</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Antsirabe
            </td>
            <td class="text-end fw-semibold text-success">1 200 000 Ar</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr class="table-light">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end text-muted">Reste: 800 000 Ar</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Toamasina
            </td>
            <td class="text-end fw-semibold text-success">800 000 Ar</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr>
            <td><span class="badge bg-secondary rounded-pill">2</span></td>
            <td>12/02/2026</td>
            <td>ONG Care International</td>
            <td>Riz</td>
            <td class="text-end">1 000 kg</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Mananjary
            </td>
            <td class="text-end fw-semibold text-success">600 kg</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr class="table-light">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end text-muted">Reste: 400 kg</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Farafangana
            </td>
            <td class="text-end fw-semibold text-success">400 kg</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr>
            <td><span class="badge bg-secondary rounded-pill">3</span></td>
            <td>13/02/2026</td>
            <td>Banque BOA</td>
            <td>Argent</td>
            <td class="text-end">5 000 000 Ar</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Toamasina
            </td>
            <td class="text-end fw-semibold text-success">3 000 000 Ar</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr class="table-light">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end text-muted">Reste: 2 000 000 Ar</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Farafangana
            </td>
            <td class="text-end fw-semibold text-success">2 000 000 Ar</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr>
            <td><span class="badge bg-secondary rounded-pill">4</span></td>
            <td>14/02/2026</td>
            <td>Association Entraide</td>
            <td>Tôle</td>
            <td class="text-end">100 pièces</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Antsirabe
            </td>
            <td class="text-end fw-semibold text-success">80 pièces</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr class="table-light">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end text-muted">Reste: 20 pièces</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Morondava
            </td>
            <td class="text-end fw-semibold text-success">20 pièces</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr>
            <td><span class="badge bg-secondary rounded-pill">5</span></td>
            <td>15/02/2026</td>
            <td>Croix-Rouge Madagascar</td>
            <td>Riz</td>
            <td class="text-end">500 kg</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Antsirabe
            </td>
            <td class="text-end fw-semibold text-success">300 kg</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
          <tr class="table-light">
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-end text-muted">Reste: 200 kg</td>
            <td class="fw-semibold text-primary">
              <i class="bi bi-arrow-right-circle text-warning"></i> Toamasina
            </td>
            <td class="text-end fw-semibold text-success">200 kg</td>
            <td class="text-center"><span class="badge bg-success"><i class="bi bi-check"></i> Attribué</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ===== RÉSUMÉ PAR VILLE après dispatch ===== -->
<div class="card border-0 shadow-sm">
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
          <tr>
            <td class="fw-semibold">Antsirabe</td>
            <td class="text-center">12 articles</td>
            <td class="text-center text-success fw-semibold">8 articles</td>
            <td class="text-center text-danger">4 articles</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar bg-warning" style="width: 67%;">67%</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="fw-semibold">Mananjary</td>
            <td class="text-center">15 articles</td>
            <td class="text-center text-success fw-semibold">14 articles</td>
            <td class="text-center text-danger">1 article</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar bg-success" style="width: 93%;">93%</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="fw-semibold">Toamasina</td>
            <td class="text-center">10 articles</td>
            <td class="text-center text-success fw-semibold">4 articles</td>
            <td class="text-center text-danger">6 articles</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar bg-danger" style="width: 40%;">40%</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="fw-semibold">Morondava</td>
            <td class="text-center">6 articles</td>
            <td class="text-center text-success fw-semibold">6 articles</td>
            <td class="text-center text-success">0</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar bg-success" style="width: 100%;">100%</div>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="fw-semibold">Farafangana</td>
            <td class="text-center">9 articles</td>
            <td class="text-center text-success fw-semibold">6 articles</td>
            <td class="text-center text-danger">3 articles</td>
            <td>
              <div class="d-flex align-items-center gap-2">
                <div class="progress flex-grow-1" style="height: 20px;">
                  <div class="progress-bar bg-warning" style="width: 67%;">67%</div>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/layouts/base.php';
?>
