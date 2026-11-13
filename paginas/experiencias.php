<?php
    $codAlojamento = 2; // codAlojamento do Oasis Dune na tabela alojamento
    $semImagem     = '../imagens/imagens/sem-imagem.svg'; // usada quando falta a associação
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Descubra o que fazer perto do Oasis Dune: Praia do Cabedelo, terminal fluvial, Capela de Santa Catarina e restaurantes.">
    <meta property="og:title" content="Experiências | Oasis Dune">
    <meta property="og:description" content="Descubra o que fazer perto do Oasis Dune: Praia do Cabedelo, terminal fluvial, Capela de Santa Catarina e restaurantes.">
    <meta property="og:type" content="website">
    <link rel="icon" href="<?= $semImagem ?>" data-secao="Logo" data-pos="0">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/styleExp.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/imagens-dinamicas.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Experiências | Oasis Dune</title>
</head>
<body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
    
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
                        require_once "../PHP/auth.php";
                        $pdo = ConnectDB();
                        admPage($pdo);
                        paginaLogin($pdo);
                    ?>
                </nav>
                <a href="https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1792669220_P3AOW4b4XqChBI5x" class="airbnb_btn">Airbnb</a>
                <button class="menu-mobile-btn" id="btn-menu" aria-label="Abrir Menu">
                    
                </button>
            </div>
        </header>
    <div class="topo" data-secao="experiencias - fundo" data-fundo data-pos="0" style="background-image: url('<?= $semImagem ?>');">
        <div class="titulo">
                <h1>SINTA, DESCUBRA E RELAXE</h1>
        </div>
    </div>
    <div class="icones">
        <div>
            <i class="fa-solid fa-umbrella-beach" onclick="godown('areaA')"></i>
            <h4>Proximidades</h4>
        </div>
        <div>
            <i class="fa-solid fa-utensils" onclick="godown('areaB')"></i>
            <h4>Restauração</h4>
        </div>
        <div>
                        <i class="fa-solid fa-phone" onclick="godown('contactos')"></i>
            <h4>Contactos</h4>
        </div>
    </div>
    <!-- representa a tabela com imagens reutilizada do index-->    
    <div class="conteudoC" id="areaA">
        <div>
            <div><img src="<?= $semImagem ?>" data-secao="experiencias" data-pos="0" alt="Capela de Santa Catarina" loading="lazy"></div>
            <div>
                <h2>Capela de Santa Catarina</h2>
                <p>A poucos minutos de nós, descubra este pitoresco e histórico monumento local. Um ponto de interesse imperdível para um passeio tranquilo a descobrir o património da zona.</p>
            </div>
        </div>
        <div>
            <div>
                <h2>Estação de viana do castelo</h2>
                <p>Acesso rápido à famosa Praia do Cabedelo e ao emblemático terminal fluvial que liga as duas margens do rio. Perfeito para quem procura praia, desportos náuticos ou uma travessia pitoresca de barco.</p>
            </div>
            <div><img src="<?= $semImagem ?>" data-secao="experiencias" data-pos="1" alt="Estação de Viana do Castelo" loading="lazy" ></div>
        </div>
        <div>
            <div><img src="<?= $semImagem ?>" data-secao="experiencias" data-pos="2" alt="Praia do Cabedelo" loading="lazy"></div>
            <div>
                <h2>Praia do Cabedelo</h2>
                <p>Um extenso areal dourado ideal para relaxar ou praticar desportos náuticos, situado a pouquíssimos minutos a pé.</p>
            </div>
        </div>
    </div>
    <div class="area-reservar">
        <h1>Convencido? Faz a tua Reserva...</h1>
        <input type="button" value="Reservar" onclick="goto('calendario.php')">
    </div>
    <!-- representa a tabela com imagens reutilizada do index-->  
    <div class="conteudoC" id="areaB">
        <div>
            <div>
                <h2>Restaurante Feelviana</h2>
                <p>Uma proposta gastronómica requintada que conjuga ingredientes frescos da região com uma vista privilegiada sobre a natureza e o pinhal, a poucos passos do alojamento.</p>
            </div>
            <div><img src="<?= $semImagem ?>" data-secao="experiencias" data-pos="3" class="imgbottom" alt="Restaurante Feelviana" loading="lazy"></div>
        </div>
        <div>
            <div><img src="<?= $semImagem ?>" data-secao="experiencias" data-pos="4" alt="Ap Dona Aninhas" loading="lazy"></div>
            <div>
                <h2>Ap Dona Aninhas</h2>
                <p>Um espaço acolhedor e requintado, perfeito para desfrutar de uma bebida relaxante ou de um café a poucos minutos do alojamento.</p>
            </div>
        </div>
    </div>
    <div class="area-reservar">
        <h1>Parece divertido? Veja as nossas acomodações.</h1>
        <input type="button" value="Ver acomodações" onclick="goto('oalojamento.php')">
    </div>
    <div class="contactos" id="contactos">
        <div>
            <div><i class="fa-solid fa-phone"></i> +351 935 334 117</div>
            <div><i class="fa-solid fa-envelope"></i> info.oasisdune@gmail.com</div>
            <div><i class="fa-solid fa-location-dot"></i> Rua Fernão Martins da Costa, Viana do Castelo</div>
        </div>
        <div>
            <form action="">
            <input type="text" placeholder="Nome" required>
            <input type="email" placeholder="E-mail" required>
            <textarea placeholder="Mensagem" required></textarea>
            <input type="submit" value="Enviar">
            </form>
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