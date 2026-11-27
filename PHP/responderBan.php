<?php
require_once '../PHP/auth.php';
$pdo = ConnectDB();
exigirLogin($pdo);
if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


$estadoRecebido = $_GET['request'];
$Codigo = $_GET['cod'];

$reservas = SELECT($pdo, "SELECT * FROM reserva WHERE estadoReserva = 'Aceite' AND codUtilizador = $Codigo");
//Caso o utilizador esteja a ser banido e houver reservas ativas dele.
if($reservas and $estadoRecebido){
    $_SESSION['aviso'] = 'O utilizador banido continha reservas ativas, deseja cancelar as reservas? '."<a href='../PHP/responderPedido.php?request=Recusada&from=$Codigo'>Cancelar</a>".' / '."<a href='../PHP/responderBan.php?request=0&cod=$Codigo'>Desbanir</a>";
    INSERT($pdo, "UPDATE utilizador SET ban = {$estadoRecebido} WHERE codUtilizador = $Codigo; ");
}else{
    INSERT($pdo, "UPDATE utilizador SET ban = {$estadoRecebido} WHERE codUtilizador = $Codigo; ");
}

if (isset($_SERVER['HTTP_REFERER'])) {
    $urlAnterior = $_SERVER['HTTP_REFERER'];
    header("Location: $urlAnterior");
    exit();
} else {
    // Fallback (plano B) caso não haja histórico, redireciona para uma página padrão
    header('Location: ../paginas/paginaadmin.php');
    exit();
    
}

?>