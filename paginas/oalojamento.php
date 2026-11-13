<?php
    $codAlojamento = 2; // codAlojamento do Oasis Dune na tabela alojamento
    $semImagem     = '../imagens/imagens/sem-imagem.svg'; // usada quando falta a associação
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Conheça o Oasis Dune: moradia com dois quartos, sala com lareira, cozinha equipada, jacuzzi exterior, jardim, terraço e garagem na Praia do Cabedelo.">
    <meta property="og:title" content="O Alojamento | Oasis Dune">
    <meta property="og:description" content="Conheça o Oasis Dune: moradia com dois quartos, sala com lareira, cozinha equipada, jacuzzi exterior, jardim, terraço e garagem na Praia do Cabedelo.">
    <meta property="og:type" content="website">
    <link rel="icon" href="<?= $semImagem ?>" data-secao="Logo" data-pos="0">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/styleAloj.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/ampliarimgscript.js" defer></script>
    <script src="../script/imagens-dinamicas.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>O Alojamento | Oasis Dune</title>
</head>
<body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
    <div class="fundo-fixo" data-secao="oAlojamento - fundo" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"></div>

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

        <div class="titulo">
            <div class="conteudo-titulo">
                <h1>O Alojamento</h1>
                <input type="button" value="Reservar" class="but-reservar" onclick="goto('calendario.php')">
            </div>
        </div>

        <div class="conteudoB">
            <h1>Comodidades</h1>
            <div class="areaB">
                <div class="areaBPrincipal">
                    <div class="comodidades">
                        <span class="comodidade"><i class="fa-solid fa-wifi"></i>Wi-Fi</span>
                        <span class="comodidade"><i class="fa-solid fa-umbrella-beach"></i>Acesso à praia</span>
                        <span class="comodidade"><i class="fa-solid fa-water"></i>Vista para o oceano</span>
                        <span class="comodidade"><i class="fa-solid fa-shower"></i>Casa de banho privativa</span>
                        <span class="comodidade"><i class="fa-solid fa-bath"></i>Banheira de hidromassagem</span>
                        <span class="comodidade"><i class="fa-solid fa-broom"></i>Serviço de limpeza diário</span>
                        <span class="comodidade"><i class="fa-solid fa-square-parking"></i>Estacionamento gratuito no local</span>
                        <span class="comodidade"><i class="fa-solid fa-video"></i>Câmaras de segurança no exterior</span>

                        <!-- Extras: começam escondidos -->
                        <span class="comodidade extra"><i class="fa-solid fa-tv"></i>TV</span>
                        <span class="comodidade extra"><i class="fa-solid fa-vault"></i>Cofre</span>
                        <span class="comodidade extra"><i class="fa-solid fa-leaf"></i>Jardim</span>
                        <span class="comodidade extra"><i class="fa-solid fa-door-open"></i>Closet</span>
                        <span class="comodidade extra"><i class="fa-solid fa-utensils"></i>Cozinha</span>
                        <span class="comodidade extra"><i class="fa-solid fa-warehouse"></i>Garagem</span>
                        <span class="comodidade extra"><i class="fa-solid fa-couch"></i>Sofá-cama</span>
                        <span class="comodidade extra"><i class="fa-solid fa-shirt"></i>Lavandaria</span>
                        <span class="comodidade extra"><i class="fa-solid fa-chair"></i>Mesa de jantar</span>
                        <span class="comodidade extra"><i class="fa-solid fa-snowflake"></i>Ar condicionado</span>
                        <span class="comodidade extra"><i class="fa-solid fa-mug-hot"></i>Máquina de café</span>
                        <span class="comodidade extra"><i class="fa-solid fa-door-closed"></i>Entrada privada</span>
                        <span class="comodidade extra"><i class="fa-solid fa-sun"></i>Espreguiçadeiras</span>
                        <span class="comodidade extra"><i class="fa-solid fa-moon"></i>Estores blackout</span>
                        <span class="comodidade extra"><i class="fa-solid fa-fire-extinguisher"></i>Extintor de incêndio</span>
                        <span class="comodidade extra"><i class="fa-solid fa-sink"></i>Máquina de lavar loiça</span>
                        <span class="comodidade extra"><i class="fa-solid fa-kit-medical"></i>Kit de primeiros socorros</span>
                        <span class="comodidade extra"><i class="fa-solid fa-fire"></i>Lareira interior (a lenha)</span>
                    </div>

                    <button id="botaoMostrarMais" class="btn-mostrar-mais" onclick="toggleComodidades()">
                        <i class="fa-solid fa-chevron-down"></i> Mostrar todas as comodidades
                    </button>
                </div>
            </div>
        </div>

        <div class="conteudoA">
            <h1>Onde irá dormir</h1>
            <p>Um lugar para descansar</p>
            <div class="areaCartas">
                <div class="cartaQuarto">
                    <img src="<?= $semImagem ?>" data-secao="Quarto I" data-pos="0" alt="Quarto I" loading="lazy">
                    <div>
                        <h3>Quarto I</h3>
                        <input type="button" value="Ver Mais" class="but-carta" onclick="goto('QuartoI.php')">
                    </div>
                </div>

                <div class="cartaQuarto">
                    <img src="<?= $semImagem ?>" data-secao="Quarto II" data-pos="0" alt="Quarto II" loading="lazy">
                    <div>
                        <h3>Quarto II</h3>
                        <input type="button" value="Ver Mais" class="but-carta" onclick="goto('QuartoII.php')">
                    </div>
                </div>

                <div class="cartaQuarto">
                    <img src="<?= $semImagem ?>" data-secao="Sala de estar" data-pos="0" alt="Sala de estar" loading="lazy">
                    <div>
                        <h3>Sala de estar</h3>
                        <input type="button" value="Ver Mais" class="but-carta" onclick="goto('saladeestar.php')">
                    </div>
                </div>
            </div>
        </div>

        <div class="conteudoB">
            <div class="areaA">
                <div class="txt">
                    <h1>O que temos a oferecer</h1>
                    <p>O Oasis Dune (Praia do Cabedelo, Viana do Castelo) é uma moradia para seis pessoas com acesso direto às dunas e vista mar. Oferece dois quartos com ar condicionado, closet e varanda, sala com sofá-cama e TV, cozinha equipada, lavandaria, Wi-Fi, duas casas de banho (uma com hidromassagem), jacuzzi exterior no jardim, terraço mobilado, garagem e estacionamento.</p>
                </div>
                <img src="<?= $semImagem ?>" data-secao="oAlojamento" data-pos="0" alt="Vista exterior do Oasis Dune" loading="lazy">
            </div>
        </div>
        
        <div class="conteudoA">
            <h1>Galeria</h1>
            <div class="areaImg">
                <?php
                    $imagens = SELECT($pdo, 'SELECT DISTINCT i.caminho, i.nomeOriginal FROM imagem AS i INNER JOIN imagemassociacao AS ic ON i.codImagem = ic.codImagem WHERE ic.idSecao NOT IN (4, 15);');

                    for ( $x = 0; $x < count($imagens); $x++ ) {
                        if ($x < 6) {
                            echo "<img src=\"../". $imagens[$x]['caminho'] . "\" class=\"imagem\" alt=\"" . $imagens[$x]['nomeOriginal'] . "\" loading=\"lazy\">";
                        } else {
                            echo "<img src=\"../". $imagens[$x]['caminho'] . "\" class=\"imagem extra\" alt=\"" . $imagens[$x]['nomeOriginal'] . "\" loading=\"lazy\">";
                        }
                    }
                ?>
            </div>
            <button id="botaoMostrarMaisImg" class="btn-mostrar-mais" onclick="toggleImg()">
                <i class="fa-solid fa-chevron-down"></i> Mostrar todas as imagens
            </button>
        </div>

        <div class="modal-lightbox" id="modalLightbox">
            <span class="fechar-lightbox">&times;</span>
            <img id="imgLightbox" src="" alt="Imagem ampliada">
        </div>

        <div class="areaReservar">
            <h1>Que tal uma dormida? ...</h1>
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