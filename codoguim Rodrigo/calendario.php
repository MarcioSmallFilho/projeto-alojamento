<?php
header('Content-Type: application/json; charset=utf-8');
$icsUrl = 'https://raw.githubusercontent.com/NateScarlet/holiday-cn/master/2026.ics';
// Vai buscar o ficheiro ICS
$ics = file_get_contents($icsUrl);
if ($ics === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Não foi possível obter o ficheiro ICS'
    ]);
    exit;
}
$today = new DateTime('today');
$events = [];
// Procura todos os VEVENT
preg_match_all(
    '/BEGIN:VEVENT(.*?)END:VEVENT/s',
    $ics,
    $matches
);
foreach ($matches[1] as $event) {
    // Procurar DTSTART
    preg_match(
        '/DTSTART(?:;[^:]*)?:(\d{8})/',
        $event,
        $start
    );
    // Procurar DTEND
    preg_match(
        '/DTEND(?:;[^:]*)?:(\d{8})/',
        $event,
        $end
    );
    // Se não tiver data inicial, ignorar
    if (!isset($start[1])) {
        continue;
    }
    // Converter 20261001 para 2026-10-01
    $startDate = DateTime::createFromFormat(
        'Ymd',
        $start[1]
    );
    // Converter a data final
    if (isset($end[1])) {
        $endDate = DateTime::createFromFormat(
            'Ymd',
            $end[1]
        );
    } else {
        $endDate = clone $startDate;
        $endDate->modify('+1 day');
    }
    // Ignora eventos que já terminaram
    if ($endDate >= $today) {
        $events[] = [
            'title' => 'Reservado',
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
            'color' => '#e74c3c',
            'allDay' => true
    ];
    }
}

echo json_encode($events);
