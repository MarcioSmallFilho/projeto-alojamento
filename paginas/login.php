<?php

require_once __DIR__ . '/../PHP/auth.php';

const MAX_TENTATIVAS = 5;
const BLOQUEIO_SEG   = 900; // 15 minutos

$pdo   = ConnectDB();
$erro  = '';
$nome  = '';
$email = '';
if (utilizadorAutenticado($pdo)) {
    header('Location: ' . URL_INICIO);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['user'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $bloqueadoAte = $_SESSION['bloqueio_ate'] ?? 0;

    if (!csrfValido($_POST['csrf'] ?? null)) {
        $erro = 'Pedido inválido. Atualize a página e tente novamente.';
    } elseif ($bloqueadoAte > time()) {
        $minutos = (int)ceil(($bloqueadoAte - time()) / 60);
        $erro = "Demasiadas tentativas. Tente novamente dentro de {$minutos} minuto(s).";
    } elseif ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $erro = 'Preencha todos os campos corretamente.';
    } else {
        $stmt = $pdo->prepare(
            "SELECT * FROM Utilizador
             WHERE nomeUtilizador = :nome AND emailUtilizador = :email
             LIMIT 1"
        );
        $stmt->execute([':nome' => $nome, ':email' => $email]);
        $u = $stmt->fetch(PDO::FETCH_ASSOC);

        $hash   = $u['palavraPasse'] ?? '$2y$10$usesomesillystringforsaleeKqzGcPzLxB0tnnQzKcx6w6wZ0rW';
        $valida = password_verify($password, $hash);

        if ($u && $valida) {
            unset($_SESSION['tentativas'], $_SESSION['bloqueio_ate']);

            if (password_needs_rehash($u['palavraPasse'], PASSWORD_DEFAULT)) {
                $pdo->prepare("UPDATE Utilizador SET palavraPasse = :h WHERE codUtilizador = :id")
                    ->execute([':h' => password_hash($password, PASSWORD_DEFAULT), ':id' => $u['codUtilizador']]);
            }

            iniciarSessao($u);
            criarTokenPersistente($pdo, (int)$u['codUtilizador']);
            if($_GET['source']){
                header('Location: ' . $_GET['source']);
                exit;
            }
            header('Location: ' . URL_INICIO);
            exit;
        }

        $_SESSION['tentativas'] = ($_SESSION['tentativas'] ?? 0) + 1;
        if ($_SESSION['tentativas'] >= MAX_TENTATIVAS) {
            $_SESSION['bloqueio_ate'] = time() + BLOQUEIO_SEG;
            $_SESSION['tentativas']   = 0;
        }
        $erro = 'Credenciais incorretas.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar | Oasis Dune</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="auth">
    <main class="auth-cartao">
        <header class="auth-topo">
            <p class="auth-marca">Oasis Dune</p>
            <h1>Entrar</h1>
            <p>Bem-vindo de volta. Inicie sessão para continuar.</p>
        </header>

        <?php if ($erro): ?>
            <div class="alerta" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES) ?></div>
        <?php endif; ?>

        <form class="auth-form" action="" method="post">
            <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES) ?>">

            <div class="campo">
                <label for="user">Nome de utilizador</label>
                <input type="text" name="user" id="user" maxlength="40" autocomplete="username"
                       placeholder="O seu nome de utilizador" value="<?= htmlspecialchars($nome, ENT_QUOTES) ?>" required>
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="email" name="email" id="email" maxlength="100" autocomplete="email"
                       placeholder="exemplo@email.com" value="<?= htmlspecialchars($email, ENT_QUOTES) ?>" required>
            </div>

            <div class="campo">
                <label for="password">Palavra-passe</label>
                <div class="campo-senha">
                    <input type="password" name="password" id="password" maxlength="72" autocomplete="current-password"
                           placeholder="A sua palavra-passe" required>
                    <button type="button" class="btn-olho" data-alvo="password" aria-pressed="false">Mostrar</button>
                </div>
            </div>

            <button type="submit" class="btn-auth">Entrar</button>
        </form>

        <p class="auth-rodape">Não tens uma conta? <a href="register.php">Regista-te</a></p>
    </main>

    <script src="../script/auth.js"></script>
</body>
</html>
