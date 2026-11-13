<?php
/*
 * Endpoint de leitura (público): devolve as imagens associadas a um alojamento,
 * agrupadas pelas secções pedidas.
 *
 * Pedido:   GET imagens-secao.php?alojamento=1&secoes[]=Index - Galeria&secoes[]=Quarto I
 * Resposta: {"ok":true,"secoes":{"Index - Galeria":[{"caminho":"imagens/a.png","ordem":0,"destaque":true}, ...],
 *                                "Quarto I":[]}}
 *
 * Em cada secção a lista vem já ordenada: primeiro a imagem em destaque,
 * depois por "ordem". Uma secção sem associações devolve uma lista vazia.
 * O "caminho" é o guardado na tabela imagem (relativo à pasta do projeto).
 */
require_once __DIR__ . '/auth.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

const MAX_SECOES_POR_PEDIDO = 30;

function responder(int $codigo, array $dados): never
{
    http_response_code($codigo);
    echo json_encode($dados, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    responder(405, ['ok' => false]);
}

$codAlojamento = filter_input(INPUT_GET, 'alojamento', FILTER_VALIDATE_INT);
$secoes        = $_GET['secoes'] ?? [];

if (!$codAlojamento || $codAlojamento < 1 || !is_array($secoes)) {
    responder(400, ['ok' => false]);
}

// Nomes de secção limpos e sem repetições (a comparação ignora maiúsculas)
$pedidas = [];

foreach ($secoes as $nome) {
    if (!is_string($nome)) {
        continue;
    }

    $nome = trim($nome);

    if ($nome === '' || mb_strlen($nome) > 50) {
        continue;
    }

    $pedidas[mb_strtolower($nome)] = $nome;
}

if (!$pedidas || count($pedidas) > MAX_SECOES_POR_PEDIDO) {
    responder(400, ['ok' => false]);
}

try {
    $pdo = ConnectDB();

    // Um marcador de posição por nome de secção; os valores vão separados (consulta preparada)
    $marcadores = implode(',', array_fill(0, count($pedidas), '?'));

    $stmt = $pdo->prepare("
        SELECT s.nomeSecao, i.caminho, a.ordem, a.destaque
        FROM ImagemAssociacao a
        INNER JOIN Secao  s ON s.idSecao   = a.idSecao
        INNER JOIN imagem i ON i.codImagem = a.codImagem
        WHERE a.codAlojamento = ?
          AND s.nomeSecao IN ($marcadores)
        ORDER BY s.nomeSecao, a.destaque DESC, a.ordem ASC, a.idAssociacao ASC
    ");
    $stmt->execute([$codAlojamento, ...array_values($pedidas)]);

    // Começa com todas as secções pedidas vazias
    $resultado = array_fill_keys(array_values($pedidas), []);

    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $linha) {

        $chave = $pedidas[mb_strtolower($linha['nomeSecao'])] ?? null;
        $caminho = (string) $linha['caminho'];

        if (
            $chave === null ||
            $caminho === '' ||
            str_contains($caminho, '..') ||
            str_starts_with($caminho, '/') ||
            preg_match('#^[a-z][a-z0-9+.\-]*:#i', $caminho)
        ) {
            continue;
        }

        $resultado[$chave][] = [
            'caminho'  => $caminho,
            'ordem'    => (int) $linha['ordem'],
            'destaque' => (bool) $linha['destaque'],
        ];
    }

    responder(200, ['ok' => true, 'secoes' => $resultado]);

} catch (Throwable $erro) {
    error_log($erro->getMessage());
    responder(500, ['ok' => false]);
}
