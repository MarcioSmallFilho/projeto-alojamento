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
    $r = $pdo->prepare("SELECT admin FROM utilizador WHERE nomeUtilizador = :nome");
    $r->execute([":nome"=> $_SESSION['nomeUtilizador']]);

    $resultado = $r->fetch(PDO::FETCH_ASSOC);
    if ($resultado["admin"] == 0) {
        header('Location: ' . URL_INICIO);
    }
}

iniciarSessaoSegura();