<?php
  require_once '../PHP/auth.php';
  include '../enviar.php';
  $pdo = ConnectDB();
  exigirLogin($pdo);
  if (!is_admin($pdo)){header('Location: ' . URL_INICIO);}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= $semImagem ?>" data-secao="Logo" data-pos="0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/styleadmin.css">
    <script src="../script/imagens-dinamicas.js" defer></script>
    <script src="../script/script.js"></script>
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


<body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
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
    require_once '../PHP/notificacoes.php';
    if(isset($_SESSION['erro'])){
      notificacao($_SESSION['erro'], "erro");
      unset($_SESSION['erro']);
    }
      // Define uma secção padrão caso nenhuma seja escolhida
      $secao = '';

      if (isset($_GET['secao'])) {
          $secao = $_GET['secao'];
      }

      // Mostra o conteúdo consoante a escolha do navbar
      switch ($secao) {
          case 'reservas':
              require_once('../PHP/controlar-reservas.php') ;
              break;
              
          case 'imagens':
              require_once('../paginas/guardar-associacao.php') ;
              break;
              
          case 'elfinder':
              require_once('../paginas/elFinder.php') ;
              break;

          case 'msg': 
            require_once('../paginas/msg.php') ;
            break;
              
          case 'users':
            require_once('../PHP/controlar-utilizadores.php');
            break;

          default:
              require_once('../PHP/controlar-reservas.php') ;
              require_once('../paginas/msg.php') ;
              break; 
      }

    ?>
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