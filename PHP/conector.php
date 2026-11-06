<?php
    require_once __DIR__ . '/auth.php';
    $pdo = ConnectDB();
    if (!utilizadorAutenticado($pdo)) {
        http_response_code(403);
        exit(json_encode(['error' => 'Não autorizado']));
    }
    require __DIR__ . '/../elFinder/php/autoload.php';


    // Se baixou manualmente: require 'php/autoload.php';

    // Função de controle de acesso (permissões)
    function access($attr, $path, $data, $volume, $isDir, $relpath) {
        $basename = basename($path);
        // Oculta arquivos que começam com ponto (ex: .htaccess)
        return $basename[0] === '.' && strlen($relpath) !== 1
            ? !($attr == 'read' || $attr == 'write')
            : null;
    }

    $opts = [
        'roots' => [
            [
                'driver'        => 'LocalFileSystem',
                'path'          => dirname(__DIR__) . '/imagens/',
                'URL'           => '/sites/projeto-alojamento/imagens/',
                'uploadDeny'    => ['all'],
                'uploadAllow'   => ['image', 'application/pdf'],
                'uploadOrder'   => ['deny', 'allow'],
                'accessControl' => 'access',
            ],
        ],
    ];

    $connector = new elFinderConnector(new elFinder($opts));
    $connector->run();
?>