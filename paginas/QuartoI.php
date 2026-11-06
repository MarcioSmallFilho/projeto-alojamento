<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Quarto I do Oasis Dune: cama de casal, ar condicionado, acesso a closet e roupa de cama de elevada qualidade.">
    <meta property="og:title" content="Quarto I | Oasis Dune">
    <meta property="og:description" content="Quarto I do Oasis Dune: cama de casal, ar condicionado, acesso a closet e roupa de cama de elevada qualidade.">
    <meta property="og:type" content="website">
    <link rel="icon" href="../imagens/logo.png">
    <link rel="stylesheet" href="../css/styleconfig.css">
    <link rel="stylesheet" href="../css/styleQua.css">
    <script src="../script/script.js" defer></script>
    <script src="../script/ampliarimgscript.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Quarto I | Oasis Dune</title>
</head>
<body>
    <div class="fundo-fixo"></div>

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
                </nav>
                <a href="https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1792669220_P3AOW4b4XqChBI5x" class="airbnb_btn">Airbnb</a>
                <button class="menu-mobile-btn" id="btn-menu" aria-label="Abrir Menu">
                    
                </button>
            </div>
        </header>

        <div class="titulo">
            <div class="conteudo-titulo">
                <h1>Quarto I</h1>
            </div>
        </div>

        <div class="conteudoA">
            <h1>Sobre o Quarto</h1>
            <p>Acolhedor quarto equipado com cama de casal, ar condicionado e acesso a closet. Inclui roupa de cama e atoalhados de elevada qualidade, ideal para um descanso tranquilo e confortável.</p>
        </div>

        <div class="areaA">
            <h1>Comodidades</h1>
            <div class="areaB">
                <div class="areaBPrincipal">
                    <div class="comodidades">
                        <span class="comodidade"><i class="fa-solid fa-door-open"></i>Armário de roupa</span>
                        <span class="comodidade"><i class="fa-solid fa-pump-soap"></i>Artigos essenciais</span>
                        <span class="comodidade"><i class="fa-solid fa-shirt"></i>Cabides</span>
                        <span class="comodidade"><i class="fa-solid fa-vault"></i>Cofre</span>
                        <span class="comodidade"><i class="fa-solid fa-moon"></i>Estores blackout</span>
                        <span class="comodidade"><i class="fa-solid fa-mattress-pillow"></i>Roupa de cama</span>
                        <span class="comodidade"><i class="fa-solid fa-snowflake"></i>Ar condicionado</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="conteudoB">
            <h1>Galeria</h1>
            <div class="areaImg">
                <img src="../imagens/Oalojamento/Quarto1.png" alt="Quarto I" loading="lazy">
            </div>  
        </div>

        <div class="modal-lightbox" id="modalLightbox">
            <span class="fechar-lightbox">&times;</span>
            <img id="imgLightbox" src="" alt="Imagem ampliada">
        </div>

        <div class="areaReservar">
            <h1>O que achou? Gostou? ...</h1>
            <input type="button" value="Reserve neste instante!" onclick="gotoAIRBNB()">
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
                <a href="https://www.livroreclamacoes.pt/" target="_blank" rel="noopener">Livro de Reclamações</a>
                <a href="https://www.airbnb.pt/rooms/1193985038143849991?source_impression_id=p3_1792669220_P3AOW4b4XqChBI5x" class="airbnb_btn">Mais no Airbnb</a>
            </div>
        </footer>
    </div>
</body>
</html>