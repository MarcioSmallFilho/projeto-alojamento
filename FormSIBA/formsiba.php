<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boletim de alojamento</title>
</head>
    <style>
        /* Estilização da etiqueta flutuante */
        .etiqueta-flutuante {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #cc2e2e; /* Cor de fundo (verde sucesso) */
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: Arial, sans-serif;
            font-size: 14px;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.5s ease-in-out;
        }

        /* Botão para fechar a etiqueta */
        .etiqueta-fechar {
            background: none;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
            font-weight: bold;
        }
        #mensagens-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            flex-direction: column-reverse;
            gap: 10px;
            z-index: 9999;
            max-width: 350px;
        }

        .etiqueta-flutuante {
            position: static;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            width: 100%;
            box-sizing: border-box;
        }

        /* Efeito simples de surgimento */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
<body>
    <div id="mensagens-container">
        <!-- As mensagens aparecem aqui -->
    </div>
    <form method="POST">
        <input type="text" name="nome" id="" placeholder="Nome Completo" required>
        <input type="date" name="data_nasc" id="" placeholder="Data de nascimento" >
        <input type="text" name="local_nasc" id="" placeholder="Local Nascimento" >
        <select name="Nacionalidade" id="" >
            <option value="" disabled selected>Nacionalidade...</option>
                <option value="AFG">Afegão / Afegã</option>
                <option value="ZAF">Sul-africano / Sul-africana</option>
                <option value="ALB">Albanês / Albanesa</option>
                <option value="DEU">Alemão / Alemã</option>
                <option value="AND">Andorrano / Andorrana</option>
                <option value="AGO">Angolano / Angolana</option>
                <option value="SAU">Saudita</option>
                <option value="DZA">Argelino / Argelina</option>
                <option value="ARG">Argentino / Argentina</option>
                <option value="ARM">Arménio / Arménia</option>
                <option value="AUS">Australiano / Australiana</option>
                <option value="AUT">Austríaco / Austríaca</option>
                <option value="AZE">Azeri / Azerbaijano</option>
                <option value="BHS">Bahamense</option>
                <option value="BGD">Bangladexiano / Bangladexiana</option>
                <option value="BEL">Belga</option>
                <option value="BRA">Brasileiro / Brasileira</option>
                <option value="CPV">Cabo-verdiano / Cabo-verdiana</option>
                <option value="CAN">Canadiano / Canadiana</option>
                <option value="CHL">Chileno / Chilena</option>
                <option value="CHN">Chinês / Chinesa</option>
                <option value="COL">Colombiano / Colombiana</option>
                <option value="KOR">Sul-coreano / Sul-coreana</option>
                <option value="CRI">Costarriquenho / Costarriquenha</option>
                <option value="CUB">Cubano / Cubana</option>
                <option value="DNK">Dinamarquês / Dinamarquesa</option>
                <option value="EGY">Egípcio / Egípcia</option>
                <option value="ARE">Emiratense</option>
                <option value="ECU">Equatoriano / Equatoriana</option>
                <option value="ESP">Espanhol / Espanhola</option>
                <option value="USA">Norte-americano / Norte-americana</option>
                <option value="PHL">Filipino / Filipina</option>
                <option value="FIN">Finlandês / Finlandesa</option>
                <option value="FRA">Francês / Francesa</option>
                <option value="GRC">Grego / Grega</option>
                <option value="GTM">Guatemalteco / Guatemalteca</option>
                <option value="NLD">Neerlandês / Neerlandesa</option>
                <option value="HND">Hondurenho / Hondurenha</option>
                <option value="HUN">Húngaro / Húngara</option>
                <option value="IND">Indiano / Indiana</option>
                <option value="IDN">Indonésio / Indonésia</option>
                <option value="IRQ">Iraquiano / Iraquiana</option>
                <option value="IRN">Iraniano / Iraniana</option>
                <option value="IRL">Irlandês / Irlandesa</option>
                <option value="ISL">Islandês / Islandesa</option>
                <option value="ISR">Israelita / Israelense</option>
                <option value="ITA">Italiano / Italiana</option>
                <option value="JAM">Jamaicano / Jamaicana</option>
                <option value="JPN">Japonês / Japonesa</option>
                <option value="JOR">Jordaniano / Jordaniana</option>
                <option value="KWT">Kuwaitiano / Kuwaitiana</option>
                <option value="LBN">Libanês / Libanesa</option>
                <option value="MYS">Malaio / Malaia</option>
                <option value="MAR">Marroquino / Marroquina</option>
                <option value="MEX">Mexicano / Mexicana</option>
                <option value="MOZ">Moçambicano / Moçambicana</option>
                <option value="NPL">Nepalês / Nepalesa</option>
                <option value="NOR">Norueguês / Norueguesa</option>
                <option value="NZL">Neozelandês / Neozelandesa</option>
                <option value="PAK">Paquistanês / Paquistanesa</option>
                <option value="PRY">Paraguaio / Paraguaia</option>
                <option value="PER">Peruano / Peruana</option>
                <option value="POL">Polaco / Polaca</option>
                <option value="PRT">Português / Portuguesa</option>
                <option value="GBR">Britânico / Britânica</option>
                <option value="DOM">Dominicano / Dominicana</option>
                <option value="ROU">Romeno / Romena</option>
                <option value="RUS">Russo / Russa</option>
                <option value="SLV">Salvadorenho / Salvadorenha</option>
                <option value="SRB">Sérvio / Sérvia</option>
                <option value="SGP">Singapurense</option>
                <option value="SYR">Sírio / Síria</option>
                <option value="SWE">Sueco / Sueca</option>
                <option value="CHE">Suíço / Suíça</option>
                <option value="THA">Tailandês / Tailandesa</option>
                <option value="TWN">Taiwanês / Taiwanesa</option>
                <option value="TZA">Tanzaniano / Tanzaniana</option>
                <option value="TUR">Turco / Turca</option>
                <option value="UKR">Ucraniano / Ucraniana</option>
                <option value="UGA">Ugandês / Ugandesa</option>
                <option value="URY">Uruguaio / Uruguaia</option>
                <option value="VEN">Venezuelano / Venezuelana</option>
                <option value="VNM">Vietnamita</option>
                <option value="ZMB">Zambiano / Zambiana</option>
                <option value="ZWE">Zimbabueano / Zimbabueana</option>
        </select> 
        <input type="text" name="local_resid" id="" placeholder="Local Residencia" >
        <select name="Pais residencia" id="" >
            <!-- Seleção de País -->
            <option value="" disabled selected>Pais Residencia...</option>
            <option value="AF">🇦🇫 Afeganistão</option>
            <option value="ZA">🇿🇦 África do Sul</option>
            <option value="AL">🇦🇱 Albânia</option>
            <option value="DE">🇩🇪 Alemanha</option>
            <option value="AD">🇦🇩 Andorra</option>
            <option value="AO">🇦🇴 Angola</option>
            <option value="SA">🇸🇦 Arábia Saudita</option>
            <option value="DZ">🇩🇿 Argélia</option>
            <option value="AR">🇦🇷 Argentina</option>
            <option value="AM">🇦🇲 Arménia</option>
            <option value="AU">🇦🇺 Austrália</option>
            <option value="AT">🇦🇹 Áustria</option>
            <option value="AZ">🇦🇿 Azerbaijão</option>
            <option value="BS">🇧🇸 Baamas</option>
            <option value="BD">🇧🇩 Bangladeche</option>
            <option value="BE">🇧🇪 Bélgica</option>
            <option value="BR">🇧🇷 Brasil</option>
            <option value="CV">🇨🇻 Cabo Verde</option>
            <option value="CA">🇨🇦 Canadá</option>
            <option value="CL">🇨🇱 Chile</option>
            <option value="CN">🇨🇳 China</option>
            <option value="CO">🇨🇴 Colômbia</option>
            <option value="KR">🇰🇷 Coreia do Sul</option>
            <option value="CR">🇨🇷 Costa Rica</option>
            <option value="CU">🇨🇺 Cuba</option>
            <option value="DK">🇩🇰 Dinamarca</option>
            <option value="EG">🇪🇬 Egito</option>
            <option value="AE">🇦🇪 Emirados Árabes Unidos</option>
            <option value="EC">🇪🇨 Equador</option>
            <option value="ES">🇪🇸 Espanha</option>
            <option value="US">🇺🇸 Estados Unidos</option>
            <option value="PH">🇵🇭 Filipinas</option>
            <option value="FI">🇫🇮 Finlândia</option>
            <option value="FR">🇫🇷 França</option>
            <option value="GR">🇬🇷 Grécia</option>
            <option value="GT">🇬🇹 Guatemala</option>
            <option value="NL">🇳🇱 Países Baixos (Holanda)</option>
            <option value="HN">🇭🇳 Honduras</option>
            <option value="HU">🇭🇺 Hungria</option>
            <option value="IN">🇮🇳 Índia</option>
            <option value="ID">🇮🇩 Indonésia</option>
            <option value="IQ">🇮🇶 Iraque</option>
            <option value="IR">🇮🇷 Irão</option>
            <option value="IE">🇮🇪 Irlanda</option>
            <option value="IS">🇮🇸 Islândia</option>
            <option value="IL">🇮🇱 Israel</option>
            <option value="IT">🇮🇹 Itália</option>
            <option value="JM">🇯🇲 Jamaica</option>
            <option value="JP">🇯🇵 Japão</option>
            <option value="JO">🇯🇴 Jordânia</option>
            <option value="KW">🇰🇼 Kuwait</option>
            <option value="LB">🇱🇧 Líbano</option>
            <option value="MY">🇲🇾 Malásia</option>
            <option value="MA">🇲🇦 Marrocos</option>
            <option value="MX">🇲🇽 México</option>
            <option value="MZ">🇲🇿 Moçambique</option>
            <option value="NP">🇳🇵 Nepal</option>
            <option value="NO">🇳🇴 Noruega</option>
            <option value="NZ">🇳🇿 Nova Zelândia</option>
            <option value="PK">🇵🇰 Paquistão</option>
            <option value="PY">🇵🇾 Paraguai</option>
            <option value="PE">🇵🇪 Peru</option>
            <option value="PL">🇵🇱 Polónia</option>
            <option value="GB">🇬🇧 Reino Unido</option>
            <option value="DO">🇩🇴 República Dominicana</option>
            <option value="RO">🇷🇴 Roménia</option>
            <option value="RU">🇷🇺 Rússia</option>
            <option value="SV">🇸🇻 El Salvador</option>
            <option value="RS">🇷🇸 Sérvia</option>
            <option value="SG">🇸🇬 Singapura</option>
            <option value="SY">🇸🇾 Síria</option>
            <option value="SE">🇸🇪 Suécia</option>
            <option value="CH">🇨🇭 Suíça</option>
            <option value="TH">🇹🇭 Tailândia</option>
            <option value="TW">🇹🇼 Taiwan</option>
            <option value="TZ">🇹🇿 Tanzânia</option>
            <option value="TR">🇹🇷 Turquia</option>
            <option value="UA">🇺🇦 Ucrânia</option>
            <option value="UG">🇺🇬 Uganda</option>
            <option value="UY">🇺🇾 Uruguai</option>
            <option value="VE">🇻🇪 Venezuela</option>
            <option value="VN">🇻🇳 Vietname</option>
            <option value="ZM">🇿🇲 Zâmbia</option>
            <option value="ZW">🇿🇼 Zimbabué</option>
        </select>
        <input type="text" name="num_doc" id="" placeholder="Numero Documento" >
        <select name="Tipo documento" id="" >
            <option value="" disabled selected>Tipo de Documento</option>
            <option value="P">Passaporte</option>
            <option value="B">Bilhete identidade</option>
            <option value="O">Outro tipo de documento</option>
        </select>
        <select name="Pais Emissor Documento" id="" >
            <option value="" disabled selected>Pais Emissor Documento...</option>
            <option value="AF">🇦🇫 Afeganistão</option>
            <option value="ZA">🇿🇦 África do Sul</option>
            <option value="AL">🇦🇱 Albânia</option>
            <option value="DE">🇩🇪 Alemanha</option>
            <option value="AD">🇦🇩 Andorra</option>
            <option value="AO">🇦🇴 Angola</option>
            <option value="SA">🇸🇦 Arábia Saudita</option>
            <option value="DZ">🇩🇿 Argélia</option>
            <option value="AR">🇦🇷 Argentina</option>
            <option value="AM">🇦🇲 Arménia</option>
            <option value="AU">🇦🇺 Austrália</option>
            <option value="AT">🇦🇹 Áustria</option>
            <option value="AZ">🇦🇿 Azerbaijão</option>
            <option value="BS">🇧🇸 Baamas</option>
            <option value="BD">🇧🇩 Bangladeche</option>
            <option value="BE">🇧🇪 Bélgica</option>
            <option value="BR">🇧🇷 Brasil</option>
            <option value="CV">🇨🇻 Cabo Verde</option>
            <option value="CA">🇨🇦 Canadá</option>
            <option value="CL">🇨🇱 Chile</option>
            <option value="CN">🇨🇳 China</option>
            <option value="CO">🇨🇴 Colômbia</option>
            <option value="KR">🇰🇷 Coreia do Sul</option>
            <option value="CR">🇨🇷 Costa Rica</option>
            <option value="CU">🇨🇺 Cuba</option>
            <option value="DK">🇩🇰 Dinamarca</option>
            <option value="EG">🇪🇬 Egito</option>
            <option value="AE">🇦🇪 Emirados Árabes Unidos</option>
            <option value="EC">🇪🇨 Equador</option>
            <option value="ES">🇪🇸 Espanha</option>
            <option value="US">🇺🇸 Estados Unidos</option>
            <option value="PH">🇵🇭 Filipinas</option>
            <option value="FI">🇫🇮 Finlândia</option>
            <option value="FR">🇫🇷 França</option>
            <option value="GR">🇬🇷 Grécia</option>
            <option value="GT">🇬🇹 Guatemala</option>
            <option value="NL">🇳🇱 Países Baixos (Holanda)</option>
            <option value="HN">🇭🇳 Honduras</option>
            <option value="HU">🇭🇺 Hungria</option>
            <option value="IN">🇮🇳 Índia</option>
            <option value="ID">🇮🇩 Indonésia</option>
            <option value="IQ">🇮🇶 Iraque</option>
            <option value="IR">🇮🇷 Irão</option>
            <option value="IE">🇮🇪 Irlanda</option>
            <option value="IS">🇮🇸 Islândia</option>
            <option value="IL">🇮🇱 Israel</option>
            <option value="IT">🇮🇹 Itália</option>
            <option value="JM">🇯🇲 Jamaica</option>
            <option value="JP">🇯🇵 Japão</option>
            <option value="JO">🇯🇴 Jordânia</option>
            <option value="KW">🇰🇼 Kuwait</option>
            <option value="LB">🇱🇧 Líbano</option>
            <option value="MY">🇲🇾 Malásia</option>
            <option value="MA">🇲🇦 Marrocos</option>
            <option value="MX">🇲🇽 México</option>
            <option value="MZ">🇲🇿 Moçambique</option>
            <option value="NP">🇳🇵 Nepal</option>
            <option value="NO">🇳🇴 Noruega</option>
            <option value="NZ">🇳🇿 Nova Zelândia</option>
            <option value="PK">🇵🇰 Paquistão</option>
            <option value="PY">🇵🇾 Paraguai</option>
            <option value="PE">🇵🇪 Peru</option>
            <option value="PL">🇵🇱 Polónia</option>
            <option value="GB">🇬🇧 Reino Unido</option>
            <option value="DO">🇩🇴 República Dominicana</option>
            <option value="RO">🇷🇴 Roménia</option>
            <option value="RU">🇷🇺 Rússia</option>
            <option value="SV">🇸🇻 El Salvador</option>
            <option value="RS">🇷🇸 Sérvia</option>
            <option value="SG">🇸🇬 Singapura</option>
            <option value="SY">🇸🇾 Síria</option>
            <option value="SE">🇸🇪 Suécia</option>
            <option value="CH">🇨🇭 Suíça</option>
            <option value="TH">🇹🇭 Tailândia</option>
            <option value="TW">🇹🇼 Taiwan</option>
            <option value="TZ">🇹🇿 Tanzânia</option>
            <option value="TR">🇹🇷 Turquia</option>
            <option value="UA">🇺🇦 Ucrânia</option>
            <option value="UG">🇺🇬 Uganda</option>
            <option value="UY">🇺🇾 Uruguai</option>
            <option value="VE">🇻🇪 Venezuela</option>
            <option value="VN">🇻🇳 Vietname</option>
            <option value="ZM">🇿🇲 Zâmbia</option>
            <option value="ZW">🇿🇼 Zimbabué</option>
        </select>
        <input type="date" name="check_in" id="" placeholder="Data check-in" >
        <input type="date" name="check_out" id="" placeholder="Data check-out" >
        <input type="submit" value="">
    </form>
    <?php
        function mostrarMensagem($textoMensagem) {
            echo '
            <script>
            (function() {
                function adicionarMensagem() {
                    const container = document.getElementById("mensagens-container");
                    if (!container) return;

                    const etiqueta = document.createElement("div");
                    etiqueta.className = "etiqueta-flutuante";

                    const texto = document.createElement("span");
                    texto.textContent = ' . json_encode($textoMensagem) . ';

                    const botao = document.createElement("button");
                    botao.type = "button";
                    botao.className = "etiqueta-fechar";
                    botao.textContent = "×";
                    botao.onclick = function() {
                        etiqueta.remove();
                    };

                    etiqueta.append(texto, botao);
                    container.appendChild(etiqueta);

                    setTimeout(function() {
                        etiqueta.remove();
                    }, 5000);
                }

                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", adicionarMensagem);
                } else {
                    adicionarMensagem();
                }
            })();
            </script>';
        }

        // Iniciar variaveis
        $cabou = false;

        // verificar se o form foi preenchido
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $info['Apelido'] = '';
            $info['Nome'] = '';
            $nome = trim($_POST['nome']);

            //Verificar se tudo foi preenchido
            if( in_array("", $_POST) ){
                mostrarMensagem("Algum pos parametros não foi preenchido");
                $cabou = True;
            }

            //Verificar se os parametros estão validos !?!?!?!?

            // Verificar se o nome tem multipla palavras e se é demasiado grande
            if(strlen($nome) > 40){
                mostrarMensagem('O nome é demasiado grande!');
                $cabou = True;
            }else{
                if(substr_count($nome, " ") > 0){
                    $info['Nome'] = $nome;
                }else{
                    $info['Apelido'] = $nome;
                }
            }

            // Verificar se a data de nascimento é futura
            $dtN = $_POST['data_nasc'];
            if ($dtN > date('Y-m-d')) {
                mostrarMensagem("A data de nascimento não pode ser no futuro!");
                $cabou = True;
               
            }

            // Verificar se o Local_Nascimento ultrapassa o limite de caracteres
            if(strlen($_POST['local_nasc']) > 30){
                mostrarMensagem('O parametro "Local Nascimento" é demasiaso grande!');
                $cabou = True;
            }

            // Verificar se o parametro Numero Documento é demasiado grande
            if( strlen($_POST['num_doc']) > 16 ){
                mostrarMensagem('O Parametro "Numero Documento" está incorreto!');
                $cabou = True;
            }






            //Verificar se ouve algum erro, e se não proceder normalmente, 
            if(!$cabou){
                $info['Data_Nascimento'] = $dtN;
                $info['Nacionalidade'] = $_POST['Nacionalidade'];

                var_dump($info);
            }
        }

        $arquivo = fopen('exemplo.xml','w');
        fwrite($arquivo, "\n0|BA03|123456789|0001|HOTEL EXEMPLO|RUA CENTRAL 10|PORTO|4000|123|220123456||JOAO SILVA|\n1|".$info['Apelido']."|".$info['Nome']."|".$info['Data_Nascimento']."|".$info['Nacionalidade']."|".$info['Documento_Identificacao']."|".$info['Tipo_Documento_Identificacao']."|".$info['Pais_Emissor_Documento']."|".$info['Pais_Residencia_Origem']."|".$info['Local_Residencia_Origem']."|".$info['Data_Entrada']."|".$info['Data_Saida']."|\n9|00002|20261030|00001|");
        fclose($arquivo);
    ?>
    <script>
    document.querySelectorAll('.etiqueta-flutuante').forEach(function(etiqueta) {
        setTimeout(function() {
            etiqueta.remove();
        }, 5000);
    });
</script>
</body>
</html>