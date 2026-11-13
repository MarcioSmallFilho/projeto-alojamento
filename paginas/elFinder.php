<?php
  require_once '../PHP/auth.php';
  $pdo = ConnectDB();
  exigirLogin($pdo);
  if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css">
  <link rel="stylesheet" href="../elFinder/css/elfinder.min.css">
  <link rel="stylesheet" href="../elFinder/css/theme.css">

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
  <script src="../elFinder/js/elfinder.min.js"></script>
  <link rel="stylesheet" href="../css/styleadmin.css">
</head>
<body>
  <header>
    <a href="paginaadmin.php">Pagina admin</a>
    <a href="index.php">index</a>
  </header>
  <div id="elfinder"></div>

  <script>
    $(function () {

        $('#elfinder').elfinder({
            url: '../PHP/conector.php',
            lang: 'pt_BR',
            resizable: false,

            getFileCallback: function(file, fm) {

                // Aceitar tanto uma string como um objeto
                let url;

                if (typeof file === 'string') {
                    url = file;
                } else if (file && file.url) {
                    url = file.url;
                } else if (file && file.hash) {
                    url = fm.url(file.hash);
                }

                // Verificar se recebemos uma imagem
                if (!url) {
                    console.error('Imagem recebida:', file);
                    alert('Não foi possível obter o caminho da imagem.');
                    return;
                }

                // Devolver ao formulário
                if (window.opener && !window.opener.closed) {

                    window.opener.selecionarImagem(url);

                    window.close();

                } else {
                    alert('Não foi possível comunicar com o formulário.');
                }
            },

            commandsOptions: {
                getfile: {
                    onlyURL: true,
                    multiple: false
                }
            }
        });

    });
  </script>
  <?php
    $resultado = procurarImagem($pdo);
    $arquvioJSON = fopen('../Imagens.json', 'w');
    fwrite($arquvioJSON, json_encode($resultado));
    fclose($arquvioJSON);
  ?>
</body>
</html>