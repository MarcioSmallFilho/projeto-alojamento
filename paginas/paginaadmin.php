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
          *
      FROM Reserva
      WHERE checkOut > :hoje
      ORDER BY dataReserva DESC
      LIMIT 3
  ";

  $stmt = $pdo->prepare($sql);
  $stmt->execute(['hoje' => $hoje]);

  $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  foreach($reservas as $reserva){
    echo '<div class="reserva '.$reserva['estadoReserva'].'" style="display: flex;">';
    foreach($reserva as $chave => $valor){
      echo $chave . ': ' . $valor . ' ';
    }
    echo '</div>';
  }
  ?>
  </div>
</body>
</html>