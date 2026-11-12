<?php

require_once 'C:/xampp/database.php';

const COOKIE_TOKEN    = 'remember_me';
const TOKEN_DIAS      = 30;
const URL_LOGIN       = '../paginas/login.php';
const URL_LOGOUT      = '../PHP/logout.php';
const URL_INICIO      = '../paginas/index.php'; // ajuste para a sua página inicial

function iniciarSessaoSegura(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function iniciarSessao(array $u): void
{
    session_regenerate_id(true); // evita session fixation
    $_SESSION['codUtilizador']   = (int)$u['codUtilizador'];
    $_SESSION['nomeUtilizador'] = $u['nomeUtilizador'];
    $_SESSION['csrf']           = bin2hex(random_bytes(32));
}

function definirCookie(string $valor, int $expira): void
{
    setcookie(COOKIE_TOKEN, $valor, [
        'expires'  => $expira,
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function criarTokenPersistente(PDO $pdo, int $codUtilizador): void
{
    $selector  = bin2hex(random_bytes(12));
    $validator = bin2hex(random_bytes(32));
    $expira    = time() + TOKEN_DIAS * 86400;

    $pdo->prepare(
        "INSERT INTO TokenSessao (codUtilizador, selector, validatorHash, expira)
         VALUES (:id, :sel, :hash, :exp)"
    )->execute([
        ':id'   => $codUtilizador,
        ':sel'  => $selector,
        ':hash' => hash('sha256', $validator),
        ':exp'  => date('Y-m-d H:i:s', $expira),
    ]);

    definirCookie($selector . ':' . $validator, $expira);
}

function loginPorCookie(PDO $pdo): bool
{
    if (empty($_COOKIE[COOKIE_TOKEN]) || !str_contains($_COOKIE[COOKIE_TOKEN], ':')) {
        return false;
    }

    [$selector, $validator] = explode(':', $_COOKIE[COOKIE_TOKEN], 2);

    $stmt = $pdo->prepare(
        "SELECT t.idToken, t.validatorHash, u.codUtilizador, u.nomeUtilizador
         FROM TokenSessao t
         JOIN Utilizador u ON u.codUtilizador = t.codUtilizador
         WHERE t.selector = :sel AND t.expira > NOW()"
    );
    $stmt->execute([':sel' => $selector]);
    $linha = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$linha) {
        definirCookie('', time() - 3600);
        return false;
    }

    if (!hash_equals($linha['validatorHash'], hash('sha256', $validator))) {
        $pdo->prepare("DELETE FROM TokenSessao WHERE selector = :sel")->execute([':sel' => $selector]);
        definirCookie('', time() - 3600);
        return false;
    }

    // Rotação do token
    $pdo->prepare("DELETE FROM TokenSessao WHERE idToken = :id")->execute([':id' => $linha['idToken']]);
    iniciarSessao($linha);
    criarTokenPersistente($pdo, (int)$linha['codUtilizador']);
    return true;
}

function utilizadorAutenticado(PDO $pdo): bool
{
    return !empty($_SESSION['codUtilizador']) || loginPorCookie($pdo);
}

/** Chamar no topo de qualquer página que exija conta. */
function exigirLogin(PDO $pdo): void
{
    header('Cache-Control: no-store'); // o botão "voltar" não mostra páginas depois do logout
    if (!utilizadorAutenticado($pdo)) {
        header('Location: ' . URL_LOGIN);
        exit;
    }
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrfValido(?string $token): bool
{
    return is_string($token) && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}

function terminarSessao(PDO $pdo): void
{
    if (!empty($_COOKIE[COOKIE_TOKEN]) && str_contains($_COOKIE[COOKIE_TOKEN], ':')) {
        [$selector] = explode(':', $_COOKIE[COOKIE_TOKEN], 2);
        $pdo->prepare("DELETE FROM TokenSessao WHERE selector = :sel")->execute([':sel' => $selector]);
    }
    definirCookie('', time() - 3600);
    $_SESSION = [];
    session_destroy();
}

function is_admin(PDO $pdo) {
    if (!utilizadorAutenticado($pdo)) {
        return ;
    }
    $r = $pdo->prepare("SELECT admin FROM utilizador WHERE nomeUtilizador = :nome");
    $r->execute([":nome"=> $_SESSION['nomeUtilizador']]);

    $resultado = $r->fetch(PDO::FETCH_ASSOC);
    if ($resultado["admin"] == 1) {
       return TRUE;
    }
}

//Botão para mandar a pagina de admin
function admPage(PDO $pdo){
    if (is_admin($pdo)){
        echo '<a href="paginaadmin.php">Admin Page</a>';
    }
}

//========================================
// 5. Funções Globais
//========================================

function SELECT($pdo, $sql, $params = []){
    try{
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $fetch = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }catch(PDOException $e){
        echo 'Erro manito.';
    }
    return $fetch;
}
function INSERT($pdo, $sql, $params=[]){   
    //prepara o SQL 
    $stmt = $pdo->prepare($sql);
    //executa o SQL 
    if ($stmt->execute($params)) {  
        //caso funcione
        return true;}
    return NULL;
    }

function procurarImagem(PDO $pdo, $nome = null, $seccao = null){
    $query = 'SELECT nomeAlojamento,caminho FROM alojamento AS a INNER JOIN imagem AS i ON a.codAlojamento = i.alojamento';

    if (!is_null($nome) AND !is_null($seccao)) {$query .= ' WHERE i.nomeOriginal = '. $nome .' AND i.seccao = '. $seccao;}
    elseif (!is_null($nome)) {$query .= ' WHERE i.nomeOriginal = '. $nome;}
    elseif (!is_null($seccao)) {$query .= ' WHERE i.seccao ='. $seccao;}

    $srmt = $pdo->prepare($query);
    $srmt->execute();

    while ($linha = $srmt->fetch(PDO::FETCH_ASSOC)){
        print_r([$linha['nomeAlojamento'] => $linha['caminho']]);
        $resultado[] = [$linha['nomeAlojamento'] => '../'.$linha['caminho']];
    }

    return $resultado;
}

//Se está logado aparece o logout, se não aparece para logar
function paginaLogin(PDO $pdo){
    if (utilizadorAutenticado($pdo)){echo '<a href="../PHP/logout.php">Logout</a>';}
    else{echo '<a href="login.php">Login/Register</a>';}
}

iniciarSessaoSegura();