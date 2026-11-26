<?php 
    include_once("../PHP/auth.php");
    $pdo = ConnectDB();
    if (realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
        exigirLogin($pdo);

        if (!is_admin($pdo)){
            http_response_code(403);
            exit('Acesso negado.');
        }
    }
?> 
<form action="" method="post" id="filtro">
        <input type="hidden" name="form_id" value="filtro">
        <Label>Banido:</Label>
        <input type="checkbox" name="ban" id="" value="on" <?php echo isset($_POST['ban']) ? 'checked' : ''; ?> >
        <input type="number" name="nReservas_user" min="1" value="<?php echo htmlspecialchars($_POST['nReservas_user'] ?? '5', ENT_QUOTES, 'UTF-8'); ?>">
        <input type="submit" value="Filtrar">
    </form>
<?php
    $limite = 5; // valor por defeito
      if (isset($_POST['nReservas_user']) && is_numeric($_POST['nReservas_user']) && (int)$_POST['nReservas_user'] > 0) {
        $limite = (int)$_POST['nReservas_user'];
      }

    $ban = 0; // valor por defeito
      if (isset($_POST['form_id']) && $_POST['form_id'] == 'filtro' && isset($_POST['ban']) && $_POST['ban'] == 'on') {
        $ban = 'on';
      } 
    $users = SELECT($pdo, "SELECT * FROM utilizador ORDER BY dataCriacao DESC LIMIT $limite", []);
    echo '<div class="utilizadores">';
    if(isset($users) and !empty($users)){
        foreach($users as $user){
            if ($user['ban'] == 1){
            echo "<div class='msg ban'>(" . $user['dataCriacao'] . ") ". $user['nomeUtilizador'] .": ";
            }else{
            echo "<div class='msg'>(" . $user['dataCriacao'] . ") ". $user['nomeUtilizador'] .": "; }
            if($user['ban']){
                echo "<a href='../PHP/responderBan.php?request=0&cod=".$user['codUtilizador']."'>Desbanir</a>";
            }else{
                echo "<a href='../PHP/responderBan.php?request=1&cod=".$user['codUtilizador']."'>Banir</a>";
            }
            echo "</div>";
        }
    }else{
        echo "<div class='msg'>Nenhum Utilizador...</div>";
    }
    echo '</div>';
?>