<?php
// proxy-ical.php
header('Access-Control-Allow-Origin: *');
header('Content-Type: text/calendar; charset=utf-8');

// Subtitui pelo teu URL iCal real do Airbnb/Booking
$icalUrl = 'https://www.airbnb.pt/calendar/ical/12345678.ics?s=example_key';

$cacheFile = DIR . '/cache/calendar_cache.ics';
$cacheTime = 900; // Cache de 15 minutos (900 segundos)

// Cria a pasta de cache se não existir
if (!is_dir(DIR . '/cache')) {
    mkdir(DIR . '/cache', 0755, true);
}

// Servir a partir da cache se ainda for recente
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTime)) {
    echo file_get_contents($cacheFile);
    exit;
}

// Caso contrário, procura o ficheiro atualizado na plataforma
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $icalUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'OasisDuneCalendarSync/1.0');

$data = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode === 200 && !empty($data)) {
    file_put_contents($cacheFile, $data);
    echo $data;
} else {
    // Se falhar o pedido, devolve a cache antiga se existir
    if (file_exists($cacheFile)) {
        echo file_get_contents($cacheFile);
    } else {
        http_response_code(500);
        echo "Erro ao carregar o calendário.";
    }
}
?>