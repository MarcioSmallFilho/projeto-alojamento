<?php
    $semImagem     = '../imagens/imagens/sem-imagem.svg'; // usada quando falta a associação
    require_once "../PHP/auth.php";
    $pdo = ConnectDB();
    exigirLogin($pdo);
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Entre em contacto com o Oasis Dune, alojamento na Praia do Cabedelo, Viana do Castelo.">
    <meta property="og:title" content="Contactos | Oasis Dune">
    <meta property="og:description" content="Entre em contacto com o Oasis Dune, alojamento na Praia do Cabedelo, Viana do Castelo.">
    <meta property="og:type" content="website">
    <link rel="icon" href="<?= $semImagem ?>" data-secao="Logo" data-pos="0">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/styleCont.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/imagens-dinamicas.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Contactos | Oasis Dune</title>
</head>
<body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
    <div class="fundo-fixo" data-secao="contacto - fundo" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"></div>

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
                <h1>Contactos</h1>
            </div>
        </div>

        <div class="conteudoA">
            <h1>Formas de contacto</h1>
            <div class="areaA">
                <div><i class="fa-solid fa-phone"></i>
                <p>Atendimento 24 horas</p></div>
                <div><i class="fa-solid fa-envelope"></i>
                <p>Lemos frequentemente o email</p></div>
                <div><i class="fa-solid fa-location-dot"></i>
                <p>Estamos disponíveis presencialmente</p></div>
            </div>
        </div>

        
        <div class="conteudoB">
            <div class="areaB">
                <div class="grega1">
                    <div class="cartinha">
                        <i class="fa-solid fa-phone"></i>
                        <div><h3>Telefone</h3>
                        <p>+351 935 334 117</p></div>
                    </div>
                    <div class="cartinha">
                        <i class="fa-solid fa-envelope"></i>
                        <div><h3>Email</h3>
                        <p>info.oasisdune@gmail.com</p></div>
                    </div>
                    <div class="cartinha">
                        <i class="fa-solid fa-location-dot"></i>
                        <div><h3>Morada</h3>
                        <p>Rua Fernão Martins da Costa, 4935-158 Viana do Castelo</p></div>
                    </div>
                </div>

                <form method="POST">
                    <h1 class="tituloMsg">Envie-nos uma mensagem</h1>
                    <textarea placeholder="Mensagem" name='mensagem'></textarea>
                    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
                    <div class="cf-turnstile" data-sitekey="<?php echo CAPTCHA_PUBLIC; ?>" data-appearance="interaction-only"></div>
                    <?php if(utilizadorAutenticado($pdo)){echo "<input type=\"submit\" value=\"Enviar Mensagem\" class=\"butaomsg\">";}
                          else{echo"<button class=\"butaomsg\" type=\"button\" onclick=\"goto('login.php')\">Login/Registrar</button>";}
                        if (isset($_POST["mensagem"])) {
                            $agora = new DateTime();
                            // Formata para o formato padrão do MySQL (Ano-Mês-Dia Horas:Minutos:Segundos)
                            $dataHora = $agora->format('Y-m-d H:i:s');
                            $statusban = SELECT($pdo, "SELECT ban FROM utilizador WHERE codUtilizador = :codUtilizador;", [":codUtilizador" => $_SESSION["codUtilizador"]]);
                            $statusban = (bool) $statusban[0]['ban'];

                            require_once "../PHP/notificacoes.php";

                            if ($statusban) { notificacao("Você está banido");}
                            elseif (!validarCaptcha($_POST['cf-turnstile-response'] ?? null)) {notificacao('Ocorreu um erro, tente novamente mais tarde.');}
                            elseif (!(strlen($_POST['mensagem']) < 201)) {notificacao("A mensagem é muito longa, maximo 200 characteres");}
                            elseif (empty($_POST['mensagem'])) { notificacao("Precisa de conteudo para mandar a mensagem");}
                            elseif (empty(trim($_POST['mensagem']))) { notificacao("A mensagem não pode conter só espaços");}
                            elseif (preg_match_all('/[^a-zA-ZáàâãéêíóôõúüçÁÀÂÃÉÊÍÓÔÕÚÜÇ ]/u', $_POST['mensagem'], $m)) {$erros = array_unique($m[0]); notificacao("A mensagem não pode conter " . implode(', ', $erros));}
                            else {
                                INSERT(
                                    $pdo,
                                    "INSERT INTO contacto (mensagem, codUtilizador, codAlojamento, dataEnvio)
                                    VALUES (:msg, :codUtilizador, :codAlojamento, :dataEnvio)",
                                    [
                                        ':msg'           => trim($_POST['mensagem']),
                                        ':codUtilizador' => $_SESSION['codUtilizador'],
                                        ':codAlojamento' => $codAlojamento,
                                        ':dataEnvio'     => $dataHora,
                                    ]
                                );
                            }
                        }
                ?>
                </form>                
            </div>
        </div>

        <div class="areaReservar">
            <h1>Resolvemos as suas dúvidas? ...</h1>
            <input type="button" value="Reserve agora!" onclick="goto('calendario.php')">
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
    </div>
</body>
</html>