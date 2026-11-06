<?php
// Os erros do PHP não podem aparecer na resposta, senão o elFinder deixa de receber JSON válido.
ini_set('display_errors', '0');
error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/erro-php.log');

require_once 'C:/xampp/database.php';                    // define ConnectDB()
require_once __DIR__ . '/../elFinder/php/autoload.php';

// Controlo de acesso: esconde ficheiros que começam por ponto (ex.: .htaccess)
function access($attr, $path, $data, $volume, $isDir, $relpath) {
    $basename = basename($path);
    return $basename[0] === '.' && strlen($relpath) !== 1
        ? !($attr == 'read' || $attr == 'write')
        : null;
}

// Depois de cada upload, regista a imagem na tabela "imagem"
function registarImagem($cmd, &$result, $args, $elfinder, $volume) {
    try {
        $pdo  = ConnectDB();   // função definida no database.php
        $stmt = $pdo->prepare('INSERT INTO imagem (caminho, nomeOriginal) VALUES (?, ?)');
        $raiz = str_replace('\\', '/', dirname(__DIR__)) . '/';

        foreach ($result['added'] as $ficheiro) {
            if (strpos($ficheiro['mime'], 'directory') !== false) continue; // ignora pastas

            $completo = str_replace('\\', '/', $elfinder->realpath($ficheiro['hash']));
            $relativo = str_replace($raiz, '', $completo);   // ex.: imagens/quartos/foto.jpg

            $stmt->execute([$relativo, $ficheiro['name']]);
        }
    } catch (Throwable $e) {
        file_put_contents(__DIR__ . '/erro.log', date('c') . ' ' . $e->getMessage() . "\n", FILE_APPEND);
    }
}

$opts = [
    'bind' => [
        'upload' => ['registarImagem'],
    ],
    'roots' => [
        [
            'driver'        => 'LocalFileSystem',
            'path'          => dirname(__DIR__) . '/imagens/',
            'URL'           => '/sites/projeto-alojamento/imagens/',
            'uploadDeny'    => ['all'],
            'uploadAllow'   => ['image'],
            'uploadOrder'   => ['deny', 'allow'],
            'accessControl' => 'access',
        ],
    ],
];

$connector = new elFinderConnector(new elFinder($opts));
$connector->run();