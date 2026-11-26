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

    if (session_status() === PHP_SESSION_NONE) session_start();

    // 1. Guardar o filtro na sessão e redirecionar
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_id'] ?? '') === 'filtro') {
        $_SESSION['filtro'] = $_POST;
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    }

    // 2. Limpar o filtro
    if (isset($_GET['limpar'])) {
        unset($_SESSION['filtro']);
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit;
    }

    $f = $_SESSION['filtro'] ?? [];
?>

<form action="" method="post" id="filtro">
    <input type="hidden" name="form_id" value="filtro">
    <label>Banido:</label>
    <input type="checkbox" name="ban" value="on" <?= !empty($f['ban']) ? 'checked' : '' ?>>
    <input type="number" name="nReservas_user" min="1"
        value="<?= htmlspecialchars($f['nReservas_user'] ?? '5', ENT_QUOTES, 'UTF-8') ?>">
    <input type="text" name="nome_utilizador" placeholder="nome de utilizador"
        value="<?= htmlspecialchars($f['nome_utilizador'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <input type="number" name="cod_utilizador" placeholder="codigo de utilizador"
        value="<?= htmlspecialchars($f['cod_utilizador'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    <input type="submit" value="Filtrar">
    <a href="?limpar=1">Limpar filtro</a>
</form>

<?php
    $limite = 5; // valor por defeito
    if (isset($f['nReservas_user']) && is_numeric($f['nReservas_user']) && (int)$f['nReservas_user'] > 0) {
        $limite = (int)$f['nReservas_user'];
    }

    $condicao = [];
    $params = [];

    if (!empty($f['nome_utilizador'])) {
        $condicao[] = 'nomeUtilizador LIKE ?';
        $params[] = $f['nome_utilizador'] . '%';
    }
    if (!empty($f['cod_utilizador'])) {
        $condicao[] = 'codUtilizador = ?';
        $params[] = $f['cod_utilizador'];
    }

    // Banido marcado: mostra banidos. Desmarcado: mostra não banidos.
    $condicao[] = 'ban = ?';
    $params[] = !empty($f['ban']) ? 1 : 0;

    $where = ' WHERE ' . implode(' AND ', $condicao);

    $users = SELECT($pdo, "SELECT * FROM utilizador$where ORDER BY dataCriacao DESC LIMIT $limite", $params);
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