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
  <script>
  function atualizarRelogio() {
      const agora = new Date();
      
      // Formata os números para terem sempre 2 dígitos (ex: 09 em vez de 9)
      const ano = agora.getFullYear();
      const mes = String(agora.getMonth() + 1).padStart(2, '0');
      const dia = String(agora.getDate()).padStart(2, '0');
      const horas = String(agora.getHours()).padStart(2, '0');
      const minutos = String(agora.getMinutes()).padStart(2, '0');
      const segundos = String(agora.getSeconds()).padStart(2, '0');

      // Junta no formato igual ao do MySQL (YYYY-MM-DD HH:MM:SS)
      const formatoMySQL = `${dia}-${mes}-${ano} ${horas}:${minutos}:${segundos}`;

      // Atualiza o texto dentro do span
      document.getElementById('relogio').innerText = formatoMySQL;
  }

  // Executa a função a cada 1 segundo (1000 milissegundos)
  setInterval(atualizarRelogio, 1000);
  </script>


<body>
  <header>
  <a href="index.php">index</a>
  <a href="paginaadmin.php?secao=reservas">Gerir Reservas</a>
  <a href="paginaadmin.php?secao=imagens">Gerir imagens</a>
  <a href="paginaadmin.php?secao=elfinder">Gerir ElFinder</a>
  <a href="paginaadmin.php?secao=msg">Gerir Mensagens</a>
  <a href="paginaadmin.php?secao=users">Gerir Utilizadores</a>

  <div class="perfil">
    <a href="../PHP/logout.php"> Sair</a>
    <p><?php echo $_SESSION['nomeUtilizador'];?> [<?php echo SELECT($pdo, 'SELECT paisOrigem from utilizador where codUtilizador = :cod', [':cod' => $_SESSION['codUtilizador']])[0]['paisOrigem'] ?>]</p>
  </div>
  </header>


  <div class="pagina">
    <?php
      // Define uma secção padrão caso nenhuma seja escolhida
      $secao = '';

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

          case 'msg':
            include_once('../paginas/msg.php') ;
            break;
              
          default:
              include_once('../PHP/controlar-reservas.php') ;
              include_once('../paginas/msg.php') ;
              break;
      }

    ?>
    <a href="../PHP/responderBan.php?request=1&cod=18">Vapo nele</a>
    <a href="../PHP/responderBan.php?request=0&cod=18">desVapo nele</a>
  </div>
  <footer>
      <span id="relogio">
        <?php
        $agora = new DateTime();
        echo $agora->format('d-m-Y H:i:s');
        ?>
      </span>
  </footer>
</body>
</html>