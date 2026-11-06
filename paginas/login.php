 <?php

require_once __DIR__ . '/../PHP/auth.php';

const MAX_TENTATIVAS = 5;
const BLOQUEIO_SEG   = 900; // 15 minutos

$pdo  = ConnectDB();
$erro = '';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <div class="login-card" style="border: 1px solid black; border-radius: 5px; width: 350px; height: 200px; justify-self: center;">
            <form action="" method="post" style="display: flex; flex-direction: column;">
                <input type="text" name="user" id="" placeholder="Username" required>
                <input type="email" name="email" id="" placeholder="E-mail" required>
                <input type="password" name="password" id="" placeholder="Password" required>
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES) ?>">
                <input type="submit" value="Login">

                <?php if ($erro): ?>
                    <p style="color: red;"><?= htmlspecialchars($erro, ENT_QUOTES) ?></p>
                <?php endif; ?>
               

                <p>Não tens uma conta? <a href="register.php">Registra-te</a></p>
            </form>
        </div>
    </div>
</body>
</html>