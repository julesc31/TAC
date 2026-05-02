<?php
require_once __DIR__ . '/db.php';

$timeMin = $_GET['timeMin'] ?? '';
$timeMax = $_GET['timeMax'] ?? '';

if (!$timeMin || !$timeMax) {
    jsonOut(['error' => 'Paramètres timeMin et timeMax requis.'], 400);
}

$calId  = rawurlencode(GOOGLE_CALENDAR_ID);
$apiKey = GOOGLE_CALENDAR_API_KEY;
$url    = "https://www.googleapis.com/calendar/v3/calendars/{$calId}/events"
        . "?key=" . urlencode($apiKey)
        . "&timeMin=" . urlencode($timeMin)
        . "&timeMax=" . urlencode($timeMax)
        . "&singleEvents=true&orderBy=startTime&maxResults=500";

$ctx      = stream_context_create(['http' => ['timeout' => 10, 'ignore_errors' => true]]);
$response = file_get_contents($url, false, $ctx);

if ($response === false) {
    jsonOut(['error' => 'Impossible de contacter Google Calendar.'], 502);
}

header('Content-Type: application/json; charset=utf-8');
echo $response;
