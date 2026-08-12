<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Saisie des notes — DiangÉcole</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#F7F7F4;
    --card:#FFFFFF;
    --border:#E6E6E1;
    --border-soft:#EDEDE9;
    --ink:#151915;
    --ink-soft:#5B6560;
    --grey:#8A8F8A;
    --green:#2E6B4E;
    --green-dark:#20503A;
    --green-bg:#E7F3EB;
    --green-bg-strong:#DCEEE3;
    --amber:#B8860B;
    --shadow: 0 1px 2px rgba(20,25,20,0.04), 0 8px 24px -12px rgba(20,25,20,0.10);
  }
  *{box-sizing:border-box;}
  html,body{margin:0;padding:0;}
  body{
    background:var(--bg);
    color:var(--ink);
    font-family:'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    -webkit-font-smoothing:antialiased;
    min-height:100vh;
  }
  .page{max-width:1360px;margin:0 auto;padding:0 40px 64px;}

  header.topbar{
    display:flex;align-items:center;justify-content:space-between;
    padding:22px 40px;
    border-bottom:1px solid var(--border);
    background:var(--bg);
  }
  .brand{
    font-size:13px;font-weight:800;letter-spacing:.09em;color:var(--ink-soft);
  }
  .top-right{display:flex;align-items:center;gap:18px;}
  .year-pill{
    display:flex;align-items:center;gap:7px;
    padding:7px 14px;border-radius:999px;background:var(--card);
    border:1px solid var(--border);font-size:13px;font-weight:600;color:var(--ink);
  }
  .dot{width:7px;height:7px;border-radius:50%;background:var(--green);display:inline-block;}
  .user{display:flex;align-items:center;gap:10px;text-decoration:none;color:inherit;}
  .avatar-user{
    width:38px;height:38px;border-radius:50%;background:var(--green-bg);
    color:var(--green-dark);font-weight:800;font-size:13px;
    display:flex;align-items:center;justify-content:center;
  }
  .user-meta{line-height:1.25;}
  .user-name{font-size:13.5px;font-weight:700;color:var(--ink);}
  .user-role{font-size:12px;color:var(--grey);}
  .btn-logout{
    display:inline-flex;align-items:center;gap:6px;
    font-size:12.5px;font-weight:700;color:#A83232;background:#FBECEC;
    padding:6px 12px;border-radius:8px;text-decoration:none;
    transition:background .15s ease;
  }
  .btn-logout:hover{background:#F7D5D5;}

  .flash-banner {
    margin-bottom: 24px;
    padding: 14px 20px;
    border-radius: 12px;
    font-size: 14.5px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 12px;
  }
  .flash-success {
    background: var(--green-bg);
    border: 1px solid #BFE0CC;
    color: var(--green-dark);
  }
  .flash-error {
    background: #FDEDED;
    border: 1px solid #F5C2C2;
    color: #9E2A2A;
  }

  .hero{display:flex;align-items:flex-start;justify-content:space-between;padding:44px 0 30px;flex-wrap:wrap;gap:20px;}
  .eyebrow{
    font-size:12.5px;font-weight:800;letter-spacing:.14em;color:var(--green);
    margin-bottom:10px;
  }
  h1.title{
    font-family:'Baloo 2', 'Plus Jakarta Sans', sans-serif;
    font-size:46px;line-height:1;font-weight:800;margin:0 0 12px;color:var(--ink);
    letter-spacing:-0.01em;
  }
  .subtitle{font-size:15.5px;color:var(--ink-soft);max-width:520px;line-height:1.5;}
  .hero-actions{display:flex;gap:12px;flex-shrink:0;padding-top:6px;}
  .btn{
    display:flex;align-items:center;gap:8px;
    font-family:inherit;font-size:14px;font-weight:700;
    padding:13px 20px;border-radius:12px;cursor:pointer;
    border:1px solid transparent;transition:transform .12s ease, box-shadow .12s ease, background .15s ease;
    white-space:nowrap;text-decoration:none;
  }
  .btn:active{transform:translateY(1px) scale(.99);}
  .btn svg{width:16px;height:16px;flex-shrink:0;}
  .btn-primary{background:var(--green-dark);color:#fff;box-shadow:var(--shadow);}
  .btn-primary:hover{background:#173D2B;}
  .btn-validate{
    background:var(--green);color:#fff;
  }
  .btn-validate:hover{background:var(--green-dark);}

  form.filters-card{
    background:var(--card);border:1px solid var(--border);border-radius:18px;
    box-shadow:var(--shadow);
    padding:26px 28px;
    display:flex;align-items:flex-end;gap:22px;flex-wrap:wrap;
    transition:box-shadow .3s ease, border-color .3s ease;
  }
  .field{display:flex;flex-direction:column;gap:9px;min-width:180px;flex:1 1 180px;}
  .field label{font-size:13.5px;font-weight:700;color:var(--ink);}
  .select-wrap{position:relative;}
  .select-wrap svg{
    position:absolute;right:14px;top:50%;transform:translateY(-50%);
    width:15px;height:15px;color:var(--grey);pointer-events:none;
  }
  select{
    appearance:none;-webkit-appearance:none;
    width:100%;font-family:inherit;font-size:15px;font-weight:600;color:var(--ink);
    background:var(--card);border:1px solid var(--border);border-radius:12px;
    padding:13px 38px 13px 15px;cursor:pointer;
  }
  select:focus-visible, .btn:focus-visible, input:focus-visible{
    outline:2px solid var(--green);outline-offset:2px;
  }

  .validate-field{flex:0 0 auto;min-width:150px;}
  .validate-field label{visibility:hidden;}
  .btn-validate{
    width:100%;justify-content:center;padding:13px 18px;border-radius:12px;font-size:14px;
  }

  .divider{width:1px;align-self:stretch;background:var(--border);margin:0 2px;}
  .stat{display:flex;flex-direction:column;gap:6px;padding-bottom:2px;}
  .stat-label{font-size:13px;color:var(--ink-soft);font-weight:600;}
  .stat-value{font-size:30px;font-weight:800;color:var(--green);}
  .stat-value span{font-size:15px;font-weight:700;color:var(--grey);}

  .table-card{
    margin-top:26px;background:var(--card);border:1px solid var(--border);
    border-radius:18px;box-shadow:var(--shadow);overflow:hidden;
  }
  table{width:100%;border-collapse:collapse;}
  thead th{
    text-align:left;font-size:11.5px;font-weight:800;letter-spacing:.08em;
    color:var(--grey);text-transform:uppercase;
    padding:18px 22px;border-bottom:1px solid var(--border);background:#FCFCFB;
  }
  thead th.num{text-align:left;}
  tbody td{padding:14px 22px;border-bottom:1px solid var(--border-soft);vertical-align:middle;}
  tbody tr:last-child td{border-bottom:none;}
  tbody tr{transition:background .15s ease;}
  tbody tr:hover{background:#FBFCFA;}

  .idx{color:var(--grey);font-weight:600;font-size:14px;width:26px;}
  .eleve-cell{display:flex;align-items:center;gap:12px;}
  .avatar{
    width:38px;height:38px;border-radius:50%;background:var(--green-bg);
    color:var(--green-dark);font-weight:800;font-size:12.5px;flex-shrink:0;
    display:flex;align-items:center;justify-content:center;
  }
  .eleve-name{font-weight:700;font-size:14.5px;color:var(--ink);}
  .eleve-id{font-size:12.5px;color:var(--grey);margin-top:1px;}

  .grade-input{
    width:70px;text-align:center;font-family:inherit;font-weight:700;font-size:14.5px;color:var(--ink);
    background:var(--card);border:1px solid var(--border);border-radius:10px;padding:9px 6px;
  }
  .grade-input.comp{background:var(--green-bg);border-color:#CFE7DA;}
  .grade-input:focus-visible{outline:2px solid var(--green);outline-offset:1px;}

  .moyenne-val{font-weight:800;font-size:16px;color:var(--green);}

  .pill{
    display:inline-flex;align-items:center;gap:7px;
    background:var(--green-bg);color:var(--green-dark);
    font-size:13px;font-weight:700;padding:7px 13px;border-radius:999px;
  }
  .pill .pdot{width:6px;height:6px;border-radius:50%;background:var(--green);}
  .pill.low{background:#FBECEC;color:#A83232;}
  .pill.low .pdot{background:#C24444;}
  .pill.mid{background:#FBF3E4;color:#946A0E;}
  .pill.mid .pdot{background:#C7940E;}

  tfoot td{
    padding:16px 22px;font-size:13px;color:var(--grey);
  }

  @media (max-width:900px){
    .page{padding:0 18px 48px;}
    header.topbar{padding:18px;}
    h1.title{font-size:34px;}
    .hero{padding:30px 0 22px;}
    .filters-card{padding:20px;}
    .divider{display:none;}
    .stat{flex:1 1 100%;}
    thead{display:none;}
    table, tbody, tr, td{display:block;width:100%;}
    tbody tr{padding:16px 20px;border-bottom:1px solid var(--border);}
    tbody td{padding:6px 0;border:none;}
    .eleve-cell{margin-bottom:8px;}
    .grade-input{width:80px;}
  }
</style>
</head>
<body>

<header class="topbar">
  <div class="brand">GROUPE SCOLAIRE AL AMAL</div>
  <div class="top-right">
    <div class="year-pill">
      <span class="dot"></span>
      <?= htmlspecialchars($anneeActive['nom'] ?? '') ?>
    </div>
    <div class="user">
      <?php 
        $userPrenom = $currentUser['prenom'] ?? '';
        $userNom = $currentUser['nom'] ?? '';
        $initials = strtoupper(substr($userPrenom, 0, 1) . substr($userNom, 0, 1));
      ?>
      <div class="avatar-user"><?= htmlspecialchars($initials) ?></div>
      <div class="user-meta">
        <div class="user-name"><?= htmlspecialchars($userPrenom . ' ' . $userNom) ?></div>
        <div class="user-role"><?= htmlspecialchars($currentUser['nomrole'] ?? '') ?></div>
      </div>
    </div>
    <a href="/logout" class="btn btn-primary" title="Déconnexion">
      Déconnexion
    </a>
  </div>
</header>

<div class="page">

  <div class="hero">
    <div>
      <div class="eyebrow">PÉDAGOGIE</div>
      <h1 class="title">Saisie des notes</h1>
      <p class="subtitle">Une grille simple, rapide et contrôlée. Les moyennes sont recalculées instantanément.</p>
    </div>
    <div class="hero-actions">
      <button class="btn btn-primary" type="submit" form="notesForm">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        Enregistrer les notes
      </button>
    </div>
  </div>

  <?php if (!empty($flashMessage)): ?>
    <div class="flash-banner <?= $flashMessage['type'] === 'success' ? 'flash-success' : 'flash-error' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" width="20" height="20">
        <?php if ($flashMessage['type'] === 'success'): ?>
          <polyline points="20 6 9 17 4 12"/>
        <?php else: ?>
          <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        <?php endif; ?>
      </svg>
      <span><?= htmlspecialchars($flashMessage['text']) ?></span>
    </div>
  <?php endif; ?>

  <!-- FORMULAIRE DE FILTRE (GET) -->
  <form method="GET" action="/gestion" class="filters-card" id="filtersCard">
    <div class="field">
      <label for="classe">Classe</label>
      <div class="select-wrap">
        <select id="classe" name="classe_id">
          <?php foreach ($classes as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $selectedClasseId ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nomclasse']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="field">
      <label for="matiere">Matière</label>
      <div class="select-wrap">
        <select id="matiere" name="matiere_id">
          <?php foreach ($matieres as $m): ?>
            <option value="<?= $m['id'] ?>" <?= $m['id'] == $selectedMatiereId ? 'selected' : '' ?>>
              <?= htmlspecialchars($m['nommatiere']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="field">
      <label for="periode">Période</label>
      <div class="select-wrap">
        <select id="periode" name="periode_id">
          <?php foreach ($periodes as $p): ?>
            <option value="<?= $p['id'] ?>" <?= $p['id'] == $selectedPeriodeId ? 'selected' : '' ?>>
              <?= htmlspecialchars($p['nomperiode']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>

    <div class="field validate-field">
      <label for="validateBtn">Valider</label>
      <button class="btn btn-validate" id="validateBtn" type="submit">
        Valider
      </button>
    </div>

    <div class="divider"></div>

    <div class="stat">
      <div class="stat-label">Moyenne de classe</div>
      <div class="stat-value" id="classAvg"><?= number_format($moyenneClasseMatiere, 2, '.', '') ?><span>/20</span></div>
    </div>
  </form>

  <!-- FORMULAIRE DES NOTES (POST) -->
  <form method="POST" action="/gestion/save" id="notesForm">
    <input type="hidden" name="classe_id" value="<?= $selectedClasseId ?>">
    <input type="hidden" name="matiere_id" value="<?= $selectedMatiereId ?>">
    <input type="hidden" name="periode_id" value="<?= $selectedPeriodeId ?>">

    <div class="table-card">
      <table>
        <thead>
          <tr>
            <th class="num">Élève</th>
            <th class="num">Devoir 1 /20</th>
            <th class="num">Devoir 2 /20</th>
            <th class="num">Composition /20</th>
            <th class="num">Moyenne</th>
            <th class="num">Appréciation</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($elevesNotes)): ?>
            <tr>
              <td colspan="6" style="text-align:center; padding:32px; color:var(--grey);">
                Aucun élève inscrit dans cette classe pour cette année.
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($elevesNotes as $i => $item): ?>
              <?php 
                $d1 = (float)($item['devoir1'] ?? 0);
                $d2 = (float)($item['devoir2'] ?? 0);
                $comp = (float)($item['composition'] ?? 0);
                $moy = calculer_moyenne_eleve($d1, $d2, $comp);
                $app = get_appreciation_note($moy);
                $inscId = $item['inscription_id'];
                $fullName = trim(($item['prenom'] ?? '') . ' ' . ($item['nom'] ?? ''));
                $initials = strtoupper(substr($item['prenom'] ?? '', 0, 1) . substr($item['nom'] ?? '', 0, 1));
              ?>
              <tr>
                <td>
                  <div class="eleve-cell">
                    <div class="idx"><?= $i + 1 ?></div>
                    <div class="avatar"><?= htmlspecialchars($initials) ?></div>
                    <div>
                      <div class="eleve-name"><?= htmlspecialchars($fullName) ?></div>
                      <div class="eleve-id"><?= htmlspecialchars($item['matricule'] ?? '') ?></div>
                    </div>
                  </div>
                </td>
                <td>
                  <input class="grade-input" type="number" min="0" max="20" step="0.5" 
                         name="notes[<?= $inscId ?>][devoir1]" value="<?= htmlspecialchars((string)$d1) ?>" data-field="devoir1">
                </td>
                <td>
                  <input class="grade-input" type="number" min="0" max="20" step="0.5" 
                         name="notes[<?= $inscId ?>][devoir2]" value="<?= htmlspecialchars((string)$d2) ?>" data-field="devoir2">
                </td>
                <td>
                  <input class="grade-input comp" type="number" min="0" max="20" step="0.5" 
                         name="notes[<?= $inscId ?>][composition]" value="<?= htmlspecialchars((string)$comp) ?>" data-field="composition">
                </td>
                <td>
                  <span class="moyenne-val"><?= number_format($moy, 2, '.', '') ?></span>
                </td>
                <td>
                  <span class="pill <?= $app['cls'] ?>">
                    <span class="pdot"></span>
                    <span class="app-label"><?= htmlspecialchars($app['label']) ?></span>
                  </span>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr><td colspan="6">Navigation clavier disponible · valeurs limitées de 0 à 20</td></tr>
        </tfoot>
      </table>
    </div>
  </form>

</div>

<script>
(function() {
  const notesForm = document.getElementById('notesForm');
  const classAvgEl = document.getElementById('classAvg');
  if (!notesForm) return;

  function appreciationFor(avg) {
    if (avg >= 16) return { label: 'Très bien', cls: '' };
    if (avg >= 14) return { label: 'Bien', cls: '' };
    if (avg >= 12) return { label: 'Assez bien', cls: '' };
    if (avg >= 10) return { label: 'Passable', cls: 'mid' };
    return { label: 'Insuffisant', cls: 'low' };
  }

  function updateRow(tr) {
    const d1Input = tr.querySelector('input[data-field="devoir1"]');
    const d2Input = tr.querySelector('input[data-field="devoir2"]');
    const compInput = tr.querySelector('input[data-field="composition"]');
    const moySpan = tr.querySelector('.moyenne-val');
    const pillSpan = tr.querySelector('.pill');
    const appLabel = tr.querySelector('.app-label');

    if (!d1Input || !d2Input || !compInput || !moySpan) return 0;

    const d1 = parseFloat(d1Input.value) || 0;
    const d2 = parseFloat(d2Input.value) || 0;
    const comp = parseFloat(compInput.value) || 0;

    const avg = (d1 + d2 + 2 * comp) / 4.0;
    moySpan.textContent = avg.toFixed(2);

    if (pillSpan && appLabel) {
      const app = appreciationFor(avg);
      pillSpan.classList.remove('low', 'mid');
      if (app.cls) pillSpan.classList.add(app.cls);
      appLabel.textContent = app.label;
    }

    return avg;
  }

  function updateClassAvg() {
    const rows = notesForm.querySelectorAll('tbody tr');
    if (!rows.length) return;
    let total = 0;
    let count = 0;
    rows.forEach(tr => {
      if (tr.querySelector('.moyenne-val')) {
        const avg = updateRow(tr);
        if (avg > 0) {
          total += avg;
          count++;
        }
      }
    });
    if (classAvgEl) {
      const classAvg = count > 0 ? (total / count) : 0;
      classAvgEl.innerHTML = classAvg.toFixed(2) + '<span>/20</span>';
    }
  }

  notesForm.addEventListener('input', (e) => {
    if (!e.target.classList.contains('grade-input')) return;
    let val = parseFloat(e.target.value);
    if (isNaN(val)) val = 0;
    e.target.classList.toggle('invalid', e.target.value !== '' && (val < 0 || val > 20));
    updateClassAvg();
  });

  notesForm.addEventListener('keydown', (e) => {
    if (!e.target.classList.contains('grade-input')) return;
    const inputs = Array.from(notesForm.querySelectorAll('.grade-input'));
    const pos = inputs.indexOf(e.target);
    let next = -1;
    if (e.key === 'ArrowRight') next = pos + 1;
    if (e.key === 'ArrowLeft') next = pos - 1;
    if (e.key === 'ArrowDown') next = pos + 3;
    if (e.key === 'ArrowUp') next = pos - 3;
    if (next >= 0 && next < inputs.length) {
      e.preventDefault();
      inputs[next].focus();
      inputs[next].select();
    }
  });
})();
</script>

</body>
</html>

