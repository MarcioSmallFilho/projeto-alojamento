<?php
// config.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json; charset=utf-8');

$icalUrl = 'https://raw.githubusercontent.com/NateScarlet/holiday-cn/master/2026.ics';

$cacheDir = __DIR__ . '/cache';
$cacheFile = $cacheDir . '/calendar_cache.json';
$cacheTime = 900;

if (!is_dir($cacheDir)) {
    mkdir($cacheDir, 0755, true);
}

// Data de hoje no formato YYYY-MM-DD
$hoje = date('Y-m-d');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $icalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

$icsData = curl_exec($ch);
curl_close($ch);

$events = [];

if ($icsData) {
    preg_match_all('/BEGIN:VEVENT(.*?)END:VEVENT/s', $icsData, $matches);

    foreach ($matches[1] as $eventStr) {
        preg_match('/DTSTART(?:;VALUE=DATE)?:(\d{8})/', $eventStr, $startMatch);
        preg_match('/DTEND(?:;VALUE=DATE)?:(\d{8})/', $eventStr, $endMatch);

        if (!empty($startMatch[1]) && !empty($endMatch[1])) {
            $start = substr($startMatch[1], 0, 4) . '-' . substr($startMatch[1], 4, 2) . '-' . substr($startMatch[1], 6, 2);
            $end   = substr($endMatch[1], 0, 4) . '-' . substr($endMatch[1], 4, 2) . '-' . substr($endMatch[1], 6, 2);

            // FILTRO: Adiciona apenas os eventos onde a data de término seja igual ou posterior a hoje
            if ($end > $hoje) {
                $events[] = [
                    'title'   => 'Ocupado',
                    'start'   => $start,
                    'end'     => $end,
                    'color'   => '#ff4d4d'
                ];
            }
        }
    }
}

echo json_encode($events);
?>