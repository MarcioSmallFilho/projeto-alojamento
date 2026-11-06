<?php

require_once __DIR__ . '/../PHP/auth.php';

$pdo  = ConnectDB();
$erro = '';

if (utilizadorAutenticado($pdo)) {
    header('Location: ' . URL_INICIO);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome  = trim($_POST['user'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pass1 = $_POST['password'] ?? '';   // sem trim: a palavra-passe é usada tal como foi escrita
    $pass2 = $_POST['password-2'] ?? '';
    $nacio = trim($_POST['nacionalidade'] ?? '');

    if (!csrfValido($_POST['csrf'] ?? null)) {
        $erro = 'Pedido inválido. Atualize a página e tente novamente.';
    } elseif (!preg_match('/^[\p{L}\p{N}_.-]{3,40}$/u', $nome)) {
        $erro = 'O nome de utilizador deve ter 3 a 30 caracteres (letras, números, _ . -).';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        $erro = 'E-mail inválido.';
    } elseif (strlen($pass1) < 8 || strlen($pass1) > 72) { // bcrypt só considera 72 bytes
        $erro = 'A palavra-passe deve ter entre 8 e 72 caracteres.';
    } elseif (!hash_equals($pass1, $pass2)) {
        $erro = 'As palavras-passe não coincidem.';
    } elseif (!preg_match('/^[A-Z]{3}$/', $nacio)) {
        $erro = 'Selecione uma nacionalidade válida.';
    } else {
        $stmt = $pdo->prepare(
            "SELECT 1 FROM Utilizador
             WHERE emailUtilizador = :email OR nomeUtilizador = :nome
             LIMIT 1"
        );
        $stmt->execute([':email' => $email, ':nome' => $nome]);

        if ($stmt->fetchColumn()) {
            $erro = 'Nome de utilizador ou e-mail já existente.';
        } else {
            try {
                $pdo->prepare(
                    "INSERT INTO Utilizador (nomeUtilizador, emailUtilizador, palavraPasse, paisOrigem)
                     VALUES (:nome, :email, :pass, :nacionalidade)"
                )->execute([
                    ':nome'          => $nome,
                    ':email'         => $email,
                    ':pass'          => password_hash($pass1, PASSWORD_DEFAULT),
                    ':nacionalidade' => $nacio,
                ]);

                $novo = ['codUtilizador' => (int)$pdo->lastInsertId(), 'nomeUtilizador' => $nome];
                iniciarSessao($novo);
                criarTokenPersistente($pdo, $novo['codUtilizador']);

                header('Location: ' . URL_INICIO);
                exit;
            } catch (PDOException $e) {
                if ($e->getCode() === '23000') { // duplicado (corrida entre dois registos)
                    $erro = 'Nome de utilizador ou e-mail já existente.';
                } else {
                    error_log($e->getMessage());
                    $erro = 'Ocorreu um erro. Tente novamente mais tarde.';
                }
            }
        }
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
                <input type="password" name="password-2" id="" placeholder="Repeat the password" required>
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES) ?>">
                <select name="nacionalidade" id="" required>
                <option value="" disabled selected>Nacionalidade...</option>
                    <option value="AFG">Afegão / Afegã</option>
                    <option value="ZAF">Sul-africano / Sul-africana</option>
                    <option value="ALB">Albanês / Albanesa</option>
                    <option value="DEU">Alemão / Alemã</option>
                    <option value="AND">Andorrano / Andorrana</option>
                    <option value="AGO">Angolano / Angolana</option>
                    <option value="SAU">Saudita</option>
                    <option value="DZA">Argelino / Argelina</option>
                    <option value="ARG">Argentino / Argentina</option>
                    <option value="ARM">Arménio / Arménia</option>
                    <option value="AUS">Australiano / Australiana</option>
                    <option value="AUT">Austríaco / Austríaca</option>
                    <option value="AZE">Azeri / Azerbaijano</option>
                    <option value="BHS">Bahamense</option>
                    <option value="BGD">Bangladexiano / Bangladexiana</option>
                    <option value="BEL">Belga</option>
                    <option value="BRA">Brasileiro / Brasileira</option>
                    <option value="CPV">Cabo-verdiano / Cabo-verdiana</option>
                    <option value="CAN">Canadiano / Canadiana</option>
                    <option value="CHL">Chileno / Chilena</option>
                    <option value="CHN">Chinês / Chinesa</option>
                    <option value="COL">Colombiano / Colombiana</option>
                    <option value="KOR">Sul-coreano / Sul-coreana</option>
                    <option value="CRI">Costarriquenho / Costarriquenha</option>
                    <option value="CUB">Cubano / Cubana</option>
                    <option value="DNK">Dinamarquês / Dinamarquesa</option>
                    <option value="EGY">Egípcio / Egípcia</option>
                    <option value="ARE">Emiratense</option>
                    <option value="ECU">Equatoriano / Equatoriana</option>
                    <option value="ESP">Espanhol / Espanhola</option>
                    <option value="USA">Norte-americano / Norte-americana</option>
                    <option value="PHL">Filipino / Filipina</option>
                    <option value="FIN">Finlandês / Finlandesa</option>
                    <option value="FRA">Francês / Francesa</option>
                    <option value="GRC">Grego / Grega</option>
                    <option value="GTM">Guatemalteco / Guatemalteca</option>
                    <option value="NLD">Neerlandês / Neerlandesa</option>
                    <option value="HND">Hondurenho / Hondurenha</option>
                    <option value="HUN">Húngaro / Húngara</option>
                    <option value="IND">Indiano / Indiana</option>
                    <option value="IDN">Indonésio / Indonésia</option>
                    <option value="IRQ">Iraquiano / Iraquiana</option>
                    <option value="IRN">Iraniano / Iraniana</option>
                    <option value="IRL">Irlandês / Irlandesa</option>
                    <option value="ISL">Islandês / Islandesa</option>
                    <option value="ISR">Israelita / Israelense</option>
                    <option value="ITA">Italiano / Italiana</option>
                    <option value="JAM">Jamaicano / Jamaicana</option>
                    <option value="JPN">Japonês / Japonesa</option>
                    <option value="JOR">Jordaniano / Jordaniana</option>
                    <option value="KWT">Kuwaitiano / Kuwaitiana</option>
                    <option value="LBN">Libanês / Libanesa</option>
                    <option value="MYS">Malaio / Malaia</option>
                    <option value="MAR">Marroquino / Marroquina</option>
                    <option value="MEX">Mexicano / Mexicana</option>
                    <option value="MOZ">Moçambicano / Moçambicana</option>
                    <option value="NPL">Nepalês / Nepalesa</option>
                    <option value="NOR">Norueguês / Norueguesa</option>
                    <option value="NZL">Neozelandês / Neozelandesa</option>
                    <option value="PAK">Paquistanês / Paquistanesa</option>
                    <option value="PRY">Paraguaio / Paraguaia</option>
                    <option value="PER">Peruano / Peruana</option>
                    <option value="POL">Polaco / Polaca</option>
                    <option value="PRT">Português / Portuguesa</option>
                    <option value="GBR">Britânico / Britânica</option>
                    <option value="DOM">Dominicano / Dominicana</option>
                    <option value="ROU">Romeno / Romena</option>
                    <option value="RUS">Russo / Russa</option>
                    <option value="SLV">Salvadorenho / Salvadorenha</option>
                    <option value="SRB">Sérvio / Sérvia</option>
                    <option value="SGP">Singapurense</option>
                    <option value="SYR">Sírio / Síria</option>
                    <option value="SWE">Sueco / Sueca</option>
                    <option value="CHE">Suíço / Suíça</option>
                    <option value="THA">Tailandês / Tailandesa</option>
                    <option value="TWN">Taiwanês / Taiwanesa</option>
                    <option value="TZA">Tanzaniano / Tanzaniana</option>
                    <option value="TUR">Turco / Turca</option>
                    <option value="UKR">Ucraniano / Ucraniana</option>
                    <option value="UGA">Ugandês / Ugandesa</option>
                    <option value="URY">Uruguaio / Uruguaia</option>
                    <option value="VEN">Venezuelano / Venezuelana</option>
                    <option value="VNM">Vietnamita</option>
                    <option value="ZMB">Zambiano / Zambiana</option>
                    <option value="ZWE">Zimbabueano / Zimbabueana</option>
            </select> 
                <input type="submit" value="Criar Conta">

                <?php if ($erro): ?>
                    <p style="color: red;"><?= htmlspecialchars($erro, ENT_QUOTES) ?></p>
                <?php endif; ?>

                <p>Já tens uma conta? <a href="login.php">Login</a></p>
            </form>
        </div>
    </div>
</body>
</html>