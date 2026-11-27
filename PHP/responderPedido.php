<?php
require_once '../PHP/auth.php';
require_once '../enviar.php';
$pdo = ConnectDB();
exigirLogin($pdo);
if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


$estadoRecebido = $_GET['request'];
$Codigo = $_GET['cod'];

if(isset($_GET['from'])){$deQuem = $_GET['from'];}

$Sobreposto = false;

if(isset($deQuem)){
    $reservasParaCancelar = SELECT($pdo, "SELECT * FROM reserva WHERE codUtilizador = $deQuem AND estadoReserva = 'Aceite' ");
    foreach($reservasParaCancelar as $reserva){
        INSERT($pdo, "UPDATE reserva SET estadoReserva = '{$estadoRecebido}' WHERE codReserva = :cod ; ", [':cod' => $reserva['codReserva']]);
    }
}else{
    if($estadoRecebido == 'Aceite'){
        $reserva = SELECT($pdo, 'SELECT * FROM reserva where codReserva = :cod', [':cod' => $Codigo])[0];

        $reservasAceites = SELECT($pdo, 'SELECT checkIn, checkOut FROM reserva WHERE (estadoReserva = "Aceite" OR estadoReserva = "Bloqueado")  AND codAlojamento = '.$codAlojamento.'');  

        foreach($reservasAceites as $reservaAceite){
            // Condição correta de sobreposição de datas em alojamentos
            if ($reserva['checkIn'] < $reservaAceite['checkOut'] && $reserva['checkOut'] > $reservaAceite['checkIn']) {
                $Sobreposto = true;
                break;
            }
        }

        if(!$Sobreposto){
            INSERT($pdo, "UPDATE reserva SET estadoReserva = '{$estadoRecebido}' WHERE codReserva = {$Codigo}; ");
            $reserva = SELECT($pdo, 'SELECT emailUtilizador,estadoReserva,checkIn,checkOut FROM reserva AS r INNER JOIN utilizador AS u ON u.codUtilizador = r.codUtilizador where codReserva = :cod', [':cod' => $Codigo])[0];
            enviarEmail(['smallmarcio48@gmail.com','Equipe Adm'], $reserva['emailUtilizador'], 'Atualização de status da sua reserva', "<h1>A sua reserva foi Aceite</h1><p>A sua reserva ". $reserva['checkIn'] . " | " . $reserva['checkOut'] . " Está:\t<b>" . $reserva['estadoReserva'] . "</b></p><p>Tenha uma otima estadia.</p>");

        }else{
            $_SESSION['erro'] = 'A reserva estava bloqueada';
        }
    }elseif($estadoRecebido == 'Cancelada'){
        INSERT($pdo, "DELETE FROM reserva WHERE codReserva = {$Codigo}; ");
    }else{
        INSERT($pdo, "UPDATE reserva SET estadoReserva = '{$estadoRecebido}' WHERE codReserva = {$Codigo}; ");
        $reserva = SELECT($pdo, 'SELECT emailUtilizador,estadoReserva,checkIn,checkOut FROM reserva AS r INNER JOIN utilizador AS u ON u.codUtilizador = r.codUtilizador where codReserva = :cod', [':cod' => $Codigo])[0];
        if ($estadoRecebido == 'Recusada') {
            $mensagem = "<h1>A sua reserva foi recusada</h1><p>A sua reserva dos dias ". $reserva['checkIn'] . " | " . $reserva['checkOut'] . "</p><p>Está:\t<b>" . $reserva['estadoReserva'] . "</b></p>";
        }else {
            $mensagem = "<h1>O estado da sua reserva mudou</h1><p>A sua reserva dos dias ". $reserva['checkIn'] . " | " . $reserva['checkOut'] . "</p><p>Está:\t<b>" . $reserva['estadoReserva'] . "</b> Aguarde um Adiministrador avaliar a sua reserva</p>";
        }

        enviarEmail(['smallmarcio48@gmail.com','Equipe Adm'], $reserva['emailUtilizador'], 'Atualização de status da sua reserva', $mensagem);
    }
}

if (isset($_SERVER['HTTP_REFERER'])) {
    $urlAnterior = $_SERVER['HTTP_REFERER'];
    header("Location: $urlAnterior");
    exit();
} else {
    if(isset($erro)){
        header("Location: ../paginas/paginaadmin.php?");
        }
    exit();
}

?>