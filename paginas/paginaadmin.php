<?php
  require_once '../PHP/auth.php';
  $pdo = ConnectDB();
  exigirLogin($pdo);
  if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}


  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/styleadmin.css">
</head>
<body>
  <header>
  <a href="index.php">index</a>
  <a href="paginaadmin.php?secao=reservas">Gerir Reservas</a>
  <a href="paginaadmin.php?secao=imagens">Gerir imagens</a>
  <a href="paginaadmin.php?secao=elfinder">Gerir ElFinder</a>

  <div class="perfil">
    <a href="../PHP/logout.php"> Sair</a>
    <p><?php echo $_SESSION['nomeUtilizador'];?> [<?php echo SELECT($pdo, 'SELECT paisOrigem from utilizador where codUtilizador = :cod', [':cod' => $_SESSION['codUtilizador']])[0]['paisOrigem'] ?>]</p>
  </div>
  </header>
  <div class="pagina">
    <?php
      // Define uma secção padrão caso nenhuma seja escolhida
      $secao = 'reservas';

      if (isset($_GET['secao'])) {
          $secao = $_GET['secao'];
      }

      // Mostra o conteúdo consoante a escolha do navbar
      switch ($secao) {
          case 'reservas':
              include_once('../PHP/controlar-reservas.php') ;
              break;
              
          case 'imagens':
              include_once('../paginas/guardar-associacao.php') ;
              break;
              
          case 'elfinder':
              include_once('../paginas/elFinder.php') ;
              break;
              
          default:
              echo "<h2>Página não encontrada.</h2>";
              break;
      }
    ?>
  </div>
  <footer>
  </footer>
</body>
</html>