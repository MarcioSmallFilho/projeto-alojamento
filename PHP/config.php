
<?php
require_once 'C:/xampp/database.php';
header('Content-Type: application/json; charset=utf-8');

// ==========================================
// 1. LIGAÇÃO À BASE DE DADOS
// ==========================================

try {

   $pdo = ConnectDB();

    // ==========================================
    // 2. CONSULTAR AS RESERVAS
    // ==========================================

    $hoje = date('Y-m-d');

    $sql = "
        SELECT
            codUtilizador,
            codAlojamento,
            checkIn,
            checkOut,
            dataReserva,
            precoTotal
        FROM Reserva
        WHERE checkOut > :hoje and estadoReserva = 'Aceite'
        ORDER BY checkIn ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['hoje' => $hoje]);

    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ==========================================
    // 3. CONVERTER PARA JSON DO FULLCALENDAR
    // ==========================================

    $events = [];

    foreach ($reservas as $reserva) {

        $events[] = [
            'id' => $reserva['codAlojamento'] . '_' .
                    $reserva['checkIn'] . '_' .
                    $reserva['checkOut'],

            'title' => 'Ocupado',

            'start' => $reserva['checkIn'],

            'end' => $reserva['checkOut'],

            'allDay' => true,

            'color' => '#ff4d4d',

            'extendedProps' => [
                'codUtilizador' => $reserva['codUtilizador'],
                'codAlojamento' => $reserva['codAlojamento'],
                'dataReserva' => $reserva['dataReserva'],
                'precoTotal' => $reserva['precoTotal']
            ]
        ];
    }

    // ==========================================
    // 4. DEVOLVER JSON
    // ==========================================

    echo json_encode($events, JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {

    http_response_code(500);

    echo json_encode([
        'erro' => 'Erro ao consultar a base de dados.'
    ]);

}
?>
