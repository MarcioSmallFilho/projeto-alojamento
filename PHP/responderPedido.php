<?php
require_once '../PHP/auth.php';
$pdo = ConnectDB();
exigirLogin($pdo);
if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


$estadoRecebido = $_GET['request'];
$Codigo = $_GET['cod'];
INSERT($pdo, "UPDATE reserva SET estadoReserva = '{$estadoRecebido}' WHERE codReserva = {$Codigo}; ");
header('Location: ../paginas/paginaadmin.php');
?>