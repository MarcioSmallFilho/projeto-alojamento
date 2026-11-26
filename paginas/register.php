<?php
require_once __DIR__ . '/../PHP/auth.php';
include_once '../enviar.php';

$pdo   = ConnectDB();
$erro  = '';
$nome  = '';
$email = '';
$nacio = '';
$agora = new DateTime();
$datahora = $agora->format('Y-m-d');

$nacionalidades = [
    'AFG' => 'Afegão / Afegã',
    'ZAF' => 'Sul-africano / Sul-africana',
    'ALB' => 'Albanês / Albanesa',
    'DEU' => 'Alemão / Alemã',
    'AND' => 'Andorrano / Andorrana',
    'AGO' => 'Angolano / Angolana',
    'SAU' => 'Saudita',
    'DZA' => 'Argelino / Argelina',
    'ARG' => 'Argentino / Argentina',
    'ARM' => 'Arménio / Arménia',
    'AUS' => 'Australiano / Australiana',
    'AUT' => 'Austríaco / Austríaca',
    'AZE' => 'Azeri / Azerbaijano',
    'BHS' => 'Bahamense',
    'BGD' => 'Bangladexiano / Bangladexiana',
    'BEL' => 'Belga',
    'BRA' => 'Brasileiro / Brasileira',
    'CPV' => 'Cabo-verdiano / Cabo-verdiana',
    'CAN' => 'Canadiano / Canadiana',
    'CHL' => 'Chileno / Chilena',
    'CHN' => 'Chinês / Chinesa',
    'COL' => 'Colombiano / Colombiana',
    'KOR' => 'Sul-coreano / Sul-coreana',
    'CRI' => 'Costarriquenho / Costarriquenha',
    'CUB' => 'Cubano / Cubana',
    'DNK' => 'Dinamarquês / Dinamarquesa',
    'EGY' => 'Egípcio / Egípcia',
    'ARE' => 'Emiratense',
    'ECU' => 'Equatoriano / Equatoriana',
    'ESP' => 'Espanhol / Espanhola',
    'USA' => 'Norte-americano / Norte-americana',
    'PHL' => 'Filipino / Filipina',
    'FIN' => 'Finlandês / Finlandesa',
    'FRA' => 'Francês / Francesa',
    'GRC' => 'Grego / Grega',
    'GTM' => 'Guatemalteco / Guatemalteca',
    'NLD' => 'Neerlandês / Neerlandesa',
    'HND' => 'Hondurenho / Hondurenha',
    'HUN' => 'Húngaro / Húngara',
    'IND' => 'Indiano / Indiana',
    'IDN' => 'Indonésio / Indonésia',
    'IRQ' => 'Iraquiano / Iraquiana',
    'IRN' => 'Iraniano / Iraniana',
    'IRL' => 'Irlandês / Irlandesa',
    'ISL' => 'Islandês / Islandesa',
    'ISR' => 'Israelita / Israelense',
    'ITA' => 'Italiano / Italiana',
    'JAM' => 'Jamaicano / Jamaicana',
    'JPN' => 'Japonês / Japonesa',
    'JOR' => 'Jordaniano / Jordaniana',
    'KWT' => 'Kuwaitiano / Kuwaitiana',
    'LBN' => 'Libanês / Libanesa',
    'MYS' => 'Malaio / Malaia',
    'MAR' => 'Marroquino / Marroquina',
    'MEX' => 'Mexicano / Mexicana',
    'MOZ' => 'Moçambicano / Moçambicana',
    'NPL' => 'Nepalês / Nepalesa',
    'NOR' => 'Norueguês / Norueguesa',
    'NZL' => 'Neozelandês / Neozelandesa',
    'PAK' => 'Paquistanês / Paquistanesa',
    'PRY' => 'Paraguaio / Paraguaia',
    'PER' => 'Peruano / Peruana',
    'POL' => 'Polaco / Polaca',
    'PRT' => 'Português / Portuguesa',
    'GBR' => 'Britânico / Britânica',
    'DOM' => 'Dominicano / Dominicana',
    'ROU' => 'Romeno / Romena',
    'RUS' => 'Russo / Russa',
    'SLV' => 'Salvadorenho / Salvadorenha',
    'SRB' => 'Sérvio / Sérvia',
    'SGP' => 'Singapurense',
    'SYR' => 'Sírio / Síria',
    'SWE' => 'Sueco / Sueca',
    'CHE' => 'Suíço / Suíça',
    'THA' => 'Tailandês / Tailandesa',
    'TWN' => 'Taiwanês / Taiwanesa',
    'TZA' => 'Tanzaniano / Tanzaniana',
    'TUR' => 'Turco / Turca',
    'UKR' => 'Ucraniano / Ucraniana',
    'UGA' => 'Ugandês / Ugandesa',
    'URY' => 'Uruguaio / Uruguaia',
    'VEN' => 'Venezuelano / Venezuelana',
    'VNM' => 'Vietnamita',
    'ZMB' => 'Zambiano / Zambiana',
    'ZWE' => 'Zimbabueano / Zimbabueana',
];

if (utilizadorAutenticado($pdo)) {
    header('Location: ' . URL_INICIO);
    exit;
}

$etapa = $_SESSION['etapa_registo'] ?? 'formulario';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfValido($_POST['csrf'] ?? null)) {
        $erro = 'Pedido inválido. Atualize a página e tente novamente.';
    } else {
        // ETAPA 1: Submissão do formulário de registo
        if ($etapa === 'formulario') {
            $nome  = trim($_POST['user'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $pass1 = $_POST['password'] ?? '';
            $pass2 = $_POST['password-2'] ?? '';
            $nacio = trim($_POST['nacionalidade'] ?? '');

            if (!preg_match('/^[\p{L}\p{N}_\s.-]{3,40}$/u', $nome)) {
                $erro = 'O nome de utilizador deve ter 3 a 40 caracteres (letras, números, _ . - ).';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 || !verificarEmailExiste($email)) {
                $erro = 'E-mail inválido.';
            } elseif (strlen($pass1) < 8 || strlen($pass1) > 72) {
                $erro = 'A palavra-passe deve ter entre 8 e 72 caracteres.';
            } elseif (!hash_equals($pass1, $pass2)) {
                $erro = 'As palavras-passe não coincidem.';
            } elseif (!isset($nacionalidades[$nacio])) {
                $erro = 'Selecione uma nacionalidade válida.';
            } elseif (!validarCaptcha($_POST['cf-turnstile-response'] ?? null)) {
                $erro = 'Ocorreu um erro, tente novamente mais tarde.';
            } else {
                $stmt = $pdo->prepare("SELECT 1 FROM Utilizador WHERE emailUtilizador = :email OR nomeUtilizador = :nome LIMIT 1");
                $stmt->execute([':email' => $email, ':nome' => $nome]);

                if ($stmt->fetchColumn()) {
                    $erro = 'Nome de utilizador ou e-mail já existente.';
                } else {
                    // Gerar código de verificação de 6 dígitos
                    $codigoVerificacao = random_int(100000, 999999);

                    // Guardar dados temporariamente na sessão
                    $_SESSION['registo_temp'] = [
                        'nome' => $nome,
                        'email' => $email,
                        'pass' => password_hash($pass1, PASSWORD_DEFAULT),
                        'nacionalidade' => $nacio,
                        'dataHora' => $datahora,
                        'codigo' => $codigoVerificacao
                    ];
                    $_SESSION['etapa_registo'] = 'verificacao';
                    $etapa = 'verificacao';

                    // Enviar o e-mail com o código
                    $mensagemHtml = "<h1>Verificar Email!</h1><p>Código de Verificação: <strong>{$codigoVerificacao}</strong></p>";
                    enviarEmail([$email, $nome], $email, 'Verificar e-mail', $mensagemHtml);
                }
            }
        } 
        // ETAPA 2: Verificação do código introduzido
        elseif ($etapa === 'verificacao') {
            $codigoInserido = trim($_POST['codigo_verificacao'] ?? '');
            $dadosTemp = $_SESSION['registo_temp'] ?? null;

            if (!$dadosTemp) {
                $erro = 'Sessão expirada. Preencha o formulário novamente.';
                $_SESSION['etapa_registo'] = 'formulario';
                $etapa = 'formulario';
            } elseif ((int)$codigoInserido !== (int)$dadosTemp['codigo']) {
                $erro = 'Código de verificação incorreto.';
            } else {
                try {
                    // Inserir efetivamente na base de dados após confirmar o e-mail
                    $stmt = $pdo->prepare(
                        "INSERT INTO Utilizador (nomeUtilizador, emailUtilizador, palavraPasse, paisOrigem, dataCriacao)
                         VALUES (:nome, :email, :pass, :nacionalidade, :dataHora)"
                    );
                    $stmt->execute([
                        ':nome'          => $dadosTemp['nome'],
                        ':email'         => $dadosTemp['email'],
                        ':pass'          => $dadosTemp['pass'],
                        ':nacionalidade' => $dadosTemp['nacionalidade'],
                        ':dataHora'      => $dadosTemp['dataHora'],
                    ]);

                    $novo = ['codUtilizador' => (int)$pdo->lastInsertId(), 'nomeUtilizador' => $dadosTemp['nome']];
                    
                    // Limpar dados temporários da sessão
                    unset($_SESSION['registo_temp'], $_SESSION['etapa_registo']);

                    iniciarSessao($novo);
                    criarTokenPersistente($pdo, $novo['codUtilizador']);

                    header('Location: ' . URL_INICIO);
                    exit;
                } catch (PDOException $e) {
                    error_log($e->getMessage());
                    $erro = 'Ocorreu um erro ao criar a conta. Tente novamente mais tarde.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta | Oasis Dune</title>
    <link rel="stylesheet" href="../css/auth.css">
</head>
<body class="auth">
    <main class="auth-cartao largo">
        <header class="auth-topo">
            <p class="auth-marca">Oasis Dune</p>
            <h1><?= $etapa === 'verificacao' ? 'Verificar E-mail' : 'Criar conta' ?></h1>
            <p><?= $etapa === 'verificacao' ? 'Insira o código enviado para o seu e-mail.' : 'Registe-se em poucos segundos.' ?></p>
        </header>

        <?php if ($erro): ?>
            <div class="alerta" role="alert"><?= htmlspecialchars($erro, ENT_QUOTES) ?></div>
        <?php endif; ?>

        <?php if ($etapa === 'formulario'): ?>
            <!-- FORMULÁRIO DE REGISTO INICIAL -->
            <form class="auth-form" action="" method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES) ?>">

                <div class="campo">
                    <label for="user">Nome de utilizador</label>
                    <input type="text" name="user" id="user" maxlength="40" autocomplete="username"
                           placeholder="Ex.: maria_silva" value="<?= htmlspecialchars($nome, ENT_QUOTES) ?>"
                           data-regra="nome" data-min="3" data-max="40" data-medida="pontos"
                           data-ok="Nome de utilizador válido." required>
                    <div class="dica">
                        <span class="dica-texto">3 a 40 caracteres: letras, números, _ . -</span>
                        <span class="dica-contador">0/40</span>
                    </div>
                </div>

                <div class="campo">
                    <label for="email">E-mail</label>
                    <input type="email" name="email" id="email" maxlength="100" autocomplete="email"
                           placeholder="exemplo@email.com" value="<?= htmlspecialchars($email, ENT_QUOTES) ?>"
                           data-regra="email" data-min="5" data-max="100" data-medida="bytes"
                           data-ok="E-mail válido." required>
                    <div class="dica">
                        <span class="dica-texto">Um endereço de e-mail válido (máx. 100 caracteres)</span>
                        <span class="dica-contador">0/100</span>
                    </div>
                </div>

                <div class="campo">
                    <label for="password">Palavra-passe</label>
                    <div class="campo-senha">
                        <input type="password" name="password" id="password" maxlength="72" autocomplete="new-password"
                               placeholder="Mínimo 8 caracteres"
                               data-regra="senha" data-min="8" data-max="72" data-medida="bytes"
                               data-ok="Palavra-passe com o tamanho correto." required>
                        <button type="button" class="btn-olho" data-alvo="password" aria-pressed="false">Mostrar</button>
                    </div>
                    <div class="dica">
                        <span class="dica-texto">Entre 8 e 72 caracteres</span>
                        <span class="dica-contador">0/72</span>
                    </div>
                </div>

                <div class="campo">
                    <label for="password-2">Repetir palavra-passe</label>
                    <div class="campo-senha">
                        <input type="password" name="password-2" id="password-2" maxlength="72" autocomplete="new-password"
                               placeholder="Repita a palavra-passe"
                               data-regra="repetir" data-igual-a="password" data-min="8" data-max="72" data-medida="bytes"
                               data-ok="As palavras-passe coincidem." required>
                        <button type="button" class="btn-olho" data-alvo="password-2" aria-pressed="false">Mostrar</button>
                    </div>
                    <div class="dica">
                        <span class="dica-texto">Tem de ser igual à palavra-passe acima</span>
                    </div>
                </div>

                <div class="campo">
                    <label for="nacionalidade">Nacionalidade</label>
                    <select name="nacionalidade" id="nacionalidade" required>
                        <option value="" disabled <?= $nacio === '' ? 'selected' : '' ?>>Selecione...</option>
                        <?php foreach ($nacionalidades as $codigo => $descricao): ?>
                            <option value="<?= $codigo ?>" <?= $nacio === $codigo ? 'selected' : '' ?>><?= htmlspecialchars($descricao, ENT_QUOTES) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                <div class="cf-turnstile" data-sitekey="<?php echo CAPTCHA_PUBLIC; ?>" data-appearance="interaction-only"></div>

                <button type="submit" class="btn-auth">Criar conta</button>
            </form>
            <p class="auth-rodape">Já tens uma conta? <a href="login.php">Entra aqui</a></p>

        <?php elseif ($etapa === 'verificacao'): ?>
            <!-- FORMULÁRIO DE INSERÇÃO DO CÓDIGO -->
            <form class="auth-form" action="" method="post">
                <input type="hidden" name="csrf" value="<?= htmlspecialchars(csrfToken(), ENT_QUOTES) ?>">

                <div class="campo">
                    <label for="codigo_verificacao">Código de Verificação</label>
                    <input type="text" name="codigo_verificacao" id="codigo_verificacao" maxlength="6" 
                           placeholder="Insira o código de 6 dígitos" required autofocus>
                    <div class="dica">
                        <span class="dica-texto">Enviamos um código para o seu e-mail.</span>
                    </div>
                </div>

                <button type="submit" class="btn-auth">Verificar e Concluir</button>
            </form>
        <?php endif; ?>
    </main>

    <script src="../script/auth.js"></script>
</body>
</html>