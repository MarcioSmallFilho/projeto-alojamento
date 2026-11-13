<?php
// Configuração das imagens desta página (vêm do sistema de secções)
$codAlojamento = 2;                            // codAlojamento do Oasis Dune na tabela alojamento
$semImagem     = '../imagens/sem-imagem.svg';  // usada quando falta a associação
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Moradia para seis pessoas na Praia do Cabedelo, em Viana do Castelo, com acesso direto às dunas, vista mar, jacuzzi e jardim. Reserve a sua estadia.">
    <meta property="og:title" content="Oasis Dune | Alojamento na Praia do Cabedelo, Viana do Castelo">
    <meta property="og:description" content="Moradia para seis pessoas na Praia do Cabedelo, em Viana do Castelo, com acesso direto às dunas, vista mar, jacuzzi e jardim. Reserve a sua estadia.">
    <meta property="og:type" content="website">
    <link rel="icon" src="../imagens/logo-ico.ico">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/ampliarimgscript.js" defer></script>
    <script src="../script/imagens-dinamicas.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Oasis Dune | Alojamento na Praia do Cabedelo, Viana do Castelo</title>
</head>
<body data-alojamento="<?= (int) $codAlojamento ?>" data-sem-imagem="<?= $semImagem ?>">
    <div class="fundo-fixo" data-secao="Index - Fundo" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"></div>

    <div class="conteudo-site">
        <header>
            <div class="h-esq">
                <img src="../imagens/logo.png" class='logotipo' height="100px" onclick="goto('index.php')" alt="Oasis Dune – página inicial">
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
                <h1>Oasis Dune, Praia do Cabedelo</h1>
                <input type="button" value="Reservar" class="but-reservar" onclick="goto('calendario.php')">
            </div>
        </div>

        <div class="conteudoA">
            <h1>O Alojamento</h1>
            <p>Descontraia e aproveite o melhor de Cabedelo.</p>
            <div class="conteiner-imgs">
                <img src="<?= $semImagem ?>" data-secao="Index - Galeria" data-pos="0" alt="Alojamento Oasis Dune, Praia do Cabedelo" loading="lazy">
                <img src="<?= $semImagem ?>" data-secao="Index - Galeria" data-pos="1" alt="Alojamento Oasis Dune, Praia do Cabedelo" loading="lazy">
                <img src="<?= $semImagem ?>" data-secao="Index - Galeria" data-pos="2" alt="Alojamento Oasis Dune, Praia do Cabedelo" loading="lazy">
            </div>

            <div class="modal-lightbox" id="modalLightbox">
                <span class="fechar-lightbox">&times;</span>
                <img id="imgLightbox" src="" alt="Imagem ampliada">
            </div>

            <div class="conteiner-video">
                <div class="texto">
                <h2>O seu refúgio de paz e descanso</h2>
                <p>Espaços desenhados ao pormenor para garantir o descanso máximo. Com luz natural, ambiente acolhedor e todas as comodidades essenciais, os nossos quartos são o seu refúgio privado no Oasís Dune.</p>
                </div>
                <img src="<?= $semImagem ?>" data-secao="Index - Descanso" data-pos="0" alt="Alojamento Oasis Dune, Praia do Cabedelo" loading="lazy">
            </div>
        </div>
        <div class="conteudoB">
            <h1>Quartos</h1>
            <p>Simplicidade e conforto para uma noite sem preocupações.</p>
            <div class="cards">
                <div class="card1" data-secao="Quarto I" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"><div>Quarto I<button class="btn-card" onclick="goto('QuartoI.php')">Saber Mais</button></div></div>
                <div class="card2" data-secao="Quarto II" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"><div>Quarto II<button class="btn-card" onclick="goto('QuartoII.php')">Saber Mais</button></div></div>
                <div class="card3" data-secao="Sala de estar" data-pos="0" data-fundo style="background-image: url('<?= $semImagem ?>');"><div>Sala de estar <button class="btn-card" onclick="goto('saladeestar.php')">Saber Mais</button></div></div>
            </div>
        </div>
        <div class="conteudoC">
            <div>
                <div>
                    <h1>Atendimento exclusivo</h1>
                    <p>O seu bem-estar vem em primeiro lugar. Oferecemos um serviço genuíno, atento e discreto, para que a sua única preocupação seja relaxar.</p>
                </div>
                <div>
                    <img src="<?= $semImagem ?>" data-secao="Index - Atendimento" data-pos="0" alt="Alojamento Oasis Dune, Praia do Cabedelo" loading="lazy">
                </div>
            </div>
            <div>
                <div>
                    <img src="<?= $semImagem ?>" data-secao="Index - Centralidade" data-pos="0" alt="Praia do Cabedelo, Viana do Castelo" loading="lazy">
                </div>
                <div>
                    <h1>Centralidade</h1>
                    <p>A poucos minutos das atrações, praias, restaurantes e acessos da região. A base perfeita para explorar tudo com facilidade e regressar à tranquilidade.</p>
                </div>
            </div>
        </div>
        <div class="avaliacao">
            <h2>"Todas as comodidades bem equipadas, Anfitriões extremamente atenciosos."</h2>
            <div class="estrelas" role="img" aria-label="Avaliação de 5 estrelas em 5">
                <img src="../imagens/estrela.png" height="25px" alt="">
                <img src="../imagens/estrela.png" height="25px" alt="">
                <img src="../imagens/estrela.png" height="25px" alt="">
                <img src="../imagens/estrela.png" height="25px" alt="">
                <img src="../imagens/estrela.png" height="25px" alt="">
            </div>
            <!-- Substituir por uma imagem automaticamente ficará formatado-->
            <i class="fa-solid fa-circle-user" style="font-size: 50px;"></i>
            <h3>Artur</h3>
        </div>
        <footer>
            <div>
                <img src="../imagens/logo.png" class='logotipo' height="100px" onclick="goto('index.php')">
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