<?php 
    include_once("../PHP/auth.php");
    $pdo = ConnectDB();
    exigirLogin($pdo);

    if (!is_admin($pdo)){
        http_response_code(403);
        exit('Acesso negado.');
    }
?>
<form action="" method="post" id="filtro">
        <input type="hidden" name="form_id" value="filtro">
        <Label>Banido:</Label>
        <input type="checkbox" name="ban" id="" value="on" <?php echo isset($_POST['ban']) ? 'checked' : ''; ?> >
        <input type="number" name="nReservas_msg" min="1" value="<?php echo htmlspecialchars($_POST['nReservas_msg'] ?? '5', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" value="Filtrar">
    </form>
<?php
    $limite = 5; // valor por defeito
      if (isset($_POST['nReservas_msg']) && is_numeric($_POST['nReservas_msg']) && (int)$_POST['nReservas_msg'] > 0) {
        $limite = (int)$_POST['nReservas_msg'];
      }

    $ban = 0; // valor por defeito
      if (isset($_POST['form_id']) && $_POST['form_id'] == 'filtro' && isset($_POST['ban']) && $_POST['ban'] == 'on') {
        $ban = 'on';
      }
    $mensagens = SELECT($pdo, "SELECT ban, nomeUtilizador, mensagem, dataEnvio FROM contacto AS m INNER JOIN utilizador AS u ON m.codUtilizador = u.codUtilizador WHERE codAlojamento = :cod AND (ban = :banlim OR :banlim = 'on' ) ORDER BY dataEnvio DESC LIMIT $limite", [':cod' => $codAlojamento, ':banlim' => $ban]);
    echo '<div class="mensagens">';
    if(!empty($mensagens)){
        foreach($mensagens as $msg){
            if ($msg['ban'] == 1){
            echo "<div class='msg ban'>(" . $msg['dataEnvio'] . ") ". $msg['nomeUtilizador'] .": ". $msg['mensagem'] . "</div>";
            }else{
            echo "<div class='msg'>(" . $msg['dataEnvio'] . ") ". $msg['nomeUtilizador'] .": ". $msg['mensagem'] . "</div>"; }
        }
    }else{
        echo "<div class='msg'>Nenhuma mensagem...</div>";
    }
    echo '</div>';
?>