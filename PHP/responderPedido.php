<?php
require_once '../PHP/auth.php';
$pdo = ConnectDB();
exigirLogin($pdo);
if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


$estadoRecebido = $_GET['request'];
$Codigo = $_GET['cod'];
$Sobreposto = false;

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
    }else{
        $_SESSION['erro'] = 'A reserva estava bloqueada';
    }
}else{
    INSERT($pdo, "UPDATE reserva SET estadoReserva = '{$estadoRecebido}' WHERE codReserva = {$Codigo}; ");
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