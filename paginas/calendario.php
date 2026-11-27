<?php
    require_once '../PHP/notificacoes.php';
    require_once '../PHP/auth.php'; 
    $semImagem     = '../imagens/imagens/sem-imagem.svg'; // usada quando falta a associação
    $pdo = ConnectDB();
    exigirLogin($pdo, 'calendario.php');
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Consulte a disponibilidade do Oasis Dune e reserve a sua estadia na Praia do Cabedelo através do Airbnb.">
    <meta property="og:title" content="Reservar | Oasis Dune">
    <meta property="og:description" content="Consulte a disponibilidade do Oasis Dune e reserve a sua estadia na Praia do Cabedelo através do Airbnb.">
    <meta property="og:type" content="website">
    <link rel="icon" href="<?= $semImagem ?>" data-secao="Logo" data-pos="0">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/styleCal.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/ampliarimgscript.js" defer></script>
    <script src="../script/calendario.js" defer></script>
    <script src="../script/imagens-dinamicas.js" defer></script>

    <!-- FullCalendar Core & Plugins via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <title>Reservar | Oasis Dune</title>
</head> 
    <body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
        <div class="fundo-fixo" data-secao="reservar - fundo" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"></div>

        <div class="conteudo-site">
            <header>
                <div class="h-esq">
                    <img src="<?= $semImagem ?>" data-secao="Logo" data-pos="2" class='logotipo' height="100px" onclick="goto('index.php')" alt="Oasis Dune – página inicial">
                </div>
                <div class="h-dir">
                    <nav class="nav-menu" id="nav-menu">
                        <a href="oalojamento.php">O Alojamento</a> 
                        <a href="experiencias.php">Experiências</a> 
                        <a href="contactos.php">Contactos</a>
                        <a href="calendario.php">Reservar</a>
                        <?php
                            admPage($pdo); 
                            paginaLogin($pdo);
                        ?>
                    </nav>
                    <a href="https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1792669220_P3AOW4b4XqChBI5x" class="airbnb_btn">Airbnb</a>
                    <button class="menu-mobile-btn" id="btn-menu" aria-label="Abrir Menu">
                    </button>
                </div>
            </header>

            <div class="titulo">
                <div class="conteudo-titulo">
                    <h1>Calendário</h1>
                </div> 
            </div>
            
            <div class="conteudoB">
                <h1>Calendário de Disponibilidade</h1>
                <div class="areaCalendarioReserva">
                    <section class="seccao-calendario">                    
                        <div id="meu-calendario-pc"></div>
                    </section>

                    <section class="seccao-calendario">                    
                        <div id="meu-calendario-tll"></div>
                    </section> 

                    <form id="bookingForm" method="post">
                        <h1>Reserva</h1>
                        <input type="date" name="checkin" style="display: none;">
                        <input type="date" name="checkout" style="display: none;">
                        <div>
                            <div>
                                <h3>Adultos:</h3>
                                <h3>Crianças:</h3>
                                <h3>Bebés:</h3>
                            </div>
                            <div style="gap: 4px;">
                                <input type="number" name="numberOfAdults" value="1" min="1" required>
                                <input type="number" name="numberOfChildren" value="0" min="0" required>
                                <input type="number" name="numberOfInfants" value="0" min="0" required>
                            </div>
                        </div>
                        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                        <div class="cf-turnstile" data-sitekey="<?php echo CAPTCHA_PUBLIC; ?>" data-appearance="interaction-only"></div>
                        <?php 

                            $valor = SELECT($pdo, 'SELECT precoNoite, limitePessoas FROM Alojamento WHERE codAlojamento = :cod', [':cod' => $codAlojamento]);
                            $valor = $valor[0];

                            if(is_admin($pdo)){
                                echo "<input type='submit' value='Bloquear Datas' class='block' style='margin-bottom: 10px; background-color: gray;' >";
                            }else{ 
                                echo '<input type="submit" class="btn-gradient" value="Fazer pedido de reserva">';
                            }


                            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                                
                                $agora = new DateTime();

                                // Formata para o formato padrão do MySQL (Ano-Mês-Dia Horas:Minutos:Segundos)
                                $dataHora = $agora->format('Y-m-d H:i:s');

                                $checkinStr = $_POST['checkin'];
                                $checkoutStr = $_POST['checkout'];

                                // Cria objetos DateTime a partir das strings das datas
                                $checkin = new DateTime($checkinStr);
                                $checkout = new DateTime($checkoutStr);
    
                                if($_POST['checkin'] == '' or $_POST['checkout'] == ''){
                                    $erro = 'Precisas de preencher uma data.';
                                }elseif($_POST['numberOfAdults'] < 1){
                                    $erro = 'Precisas de pelo menos um adulto.';
                                }elseif($_POST['numberOfChildren'] < 0 or $_POST['numberOfInfants'] < 0){
                                    $erro = 'não podem haver valores menores que 0.';
                                }elseif($_POST['numberOfChildren'] + $_POST['numberOfInfants'] + $_POST['numberOfAdults'] > $valor['limitePessoas']){
                                    $erro = "limite de pessoas ultrapassado. (".$valor['limitePessoas'].")";
                                }elseif($checkin >= $checkout){
                                    $erro = "A data de check-in tem de ser anterior à data de check-out.";
                                }elseif (!validarCaptcha($_POST['cf-turnstile-response'] ?? null)) {
                                    $erro = 'Verificação CAPTCHA falhada. Tente novamente.';
                                }
                                if(!empty($erro)){
                                    notificacao($erro);

                                }else{
                                    if(is_admin($pdo)){
                                        $insert = INSERT($pdo, 'INSERT INTO reserva(checkIn, checkOut, codAlojamento, dataReserva, codUtilizador, estadoReserva) 
                                        VALUES(:ckIn, :ckOut, :codAloj, :hoje, :codUtil, "Bloqueado")', 
                                        [':ckIn' => $_POST['checkin'], 
                                        ':ckOut' => $_POST['checkout'],
                                        ':codAloj' => $codAlojamento,
                                        ':hoje' => $dataHora,
                                        ':codUtil' => $_SESSION['codUtilizador'],
                                        ]
                                        );
                                    }else{
                                        //Criar o Pedido de Reserva.
                                        //-------------------------------------------                               

                                        // Calcula a diferença entre as duas datas
                                        $diferenca = $checkin->diff($checkout);
                                        // Obtém o número de dias
                                        $numeroDias = $diferenca->days;
                                        $preco = $numeroDias *  $valor['precoNoite'];
                                        $insert = INSERT($pdo, 'INSERT INTO reserva(checkIn, checkOut, codAlojamento, codUtilizador, dataReserva, numeroAdultos, numeroBebes, numeroCriancas, precoTotal) VALUES(:ckIn, :ckOut, :codAloj, :codUtil, :hoje, :nAdultos, :nBebes, :nCriancas, :preco)', [':ckIn' => $_POST['checkin'], ':ckOut' => $_POST['checkout'],':codAloj' => $codAlojamento, ':codUtil' => $_SESSION['codUtilizador'],':hoje' => $dataHora,':nAdultos' => $_POST['numberOfAdults'] ,':nBebes' => $_POST['numberOfInfants'],':nCriancas' => $_POST['numberOfChildren'], ':preco' => $preco]);
                                        if($insert){
                                            notificacao('Pedido enviado com sucesso.', "sucesso");
                                        }
                                    }
                                }
                            }
                ?>       
                    </form>
                </div>
            </div>
            
            

        </div>
        <footer>
            <div>
                <img src="<?= $semImagem ?>" data-secao='Logo' data-pos="2" class='logotipo' height="100px" onclick="goto('index.php')">
                <p>+351 935 334 117</p> 
                <p>info.oasisdune@gmail.com</p> 
                <p>Rua Fernão Martins da Costa, 4935-158 Viana do Castelo</p> 
                <p>Registro nº 159124/AL</p>
            </div>
            <div>
                <b>Sobre o alojamento</b>
                <p>Oasis Dune é uma moradia de luxo junto à Praia do Cabedelo, com acesso direto às dunas e vista para o mar. Ideal para até 6 pessoas, oferece conforto, jacuzzi, terraço, cozinha equipada e espaços de lazer, numa localização perfeita para relaxar e desfrutar de atividades como windsurf, ciclismo e ténis.</p>
            </div>
            <div>
                <h2>Alojamento</h2>
                <a href="oalojamento.php">O Alojamento</a> 
                <a href="experiencias.php">Experiências</a> 
                <a href="contactos.php">Contactos</a>
                <a href="calendario.php">Reservar</a>
                <a href="https://www.livroreclamacoes.pt/" target="_blank" rel="noopener">Livro de Reclamações</a><br>
                <a href="https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1792669220_P3AOW4b4XqChBI5x" class="airbnb_btn">Mais no Airbnb</a>
            </div>
        </footer>
    </body>
</html>