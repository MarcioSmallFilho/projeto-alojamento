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
  <a href="elFinder.php">elFinder</a>
  <a href="index.php">index</a>
  <div class="perfil">
    <a href="../PHP/logout.php"> Sair</a>
    <p><?php echo $_SESSION['nomeUtilizador'];?> [<?php echo SELECT($pdo, 'SELECT paisOrigem from utilizador where codUtilizador = :cod', [':cod' => $_SESSION['codUtilizador']])[0]['paisOrigem'] ?>]</p>
  </div>
  </header>
  <div class="pagina">
  <?php

  // ==========================================
  // 2. CONSULTAR AS RESERVAS
  // ==========================================

  $hoje = date('Y-m-d');

  $sql = "
      SELECT
          codReserva, 
          reserva.codUtilizador, 
          utilizador.nomeUtilizador, 
          reserva.codAlojamento, 
          alojamento.nomeAlojamento, 
          checkIn, 
          checkOut, 
          dataReserva, 
          precoTotal, 
          estadoReserva, 
          numeroAdultos, 
          numeroCriancas, 
          numeroBebes
      FROM Reserva inner join utilizador
      ON reserva.codUtilizador = utilizador.codUtilizador
      inner join alojamento 
      ON reserva.codAlojamento = alojamento.codAlojamento
      WHERE checkOut > :hoje
      ORDER BY dataReserva DESC
      LIMIT 7
  ";
  $parametros = ['codReserva', 'nomeUtilizador', 'nomeAlojamento', 'checkIn', 'checkOut', 'dataReserva', 'precoTotal', 'estadoReserva', 'numeroAdultos', 'numeroCriancas', 'numeroBebes' ];
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['hoje' => $hoje]);

  $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($reservas as $reserva){
    echo '<div class="reserva '.$reserva['estadoReserva'].'" style="display: flex;">';
    foreach($reserva as $chave => $valor){
      if(in_array($chave, $parametros)) {
        //adicionar o valor no card
        echo $valor. ' | ';
      } 
    }
    if($reserva['estadoReserva'] == 'Pendente'){
        echo "<a href='../PHP/responderPedido.php?request="."Aceite"."&cod=".$reserva['codReserva']."'>Aceitar</a> / <a href='../PHP/responderPedido.php?request="."Recusada"."&cod=".$reserva['codReserva']."'>Recusar</a>";
    } 
    echo '</div>';
  }
  ?>
  </div>
</body>
</html>