<form action="" method="post" id="filtro">
    <input type="hidden" name="form_id" value="filtro">
    <select name="tipoReserva" id="">
    <option value="" disabled selected>Filtro de reserva</option>
    <option value="Todos">Todos</option>
    <option value="Pendente">Pendente</option>
    <option value="Aceite">Aceite</option>
    <option value="Recusada">Recusada</option>
  </select>
  <input type="submit" value="Filtrar">
</form>
<?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_id']) && $_POST['form_id'] === 'filtro') {
    $filtroReserva = 'Todos';
    if(isset($_POST['tipoReserva'])){
      $filtroReserva = $_POST['tipoReserva'];
    }
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
        WHERE checkOut > :hoje and reserva.codAlojamento = :cod AND (estadoReserva = :state OR :state = 'Todos')
        ORDER BY dataReserva DESC
        LIMIT 5
    ";
    $parametros = ['codReserva', 'nomeUtilizador', 'nomeAlojamento', 'checkIn', 'checkOut', 'dataReserva', 'precoTotal', 'estadoReserva', 'nPessoas' ];
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['hoje' => $hoje, ':cod' => $codAlojamento, ':state' => $filtroReserva]);

    $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(!empty($reservas)){
      foreach($reservas as $reserva){
        $reserva['nPessoas'] = $reserva['numeroAdultos'] + $reserva['numeroCriancas'] + $reserva['numeroBebes'];
        echo '<div class="reserva '.$reserva['estadoReserva'].'" style="display: flex;">';
        foreach($reserva as $chave => $valor){
          if(in_array($chave, $parametros)) {
            //adicionar o valor no card
            echo $valor. ' |';
          } 
        }
        if($reserva['estadoReserva'] == 'Pendente'){
          // PAUSA para manutenção após identificação do alojamento pelo GET
          $reservasAceites = SELECT($pdo, 'SELECT checkIn, checkOut FROM reserva WHERE estadoReserva = "Aceite" AND codAlojamento = '.$codAlojamento.'');  
          
          $Sobreposto = false; // Inicializa a falso antes de verificar
          
          foreach($reservasAceites as $reservaAceite){
              // Condição correta de sobreposição de datas em alojamentos:
              if ($reserva['checkIn'] < $reservaAceite['checkOut'] && $reserva['checkOut'] > $reservaAceite['checkIn']) {
                  $Sobreposto = true;
                  break; // Já encontrámos uma sobreposição, não precisamos de continuar a verificar o resto
              }
          }
          
          if($Sobreposto){
              echo "<i style='text-decoration: underline;'>Bloqueado:</i><a href='../PHP/responderPedido.php?request="."Recusada"."&cod=".$reserva['codReserva']."'>Recusar</a>";
          } else {
              echo "<a href='../PHP/responderPedido.php?request="."Aceite"."&cod=".$reserva['codReserva']."'>Aceitar</a> / <a href='../PHP/responderPedido.php?request="."Recusada"."&cod=".$reserva['codReserva']."'>Recusar</a>";
          }
      }
        echo '</div>';
      }
    }
  }
?>