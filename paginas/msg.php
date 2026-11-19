<?php 
    include_once("../PHP/auth.php");
    $pdo = ConnectDB();
    exigirLogin($pdo);

    if (!is_admin($pdo)){
        http_response_code(403);
        exit('Acesso negado.');
    }

    $mensagens = SELECT($pdo, "SELECT nomeUtilizador, mensagem, dataEnvio FROM contacto AS m INNER JOIN utilizador AS u ON m.codUtilizador = u.codUtilizador");
    print_r($mensagens);
?>