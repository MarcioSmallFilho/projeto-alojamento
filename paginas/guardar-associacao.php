<?php
require_once '../PHP/auth.php';

$pdo = ConnectDB();
exigirLogin($pdo);

if (!is_admin($pdo)) {
    http_response_code(403);
    exit('Acesso negado.');
}

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/*
 * As duas coisas a que uma imagem é associada (ambas obrigatórias).
 * Os nomes de tabela e de colunas usados nas consultas vêm APENAS desta
 * lista fixa, nunca do que o utilizador envia.
 */
const ENTIDADES_ASSOCIACAO = [
    'alojamento' => [
        'rotulo'  => 'Alojamento',
        'tabela'  => 'alojamento',
        'colId'   => 'codAlojamento',
        'colNome' => 'nomeAlojamento',
    ],
    'secao' => [
        'rotulo'  => 'Secção',
        'tabela'  => 'Secao',
        'colId'   => 'idSecao',
        'colNome' => 'nomeSecao',
    ],
];

// Erros cuja mensagem pode ser mostrada ao utilizador.
// (Os erros da base de dados nunca são mostrados: ficam só no registo de erros.)
class ErroAssociacao extends Exception
{
}

function assocEscapar(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES, 'UTF-8');
}

/** Lista os registos existentes (para o utilizador escolher pelo nome). */
function assocListar(PDO $pdo, string $entidade): array
{
    $c = ENTIDADES_ASSOCIACAO[$entidade];

    $sql = "SELECT {$c['colId']} AS id, {$c['colNome']} AS nome
            FROM {$c['tabela']}
            ORDER BY {$c['colNome']}";

    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

/** Confirma que o identificador existe na tabela correspondente. */
function assocExiste(PDO $pdo, string $entidade, int $id): bool
{
    $c = ENTIDADES_ASSOCIACAO[$entidade];

    $stmt = $pdo->prepare("
        SELECT 1
        FROM {$c['tabela']}
        WHERE {$c['colId']} = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);

    return (bool) $stmt->fetchColumn();
}

// Mensagem do formulário "Associar imagem"
$mensagem = '';

// Mensagem do formulário "Adicionar secção" (guardada antes do redirecionamento,
// para que atualizar a página não reenvie o formulário)
$mensagemSecao = $_SESSION['flash_secao'] ?? '';
unset($_SESSION['flash_secao']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // A verificação do token é comum aos dois formulários
    $tokenRecebido = $_POST['csrf'] ?? '';

    if (!is_string($tokenRecebido) || !hash_equals($_SESSION['csrf'], $tokenRecebido) && !isset($_POST['formulario_id']) && !$_POST['formulario_id'] === 'Guardar') {
        http_response_code(403);
        exit('Pedido inválido.');
    }

    // Cada formulário envia um campo escondido "acao" a dizer qual foi submetido
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'associar') {

        // ---------------------------------------------------------------
        // Guardar associação de imagem
        // ---------------------------------------------------------------
        $codAlojamento = filter_input(INPUT_POST, 'codAlojamento', FILTER_VALIDATE_INT);
        $idSecao       = filter_input(INPUT_POST, 'idSecao', FILTER_VALIDATE_INT);
        $caminho       = is_string($_POST['caminho'] ?? null) ? trim($_POST['caminho']) : '';
        $ordem         = filter_input(INPUT_POST, 'ordem', FILTER_VALIDATE_INT);
        $destaque      = isset($_POST['destaque']) ? 1 : 0;

        if (
            !$codAlojamento || $codAlojamento < 1 ||
            !$idSecao || $idSecao < 1 ||
            $caminho === '' ||
            $ordem === false || $ordem === null || $ordem < 0
        ) {
            $mensagem = 'Preenche corretamente todos os campos.';
        } else {

            // Converter o endereço do elFinder em caminho relativo
            $path = rawurldecode((string) parse_url($caminho, PHP_URL_PATH));

            $prefixo = '/sites/projeto-alojamento/';

            if (
                !str_starts_with($path, $prefixo) ||
                str_contains($path, '..')
            ) {
                $mensagem = 'Caminho da imagem inválido.';
            } else {

                $caminhoBD = substr($path, strlen($prefixo));

                try {
                    // O alojamento e a secção escolhidos têm de existir
                    if (!assocExiste($pdo, 'alojamento', $codAlojamento)) {
                        throw new ErroAssociacao('O alojamento selecionado não existe.');
                    }

                    if (!assocExiste($pdo, 'secao', $idSecao)) {
                        throw new ErroAssociacao('A secção selecionada não existe.');
                    }

                    // Procurar imagem na biblioteca
                    $stmt = $pdo->prepare("
                        SELECT codImagem
                        FROM imagem
                        WHERE caminho = ?
                        LIMIT 1
                    ");
                    $stmt->execute([$caminhoBD]);
                    $imagem = $stmt->fetch(PDO::FETCH_ASSOC);

                    if (!$imagem) {
                        throw new ErroAssociacao('Imagem não encontrada na biblioteca.');
                    }

                    $pdo->beginTransaction();

                    // Retirar destaque anterior desta combinação alojamento + secção
                    if ($destaque) {
                        $stmt = $pdo->prepare("
                            UPDATE ImagemAssociacao
                            SET destaque = 0
                            WHERE codAlojamento = ?
                            AND idSecao = ?
                        ");
                        $stmt->execute([$codAlojamento, $idSecao]);
                    }

                    // Criar ou atualizar associação
                    $stmt = $pdo->prepare("
                        INSERT INTO ImagemAssociacao
                        (codImagem, codAlojamento, idSecao, ordem, destaque)
                        VALUES (?, ?, ?, ?, ?)

                        ON DUPLICATE KEY UPDATE
                            ordem = VALUES(ordem),
                            destaque = VALUES(destaque)
                    ");
                    $stmt->execute([
                        $imagem['codImagem'],
                        $codAlojamento,
                        $idSecao,
                        $ordem,
                        $destaque
                    ]);

                    $pdo->commit();

                    $mensagem = 'Imagem associada com sucesso!';

                } catch (Throwable $erro) {

                    if ($pdo->inTransaction()) {
                        $pdo->rollBack();
                    }

                    if ($erro instanceof ErroAssociacao) {
                        $mensagem = $erro->getMessage();
                    } else {
                        error_log($erro->getMessage());
                        $mensagem = 'Erro ao associar imagem.';
                    }
                }
            }
        }

    } elseif ($acao === 'criar_secao') {

        // ---------------------------------------------------------------
        // Adicionar secção
        // ---------------------------------------------------------------

        // Limpar o nome: sem espaços nas pontas e sem espaços repetidos
        $nome = is_string($_POST['nomeSeccao'] ?? null) ? trim($_POST['nomeSeccao']) : '';
        $nome = (string) preg_replace('/\s+/u', ' ', $nome);

        if ($nome === '' || mb_strlen($nome) > 50) {
            $mensagemSecao = 'O nome da secção é obrigatório e pode ter no máximo 50 caracteres.';
        } else {
            try {
                // Já existe uma secção com este nome?
                $stmt = $pdo->prepare("
                    SELECT 1
                    FROM Secao
                    WHERE nomeSecao = ?
                    LIMIT 1
                ");
                $stmt->execute([$nome]);

                if ($stmt->fetchColumn()) {
                    $mensagemSecao = 'Já existe uma secção com esse nome.';
                } else {
                    $stmt = $pdo->prepare("
                        INSERT INTO Secao (nomeSecao)
                        VALUES (?)
                    ");
                    $stmt->execute([$nome]);

                    $_SESSION['flash_secao'] = 'Secção criada com sucesso!';
                    header('Location: ' . basename($_SERVER['PHP_SELF']));
                    exit;
                }

            } catch (PDOException $erro) {

                // 1062 = nome repetido (caso dois pedidos cheguem ao mesmo tempo)
                if (($erro->errorInfo[1] ?? null) === 1062) {
                    $mensagemSecao = 'Já existe uma secção com esse nome.';
                } else {
                    error_log($erro->getMessage());
                    $mensagemSecao = 'Erro ao criar a secção.';
                }

            } catch (Throwable $erro) {
                error_log($erro->getMessage());
                $mensagemSecao = 'Erro ao criar a secção.';
            }
        }

    } else {
        http_response_code(400);
        exit('Pedido inválido.');
    }
}

// Listas para os campos de escolha (lidas depois do envio, para estarem atualizadas)
$listas = array_fill_keys(array_keys(ENTIDADES_ASSOCIACAO), []);

try {
    foreach (array_keys(ENTIDADES_ASSOCIACAO) as $chave) {
        $listas[$chave] = assocListar($pdo, $chave);
    }
} catch (Throwable $erro) {
    error_log($erro->getMessage());
    $mensagem = trim($mensagem . ' Erro ao carregar as listas de alojamentos e secções.');
}

// Nome do campo enviado por cada lista
$camposFormulario = [
    'alojamento' => 'codAlojamento',
    'secao'      => 'idSecao',
];
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Associar imagem</title>
    <link rel="stylesheet" href="../css/styleadmin.css">
</head>
<body>
    <div class="formularios">
        <div class="formulario">
            <h2>Associar imagem</h2>

            <p class="mensagem"><?= assocEscapar($mensagem) ?></p>

            <form method="POST" class="formulario">
                <input type="hidden" name="formulario_id" value="Guardar">

                <input type="hidden" name="csrf"
                    value="<?= assocEscapar($_SESSION['csrf']) ?>">
                <input type="hidden" name="acao" value="associar">

                <?php foreach (ENTIDADES_ASSOCIACAO as $chave => $cfg): ?>

                    <label for="<?= assocEscapar($camposFormulario[$chave]) ?>">
                        <?= assocEscapar($cfg['rotulo']) ?>:
                    </label>

                    <select name="<?= assocEscapar($camposFormulario[$chave]) ?>"
                        id="<?= assocEscapar($camposFormulario[$chave]) ?>"
                        required>

                        <option value="" disabled selected>
                            <?= $listas[$chave] ? '— Escolhe —' : '— Sem registos —' ?>
                        </option>

                        <?php foreach ($listas[$chave] as $registo): ?>
                            <option value="<?= (int) $registo['id'] ?>">
                                <?= assocEscapar($registo['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <br><br>

                <?php endforeach; ?>

                <label>Imagem:</label>

                <input type="text" name="caminho"
                    id="caminho" readonly required>

                <button type="button" onclick="abrirBiblioteca()">
                    Escolher imagem
                </button>

                <br><br>

                <img id="preview"
                    style="display:none; max-width:250px;">

                <br><br>

                <label>Ordem:</label>

                <input type="number" name="ordem"
                    value="0" min="0" required>

                <br><br>

                <label>
                    <input type="checkbox" name="destaque">
                    Imagem em destaque
                </label>

                <br><br>

                <button type="submit">Guardar associação</button>

            </form>
        </div>

        <div class="formulario">
            <h2>Adicionar secção</h2>

            <p class="mensagem"><?= assocEscapar($mensagemSecao) ?></p>

            <form method="POST" class="formulario">

                <input type="hidden" name="formulario_id" value="Guardar">

                <input type="hidden" name="csrf"
                    value="<?= assocEscapar($_SESSION['csrf']) ?>">
                <input type="hidden" name="acao" value="criar_secao">

                <label for="Nseccao">Nome da Secção</label>
                <input type="text" name="nomeSeccao" id="Nseccao"
                    maxlength="50" required>

                <br><br>
                <button type="submit">Guardar secção</button>
            </form>
        </div>
    </div>

<script>
function abrirBiblioteca() {
    window.open(
        'elFinder.php',
        'biblioteca',
        'width=1100,height=750'
    );
}

function selecionarImagem(url) {

    document.getElementById('caminho').value = url;

    const preview = document.getElementById('preview');

    preview.src = url;
    preview.style.display = 'block';
}
</script>

</body>
</html>