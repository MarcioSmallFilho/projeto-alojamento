<?php
require_once '../PHP/auth.php';
$pdo = ConnectDB();
if (realpath($_SERVER['SCRIPT_FILENAME']) === realpath(__FILE__)) {
  exigirLogin($pdo);

  if (!is_admin($pdo)){
      http_response_code(403);
      exit('Acesso negado.');
  }
}
?>
<form action="" method="post" id="filtro">
      <input type="hidden" name="form_id" value="filtro">
      <select name="tipoReserva" id="">
        <option value="" disabled selected>Filtro de reserva</option>
        <option value="Todos">Todos</option>
        <option value="Pendente">Pendente</option>
        <option value="Aceite">Aceite</option>
        <option value="Recusada">Recusada</option>
        <option value="Bloqueado">Bloqueado</option>
      </select>
      <input type="number" name="nReservas" min="1" value="<?php echo htmlspecialchars($_POST['nReservas'] ?? '5', ENT_QUOTES, 'UTF-8'); ?>">
      <input type="submit" value="Filtrar">
    </form>
  <div class="reservas">
  <?php
      $limite = 5; // valor por defeito
      if (isset($_POST['nReservas']) && is_numeric($_POST['nReservas']) && (int)$_POST['nReservas'] > 0) {
        $limite = (int)$_POST['nReservas'];
      }
      
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
          LIMIT $limite
      ";
      $parametros = ['codReserva', 'nomeUtilizador', 'nomeAlojamento', 'checkIn', 'checkOut', 'dataReserva', 'precoTotal', 'estadoReserva', 'nPessoas' ];
      $stmt = $pdo->prepare($sql);
      $stmt->execute(['hoje' => $hoje, ':cod' => $codAlojamento, ':state' => $filtroReserva]);
      $reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

      //Se existem reservas
      if(!empty($reservas)){

        foreach($reservas as $reserva){

          $reserva['nPessoas'] = $reserva['numeroAdultos'] + $reserva['numeroCriancas'] + $reserva['numeroBebes'];
          echo '<div class="reserva '.$reserva['estadoReserva'].'" style="display: flex;"><p style="margin: 0px;">';
          foreach($reserva as $chave => $valor){

            if(in_array($chave, $parametros)) {
              //adicionar o valor no card
              echo $valor. '|';
            }
          }

          //Se a reserva estiver em pendente
          if($reserva['estadoReserva'] == 'Pendente'){

            $reservasAceites = SELECT($pdo, 'SELECT checkIn, checkOut FROM reserva WHERE (estadoReserva = "Aceite" OR estadoReserva = "Bloqueado")  AND codAlojamento = '.$codAlojamento.'');  
            
            $Sobreposto = false;
            
            foreach($reservasAceites as $reservaAceite){
                // Condição correta de sobreposição de datas em alojamentos
                if ($reserva['checkIn'] < $reservaAceite['checkOut'] && $reserva['checkOut'] > $reservaAceite['checkIn']) {
                    $Sobreposto = true;
                    break;
                }
            }
            
            if($Sobreposto){
                echo "<i style='text-decoration: underline;'>Sobreposto:</i><a href='../PHP/responderPedido.php?request="."Recusada"."&cod=".$reserva['codReserva']."'>Recusar</a>";
            }else{
                echo "<a href='../PHP/responderPedido.php?request="."Aceite"."&cod=".$reserva['codReserva']."'>Aceitar</a> / <a href='../PHP/responderPedido.php?request="."Recusada"."&cod=".$reserva['codReserva']."'>Recusar</a>";
            }
            
          }elseif($reserva['estadoReserva'] == 'Bloqueado'){
            echo "<i style='text-decoration: underline;'>Bloqueado:</i> <a href='../PHP/responderPedido.php?request="."Cancelada"."&cod=".$reserva['codReserva']."'>Cancelar</a>";
          }elseif($reserva['estadoReserva'] == 'Aceite'){
            echo "<a href='../PHP/responderPedido.php?request="."Pendente"."&cod=".$reserva['codReserva']."'>Cancelar</a>";
          }elseif($reserva['estadoReserva'] == 'Recusada'){
            echo "<a href='../PHP/responderPedido.php?request="."Pendente"."&cod=".$reserva['codReserva']."'>Cancelar</a>";
          }
          echo '</p></div>';
        }
      }else{
        echo '<div class="reserva Pendente" style="display: flex;"><p style="margin: 0px;"> Sem Reservas... </p></div>';
      }
  ?>
  </div>
  
  
  

  </div>