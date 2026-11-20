<?php
require_once '../PHP/auth.php';
$pdo = ConnectDB();
exigirLogin($pdo);
if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


$estadoRecebido = $_GET['request'];
$Codigo = $_GET['cod'];
INSERT($pdo, "UPDATE utilizador SET ban = {$estadoRecebido} WHERE codUtilizador = {$Codigo}; ");

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