<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Résultats — Arc Club Pechbonnieu';
$pageDesc  = 'Palmarès des archers de l\'Arc Club Pechbonnieu en compétition.';

// Charger toutes les compétitions
$competitions = [];
try {
    $competitions = db()->query("SELECT * FROM competitions ORDER BY date DESC")->fetchAll();
} catch (Exception $e) {}

// Charger tous les résultats d'un coup et les indexer par competition_id
$resultats = [];
if (!empty($competitions)) {
    try {
        $rows = db()->query("SELECT * FROM resultats ORDER BY competition_id, place ASC, nom ASC")->fetchAll();
        foreach ($rows as $r) {
            $resultats[$r['competition_id']][] = $r;
        }
    } catch (Exception $e) {}
}

// Années disponibles pour le filtre
$years = [];
foreach ($competitions as $c) {
    $y = substr($c['date'], 0, 4);
    if (!in_array($y, $years)) $years[] = $y;
}
rsort($years);

$filterYear = $_GET['annee'] ?? '';

$TYPE_COLORS = [
    'Tir 18m en salle'    => 'badge-sky',
    'Beursault'           => 'badge-emerald',
    'Bouquet provincial'  => 'badge-purple',
    'TAE'                 => 'badge-orange',
    'Nature'              => 'badge-green',
    'Campagne'            => 'badge-amber',
    '3D'                  => 'badge-red',
];

function typeBadgeClass(string $type, array $map): string {
    foreach ($map as $key => $cls) {
        if (stripos($type, $key) !== false) return $cls;
    }
    return 'badge-default';
}

include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">

  <!-- Hero -->
  <div style="background:linear-gradient(to bottom,var(--navy),#1a3a5c);padding:5rem 0 4rem;text-align:center;border-bottom:1px solid var(--white10)">
    <div class="container-sm">
      <div class="inline-badge">Arc Club Pechbonnieu</div>
      <h1 style="font-size:clamp(2rem,5vw,3.5rem);font-weight:800;color:#fff;margin-bottom:1rem">
        Nos <span style="color:var(--gold)">Résultats</span>
      </h1>
      <p style="color:var(--stone2);font-size:1.05rem;max-width:34rem;margin:0 auto">
        Palmarès de nos archers en compétition.
      </p>
    </div>
  </div>

  <!-- Contenu -->
  <div style="padding:3rem 0 5rem">
    <div class="container-sm">

      <?php if (count($years) > 1): ?>
      <!-- Filtre par année -->
      <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-bottom:2rem">
        <a href="/resultats.php"
           style="padding:.375rem 1rem;border-radius:9999px;font-size:.85rem;font-weight:500;transition:all .2s;
                  <?= $filterYear === '' ? 'background:var(--gold);color:var(--navy)' : 'background:var(--white10);color:var(--stone2)' ?>">
          Toutes
        </a>
        <?php foreach ($years as $y): ?>
        <a href="/resultats.php?annee=<?= h($y) ?>"
           style="padding:.375rem 1rem;border-radius:9999px;font-size:.85rem;font-weight:500;transition:all .2s;
                  <?= $filterYear === $y ? 'background:var(--gold);color:var(--navy)' : 'background:var(--white10);color:var(--stone2)' ?>">
          <?= h($y) ?>
        </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <?php
      $displayed = array_filter($competitions, function($c) use ($filterYear) {
          return $filterYear === '' || substr($c['date'], 0, 4) === $filterYear;
      });
      ?>

      <?php if (empty($displayed)): ?>
        <div style="text-align:center;padding:5rem 0">
          <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--stone5)" stroke-width="1.5" style="margin:0 auto 1.5rem">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/>
            <path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22"/>
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22"/>
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2z"/>
          </svg>
          <p style="color:var(--stone4);font-size:1.05rem">Aucun résultat disponible pour le moment.</p>
          <p style="color:var(--stone5);font-size:.875rem;margin-top:.5rem">Les résultats seront publiés après chaque compétition.</p>
        </div>

      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:1.25rem">
          <?php foreach ($displayed as $comp):
            $compResultats = $resultats[$comp['id']] ?? [];
            $badgeClass = typeBadgeClass($comp['type_competition'], $TYPE_COLORS);
          ?>
          <details class="result-card" <?= isset($_GET['open']) && $_GET['open'] == $comp['id'] ? 'open' : '' ?>>
            <summary style="cursor:pointer;list-style:none;padding:1.25rem 1.5rem;display:flex;align-items:flex-start;justify-content:space-between;gap:1rem">
              <div style="flex:1;min-width:0">
                <div style="display:flex;flex-wrap:wrap;align-items:center;gap:.5rem;margin-bottom:.6rem">
                  <span class="type-badge <?= $badgeClass ?>"><?= h($comp['type_competition']) ?></span>
                </div>
                <h3 style="color:#fff;font-weight:700;font-size:1rem;margin-bottom:.6rem;line-height:1.4"><?= h($comp['titre']) ?></h3>
                <div style="display:flex;flex-wrap:wrap;gap:1.25rem;color:var(--stone4);font-size:.82rem">
                  <span style="display:flex;align-items:center;gap:.375rem">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?= h(formatDateFR($comp['date'])) ?>
                  </span>
                  <?php if ($comp['lieu']): ?>
                  <span style="display:flex;align-items:center;gap:.375rem">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?= h($comp['lieu']) ?>
                  </span>
                  <?php endif; ?>
                  <?php if (count($compResultats) > 0): ?>
                  <span style="display:flex;align-items:center;gap:.375rem">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="var(--gold)" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <?= count($compResultats) ?> archer<?= count($compResultats) > 1 ? 's' : '' ?>
                  </span>
                  <?php endif; ?>
                </div>
              </div>
              <div style="display:flex;align-items:center;gap:.75rem;flex-shrink:0;margin-top:.25rem">
                <?php if ($comp['lien_officiel']): ?>
                <a href="<?= h($comp['lien_officiel']) ?>" target="_blank" rel="noopener noreferrer"
                   onclick="event.stopPropagation()"
                   style="display:inline-flex;align-items:center;gap:.3rem;font-size:.75rem;color:var(--gold);background:rgba(255,215,0,.1);border:1px solid rgba(255,215,0,.2);padding:.3rem .75rem;border-radius:9999px;white-space:nowrap">
                  <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  Officiel
                </a>
                <?php endif; ?>
                <svg class="chevron-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--stone5)" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
              </div>
            </summary>

            <div style="border-top:1px solid var(--white10);padding:1.25rem 1.5rem">
              <?php if (empty($compResultats)): ?>
                <p style="color:var(--stone5);font-size:.875rem;text-align:center;padding:.75rem 0">Aucun résultat enregistré pour cette compétition.</p>
              <?php else: ?>
                <div style="overflow-x:auto">
                  <table style="width:100%;font-size:.875rem;border-collapse:collapse">
                    <thead>
                      <tr style="border-bottom:1px solid var(--white10)">
                        <th style="text-align:left;padding:.6rem .75rem;color:var(--stone4);font-weight:500;font-size:.78rem;white-space:nowrap">Place</th>
                        <th style="text-align:left;padding:.6rem .75rem;color:var(--stone4);font-weight:500;font-size:.78rem">Nom</th>
                        <th style="text-align:left;padding:.6rem .75rem;color:var(--stone4);font-weight:500;font-size:.78rem">Catégorie</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($compResultats as $i => $r): ?>
                      <tr style="border-bottom:1px solid rgba(255,255,255,.04);<?= $i % 2 !== 0 ? 'background:rgba(255,255,255,.02)' : '' ?>">
                        <td style="padding:.65rem .75rem;font-weight:700;color:var(--gold);white-space:nowrap">
                          <?php
                          $p = $r['place'];
                          if ($p === null || $p === '') echo '—';
                          elseif ($p == 1) echo '🥇 1<sup>er</sup>';
                          elseif ($p == 2) echo '🥈 2<sup>e</sup>';
                          elseif ($p == 3) echo '🥉 3<sup>e</sup>';
                          else echo h($p) . '<sup>e</sup>';
                          ?>
                        </td>
                        <td style="padding:.65rem .75rem;color:#fff"><?= h($r['nom']) ?></td>
                        <td style="padding:.65rem .75rem;color:var(--stone4)"><?= h($r['categorie']) ?: '—' ?></td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              <?php endif; ?>
            </div>
          </details>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

    </div>
  </div>
</main>

<style>
.inline-badge{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,215,0,.15);border:1px solid rgba(255,215,0,.3);border-radius:9999px;padding:.35rem 1rem;margin-bottom:1.5rem;color:var(--gold);font-size:.82rem;font-weight:500}
.result-card{background:var(--white10);border:1px solid rgba(255,255,255,.1);border-radius:1rem;overflow:hidden;transition:border-color .2s}
.result-card:hover{border-color:rgba(255,255,255,.2)}
.result-card[open]{border-color:rgba(255,215,0,.25)}
.result-card summary::-webkit-details-marker{display:none}
.result-card[open] .chevron-icon{transform:rotate(180deg)}
.chevron-icon{transition:transform .2s}
.type-badge{display:inline-block;font-size:.7rem;font-weight:700;padding:.2rem .65rem;border-radius:9999px;border:1px solid}
.badge-sky{background:rgba(14,165,233,.15);color:#7dd3fc;border-color:rgba(14,165,233,.3)}
.badge-emerald{background:rgba(16,185,129,.15);color:#6ee7b7;border-color:rgba(16,185,129,.3)}
.badge-purple{background:rgba(139,92,246,.15);color:#c4b5fd;border-color:rgba(139,92,246,.3)}
.badge-orange{background:rgba(249,115,22,.15);color:#fdba74;border-color:rgba(249,115,22,.3)}
.badge-green{background:rgba(34,197,94,.15);color:#86efac;border-color:rgba(34,197,94,.3)}
.badge-amber{background:rgba(245,158,11,.15);color:#fde68a;border-color:rgba(245,158,11,.3)}
.badge-red{background:rgba(239,68,68,.15);color:#fca5a5;border-color:rgba(239,68,68,.3)}
.badge-default{background:var(--white10);color:var(--stone2);border-color:rgba(255,255,255,.2)}
</style>

<?php include __DIR__ . '/inc/footer.php'; ?>
