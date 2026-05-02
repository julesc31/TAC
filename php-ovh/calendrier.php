<?php
require_once __DIR__ . '/inc/config.php';
require_once __DIR__ . '/inc/functions.php';
session_name(ADMIN_SESSION_NAME); session_start();

$pageTitle = 'Calendrier — Arc Club Pechbonnieu';

// ─── Fetch Google Calendar ────────────────────────────────────────────────
function fetchGoogleEvents(): array {
    $calId  = urlencode(GOOGLE_CALENDAR_ID);
    $apiKey = GOOGLE_API_KEY;
    $tMin   = urlencode(date('c', mktime(0,0,0,1,1,date('Y')-1)));
    $tMax   = urlencode(date('c', mktime(0,0,0,12,31,date('Y')+2)));
    $url    = "https://www.googleapis.com/calendar/v3/calendars/{$calId}/events?key={$apiKey}&timeMin={$tMin}&timeMax={$tMax}&singleEvents=true&orderBy=startTime&maxResults=250";

    $ctx = stream_context_create(['http' => ['timeout' => 8, 'method' => 'GET', 'header' => 'Accept: application/json']]);
    $res = @file_get_contents($url, false, $ctx);
    if (!$res) return [];
    $data = json_decode($res, true);
    return $data['items'] ?? [];
}

function guessType(string $title, bool $allDay = true): string {
    $t = strtolower(iconv('UTF-8','ASCII//TRANSLIT', $title));
    if (str_contains($t,'morata')||str_contains($t,'3d')||str_contains($t,'parcours')||str_contains($t,'campagne')) return '3d';
    if (str_contains($t,'18m')||str_contains($t,'championnat')||str_contains($t,'concours')||str_contains($t,'competition')||
        str_contains($t,'beursault')||str_contains($t,'bouquet')||str_contains($t,'monpitol')||str_contains($t,'ffta')||
        str_contains($t,'ranking')) return 'competition';
    if (str_contains($t,'stage')) return 'stage';
    if (str_contains($t,'ag ')||str_contains($t,'assemblee')||str_contains($t,'reunion')||str_contains($t,'galette')||
        str_contains($t,'forum')||str_contains($t,'vacances')||str_contains($t,'noel')||str_contains($t,'interne')) return 'interne';
    if (str_contains($t,'entrainement')||str_contains($t,'reprise')||str_contains($t,'outdoor')||str_contains($t,'indoor')||
        str_contains($t,'seance')) return 'entrainement';
    if (!$allDay) return 'entrainement';
    return 'autre';
}

$typeConfig = [
    'competition'  => ['label' => 'Compétition',  'bg' => '#ef4444', 'text' => '#fff'],
    'entrainement' => ['label' => 'Entraînement', 'bg' => '#1a4a7c', 'text' => '#fff'],
    '3d'           => ['label' => '3D',           'bg' => '#10b981', 'text' => '#fff'],
    'interne'      => ['label' => 'Interne',      'bg' => '#ffd700', 'text' => '#0a2744'],
    'stage'        => ['label' => 'Stage',        'bg' => '#0ea5e9', 'text' => '#fff'],
    'autre'        => ['label' => 'Autre',        'bg' => '#78716c', 'text' => '#fff'],
];

$rawItems = fetchGoogleEvents();
$events   = [];
foreach ($rawItems as $item) {
    $summary = $item['summary'] ?? 'Événement';
    if (stripos($summary, '[privé]') === 0) continue;
    $allDay = isset($item['start']['date']);
    $start  = $item['start']['date'] ?? substr($item['start']['dateTime'] ?? '', 0, 10);
    $end    = $item['end']['date']   ?? substr($item['end']['dateTime'] ?? '', 0, 10) ?: null;
    if ($end === $start) $end = null;
    $type = guessType($summary, $allDay);
    $events[] = [
        'id'       => $item['id'] ?? uniqid(),
        'date'     => $start,
        'dateEnd'  => $end,
        'title'    => $summary,
        'location' => $item['location'] ?? 'Pechbonnieu',
        'type'     => $type,
        'typeLabel'=> $typeConfig[$type]['label'],
        'desc'     => $item['description'] ?? null,
        'allDay'   => $allDay,
        'dateFR'   => $start ? (new DateTime($start))->format('l d F Y') : '',
    ];
}

// Calendar grid
$months_fr = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
$days_fr   = ['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'];
$today     = new DateTime();
$todayStr  = $today->format('Y-m-d');

$viewYear  = intval($_GET['y'] ?? $today->format('Y'));
$viewMonth = intval($_GET['m'] ?? $today->format('n')) - 1; // 0-indexed
if ($viewMonth < 0)  { $viewMonth = 11; $viewYear--; }
if ($viewMonth > 11) { $viewMonth = 0;  $viewYear++; }

$firstDay   = (new DateTime("{$viewYear}-" . sprintf('%02d', $viewMonth+1) . "-01"))->format('N') - 1; // 0=Mon
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $viewMonth+1, $viewYear);
$prevM = $viewMonth === 0  ? ['y' => $viewYear-1, 'm' => 12] : ['y' => $viewYear, 'm' => $viewMonth];
$nextM = $viewMonth === 11 ? ['y' => $viewYear+1, 'm' => 1]  : ['y' => $viewYear, 'm' => $viewMonth+2];

function eventsForDay(array $events, string $dateStr): array {
    return array_filter($events, function($e) use ($dateStr) {
        if (!$e['dateEnd'] || $e['date'] === $e['dateEnd']) return $e['date'] === $dateStr;
        return $dateStr >= $e['date'] && $dateStr < $e['dateEnd'];
    });
}

// Upcoming (next 5)
usort($events, fn($a,$b) => strcmp($a['date'], $b['date']));
$upcoming = array_slice(array_filter($events, fn($e) => $e['date'] >= $todayStr), 0, 5);

include __DIR__ . '/inc/header.php';
?>

<main class="pt-16">
  <!-- Hero -->
  <div class="page-hero">
    <div class="page-hero-bg" style="background-image:url('https://images.pexels.com/photos/3765179/pexels-photo-3765179.jpeg?auto=compress&cs=tinysrgb&w=1600')"></div>
    <div class="page-hero-content">
      <h1>Calendrier</h1>
      <p>Concours, compétitions, sorties 3D et événements du club</p>
    </div>
  </div>

  <div class="container-md" style="padding-bottom:5rem">

    <!-- Légende -->
    <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin:1.5rem 0">
      <?php foreach ($typeConfig as $key => $cfg): if ($key === 'autre') continue; ?>
        <span style="display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:700;padding:.25rem .75rem;border-radius:9999px;background:<?= $cfg['bg'] ?>;color:<?= $cfg['text'] ?>"><?= h($cfg['label']) ?></span>
      <?php endforeach; ?>
    </div>

    <div style="display:grid;grid-template-columns:1fr minmax(0,280px);gap:1.5rem">

      <!-- Calendrier -->
      <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:1.25rem;overflow:hidden">
        <!-- Header mois -->
        <div class="cal-header">
          <a href="?y=<?= $prevM['y'] ?>&m=<?= $prevM['m'] ?>" class="cal-nav">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
          </a>
          <div style="display:flex;align-items:center;gap:1rem">
            <h2 class="cal-title"><?= $months_fr[$viewMonth] ?> <?= $viewYear ?></h2>
            <a href="?y=<?= $today->format('Y') ?>&m=<?= $today->format('n') ?>" style="font-size:.75rem;font-weight:700;background:var(--gold);color:var(--navy);padding:.25rem .75rem;border-radius:9999px">Aujourd'hui</a>
          </div>
          <a href="?y=<?= $nextM['y'] ?>&m=<?= $nextM['m'] ?>" class="cal-nav">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          </a>
        </div>

        <!-- Jours -->
        <div class="cal-grid" style="border-bottom:1px solid var(--white10)">
          <?php foreach ($days_fr as $i => $d): ?>
            <div class="cal-day-label <?= $i >= 5 ? 'weekend' : '' ?>"><?= $d ?></div>
          <?php endforeach; ?>
        </div>

        <!-- Grille -->
        <div class="cal-grid" style="border-top:1px solid var(--white10);border-left:1px solid var(--white10)">
          <?php for ($i = 0; $i < $firstDay; $i++): ?>
            <div class="cal-cell empty"></div>
          <?php endfor; ?>

          <?php for ($day = 1; $day <= $daysInMonth; $day++):
            $dateStr  = sprintf('%04d-%02d-%02d', $viewYear, $viewMonth+1, $day);
            $dayEvents = array_values(eventsForDay($events, $dateStr));
            $isToday   = $dateStr === $todayStr;
            $colIdx    = ($firstDay + $day - 1) % 7;
            $isWeekend = $colIdx >= 5;
          ?>
            <div class="cal-cell <?= $isWeekend ? 'weekend' : '' ?>">
              <div class="cal-num <?= $isToday ? 'today' : ($isWeekend ? 'weekend' : '') ?>"><?= $day ?></div>
              <?php foreach (array_slice($dayEvents, 0, 3) as $ev):
                $cfg = $typeConfig[$ev['type']]; ?>
                <button class="cal-event ev-<?= $ev['type'] ?>" data-event-id="<?= h($ev['id']) ?>"
                        style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['text'] ?>">
                  <?= h($ev['title']) ?>
                </button>
              <?php endforeach; ?>
              <?php if (count($dayEvents) > 3): ?>
                <span style="font-size:.65rem;color:rgba(255,255,255,.4);padding:0 .25rem">+<?= count($dayEvents) - 3 ?></span>
              <?php endif; ?>
            </div>
          <?php endfor; ?>

          <?php
          $totalCells = $firstDay + $daysInMonth;
          $remainder  = $totalCells % 7;
          if ($remainder !== 0) for ($i = 0; $i < 7 - $remainder; $i++): ?>
            <div class="cal-cell empty"></div>
          <?php endfor; ?>
        </div>
      </div>

      <!-- Sidebar -->
      <div style="display:flex;flex-direction:column;gap:1rem">

        <!-- Prochains événements -->
        <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:1.25rem;padding:1.25rem">
          <h3 style="color:var(--gold);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:1rem">Prochains événements</h3>
          <?php if (empty($upcoming)): ?>
            <p style="color:rgba(255,255,255,.4);font-size:.875rem">Aucun événement à venir.</p>
          <?php else: ?>
            <?php foreach ($upcoming as $ev):
              $d   = new DateTime($ev['date']);
              $cfg = $typeConfig[$ev['type']];
            ?>
              <button class="upcoming-event" data-event-id="<?= h($ev['id']) ?>">
                <div class="upcoming-date-box">
                  <div class="upcoming-month"><?= mb_substr($months_fr[intval($d->format('n'))-1], 0, 3) ?></div>
                  <div class="upcoming-day" style="background:<?= $cfg['bg'] ?>;color:<?= $cfg['text'] ?>"><?= $d->format('j') ?></div>
                </div>
                <div>
                  <div class="upcoming-title"><?= h($ev['title']) ?></div>
                  <div class="upcoming-loc">
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <?= h($ev['location']) ?>
                  </div>
                </div>
              </button>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Légende sidebar -->
        <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:1.25rem;padding:1.25rem">
          <h3 style="color:var(--gold);font-weight:700;font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;margin-bottom:.75rem">Légende</h3>
          <?php foreach ($typeConfig as $key => $cfg): if ($key === 'autre') continue; ?>
            <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
              <span style="width:.75rem;height:.75rem;border-radius:.25rem;background:<?= $cfg['bg'] ?>;flex-shrink:0"></span>
              <span style="color:rgba(255,255,255,.65);font-size:.75rem"><?= h($cfg['label']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>

        <div style="background:rgba(255,215,0,.1);border:1px solid rgba(255,215,0,.2);border-radius:1.25rem;padding:1.25rem">
          <p style="color:rgba(255,255,255,.5);font-size:.75rem;line-height:1.6">Les dates sont synchronisées depuis l'agenda officiel du club. Consultez les annonces en club pour les informations les plus récentes.</p>
        </div>

      </div>
    </div>
  </div>

  <!-- Modal événement -->
  <div class="modal-overlay" id="modal-overlay">
    <div class="modal">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1rem">
        <div>
          <span class="modal-type" id="modal-type-badge"></span>
          <h3 class="modal-title" id="modal-event-title"></h3>
        </div>
        <button class="modal-close-btn" id="modal-close">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <p class="modal-body" id="modal-event-desc"></p>
      <div class="modal-meta">
        <div><strong>Date :</strong> <span id="modal-event-date"></span></div>
        <div style="display:flex;align-items:center;gap:.375rem">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <span id="modal-event-loc"></span>
        </div>
      </div>
    </div>
  </div>

</main>

<script>
// Pass events data to JS for modal
const calEvents = <?= json_encode(array_values($events), JSON_UNESCAPED_UNICODE) ?>;
document.addEventListener('DOMContentLoaded', () => initCalendar(calEvents));
</script>
<style>
@media(max-width:900px){
  .container-md > div[style*="grid-template-columns:1fr minmax"]{grid-template-columns:1fr!important}
}
</style>

<?php include __DIR__ . '/inc/footer.php'; ?>
