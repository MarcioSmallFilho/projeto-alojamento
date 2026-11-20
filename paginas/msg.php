<?php 
    include_once("../PHP/auth.php");
    $pdo = ConnectDB();
    exigirLogin($pdo);

    if (!is_admin($pdo)){
        http_response_code(403);
        exit('Acesso negado.');
    }

    $mensagens = SELECT($pdo, "SELECT ban, nomeUtilizador, mensagem, dataEnvio FROM contacto AS m INNER JOIN utilizador AS u ON m.codUtilizador = u.codUtilizador WHERE codAlojamento = :cod ORDER BY dataEnvio DESC", [':cod' => $codAlojamento]);
    echo '<div class="mensagens">';
    if(!empty($mensagens)){
        foreach($mensagens as $msg){
            if ($msg['ban'] == 1){
            echo "<div class='msg ban'>". $msg['nomeUtilizador'] .": ". $msg['mensagem'] . " (" . $msg['dataEnvio'] . ") </div>";
            }else{
            echo "<div class='msg'>". $msg['nomeUtilizador'] .": ". $msg['mensagem'] . " (" . $msg['dataEnvio'] . ") </div>"; }
        }
    }
    echo '</div>';
?>