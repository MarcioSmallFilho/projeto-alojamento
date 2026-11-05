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
        <input type="date" name="data_nasc" id="" placeholder="Data de nascimento" required>
        <input type="text" name="local_nasc" id="" placeholder="Local Nascimento">
        <select name="Nacionalidade" id="" required>
            <option value="" disabled selected>Nacionalidade...</option>
                <option value="AFG">Afegão / Afegã</option>
                <option value="ZAF">Sul-africano / Sul-africana</option>
                <option value="ALB">Albanês / Albanesa</option>
                <option value="D  ">Alemão / Alemã</option>
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
        <input type="text" name="local_resid" id="" placeholder="Local_Residencia" >
        <select name="Pais_residencia" id="" required>
            <!-- Seleção de País -->
            <option value="" disabled selected>Pais Residencia...</option>
            <option value="AFG">🇦🇫 Afeganistão</option>
            <option value="ZAF">🇿🇦 África do Sul</option>
            <option value="ALB">🇦🇱 Albânia</option>
            <option value="D  ">🇩🇪 Alemanha</option>
            <option value="AND">🇦🇩 Andorra</option>
            <option value="AGO">🇦🇴 Angola</option>
            <option value="SAU">🇸🇦 Arábia Saudita</option>
            <option value="DZA">🇩🇿 Argélia</option>
            <option value="ARG">🇦🇷 Argentina</option>
            <option value="ARM">🇦🇲 Arménia</option>
            <option value="AUS">🇦🇺 Austrália</option>
            <option value="AUT">🇦🇹 Áustria</option>
            <option value="AZE">🇦🇿 Azerbaijão</option>
            <option value="BHS">🇧🇸 Baamas</option>
            <option value="BGD">🇧🇩 Bangladeche</option>
            <option value="BEL">🇧🇪 Bélgica</option>
            <option value="BRA">🇧🇷 Brasil</option>
            <option value="CPV">🇨🇻 Cabo Verde</option>
            <option value="CAN">🇨🇦 Canadá</option>
            <option value="CHL">🇨🇱 Chile</option>
            <option value="CHN">🇨🇳 China</option>
            <option value="COL">🇨🇴 Colômbia</option>
            <option value="KOR">🇰🇷 Coreia do Sul</option>
            <option value="CRI">🇨🇷 Costa Rica</option>
            <option value="CUB">🇨🇺 Cuba</option>
            <option value="DNK">🇩🇰 Dinamarca</option>
            <option value="EGY">🇪🇬 Egito</option>
            <option value="ARE">🇦🇪 Emirados Árabes Unidos</option>
            <option value="ECU">🇪🇨 Equador</option>
            <option value="ESP">🇪🇸 Espanha</option>
            <option value="USA">🇺🇸 Estados Unidos</option>
            <option value="PHL">🇵🇭 Filipinas</option>
            <option value="FIN">🇫🇮 Finlândia</option>
            <option value="FRA">🇫🇷 França</option>
            <option value="GRC">🇬🇷 Grécia</option>
            <option value="GTM">🇬🇹 Guatemala</option>
            <option value="NLD">🇳🇱 Países Baixos</option>
            <option value="HND">🇭🇳 Honduras</option>
            <option value="HUN">🇭🇺 Hungria</option>
            <option value="IND">🇮🇳 Índia</option>
            <option value="IDN">🇮🇩 Indonésia</option>
            <option value="IRQ">🇮🇶 Iraque</option>
            <option value="IRN">🇮🇷 Irão</option>
            <option value="IRL">🇮🇪 Irlanda</option>
            <option value="ISL">🇮🇸 Islândia</option>
            <option value="ISR">🇮🇱 Israel</option>
            <option value="ITA">🇮🇹 Itália</option>
            <option value="JAM">🇯🇲 Jamaica</option>
            <option value="JPN">🇯🇵 Japão</option>
            <option value="JOR">🇯🇴 Jordânia</option>
            <option value="KWT">🇰🇼 Kuwait</option>
            <option value="LBN">🇱🇧 Líbano</option>
            <option value="MYS">🇲🇾 Malásia</option>
            <option value="MAR">🇲🇦 Marrocos</option>
            <option value="MEX">🇲🇽 México</option>
            <option value="MOZ">🇲🇿 Moçambique</option>
            <option value="NPL">🇳🇵 Nepal</option>
            <option value="NOR">🇳🇴 Noruega</option>
            <option value="NZL">🇳🇿 Nova Zelândia</option>
            <option value="PAK">🇵🇰 Paquistão</option>
            <option value="PRY">🇵🇾 Paraguai</option>
            <option value="PER">🇵🇪 Peru</option>
            <option value="POL">🇵🇱 Polónia</option>
            <option value="GBR">🇬🇧 Reino Unido</option>
            <option value="DOM">🇩🇴 República Dominicana</option>
            <option value="ROU">🇷🇴 Roménia</option>
            <option value="RUS">🇷🇺 Rússia</option>
            <option value="SLV">🇸🇻 El Salvador</option>
            <option value="SRB">🇷🇸 Sérvia</option>
            <option value="SGP">🇸🇬 Singapura</option>
            <option value="SYR">🇸🇾 Síria</option>
            <option value="SWE">🇸🇪 Suécia</option>
            <option value="CHE">🇨🇭 Suíça</option>
            <option value="THA">🇹🇭 Tailândia</option>
            <option value="TWN">🇹🇼 Taiwan</option>
            <option value="TZA">🇹🇿 Tanzânia</option>
            <option value="TUR">🇹🇷 Turquia</option>
            <option value="UKR">🇺🇦 Ucrânia</option>
            <option value="UGA">🇺🇬 Uganda</option>
            <option value="URY">🇺🇾 Uruguai</option>
            <option value="VEN">🇻🇪 Venezuela</option>
            <option value="VNM">🇻🇳 Vietname</option>
            <option value="ZMB">🇿🇲 Zâmbia</option>
            <option value="ZWE">🇿🇼 Zimbabué</option>
        </select>
        <input type="text" name="num_doc" id="" placeholder="Numero Documento" required>
        <select name="tipo_documento" id="" required>
            <option value="" disabled selected>Tipo de Documento</option>
            <option value="P">Passaporte</option>
            <option value="B">Bilhete identidade</option>
            <option value="O">Outro tipo de documento</option>
        </select>
       <select name="Pais_Emissor_Documento" required>
            <option value="" disabled selected>País Emissor do Documento...</option>
            <option value="AFG">🇦🇫 Afeganistão</option>
            <option value="ZAF">🇿🇦 África do Sul</option>
            <option value="ALB">🇦🇱 Albânia</option>
            <option value="D  ">🇩🇪 Alemanha</option>
            <option value="AND">🇦🇩 Andorra</option>
            <option value="AGO">🇦🇴 Angola</option>
            <option value="SAU">🇸🇦 Arábia Saudita</option>
            <option value="DZA">🇩🇿 Argélia</option>
            <option value="ARG">🇦🇷 Argentina</option>
            <option value="ARM">🇦🇲 Arménia</option>
            <option value="AUS">🇦🇺 Austrália</option>
            <option value="AUT">🇦🇹 Áustria</option>
            <option value="AZE">🇦🇿 Azerbaijão</option>
            <option value="BHS">🇧🇸 Baamas</option>
            <option value="BGD">🇧🇩 Bangladeche</option>
            <option value="BEL">🇧🇪 Bélgica</option>
            <option value="BRA">🇧🇷 Brasil</option>
            <option value="CPV">🇨🇻 Cabo Verde</option>
            <option value="CAN">🇨🇦 Canadá</option>
            <option value="CHL">🇨🇱 Chile</option>
            <option value="CHN">🇨🇳 China</option>
            <option value="COL">🇨🇴 Colômbia</option>
            <option value="KOR">🇰🇷 Coreia do Sul</option>
            <option value="CRI">🇨🇷 Costa Rica</option>
            <option value="CUB">🇨🇺 Cuba</option>
            <option value="DNK">🇩🇰 Dinamarca</option>
            <option value="EGY">🇪🇬 Egito</option>
            <option value="ARE">🇦🇪 Emirados Árabes Unidos</option>
            <option value="ECU">🇪🇨 Equador</option>
            <option value="ESP">🇪🇸 Espanha</option>
            <option value="USA">🇺🇸 Estados Unidos</option>
            <option value="PHL">🇵🇭 Filipinas</option>
            <option value="FIN">🇫🇮 Finlândia</option>
            <option value="FRA">🇫🇷 França</option>
            <option value="GRC">🇬🇷 Grécia</option>
            <option value="GTM">🇬🇹 Guatemala</option>
            <option value="NLD">🇳🇱 Países Baixos</option>
            <option value="HND">🇭🇳 Honduras</option>
            <option value="HUN">🇭🇺 Hungria</option>
            <option value="IND">🇮🇳 Índia</option>
            <option value="IDN">🇮🇩 Indonésia</option>
            <option value="IRQ">🇮🇶 Iraque</option>
            <option value="IRN">🇮🇷 Irão</option>
            <option value="IRL">🇮🇪 Irlanda</option>
            <option value="ISL">🇮🇸 Islândia</option>
            <option value="ISR">🇮🇱 Israel</option>
            <option value="ITA">🇮🇹 Itália</option>
            <option value="JAM">🇯🇲 Jamaica</option>
            <option value="JPN">🇯🇵 Japão</option>
            <option value="JOR">🇯🇴 Jordânia</option>
            <option value="KWT">🇰🇼 Kuwait</option>
            <option value="LBN">🇱🇧 Líbano</option>
            <option value="MYS">🇲🇾 Malásia</option>
            <option value="MAR">🇲🇦 Marrocos</option>
            <option value="MEX">🇲🇽 México</option>
            <option value="MOZ">🇲🇿 Moçambique</option>
            <option value="NPL">🇳🇵 Nepal</option>
            <option value="NOR">🇳🇴 Noruega</option>
            <option value="NZL">🇳🇿 Nova Zelândia</option>
            <option value="PAK">🇵🇰 Paquistão</option>
            <option value="PRY">🇵🇾 Paraguai</option>
            <option value="PER">🇵🇪 Peru</option>
            <option value="POL">🇵🇱 Polónia</option>
            <option value="GBR">🇬🇧 Reino Unido</option>
            <option value="DOM">🇩🇴 República Dominicana</option>
            <option value="ROU">🇷🇴 Roménia</option>
            <option value="RUS">🇷🇺 Rússia</option>
            <option value="SLV">🇸🇻 El Salvador</option>
            <option value="SRB">🇷🇸 Sérvia</option>
            <option value="SGP">🇸🇬 Singapura</option>
            <option value="SYR">🇸🇾 Síria</option>
            <option value="SWE">🇸🇪 Suécia</option>
            <option value="CHE">🇨🇭 Suíça</option>
            <option value="THA">🇹🇭 Tailândia</option>
            <option value="TWN">🇹🇼 Taiwan</option>
            <option value="TZA">🇹🇿 Tanzânia</option>
            <option value="TUR">🇹🇷 Turquia</option>
            <option value="UKR">🇺🇦 Ucrânia</option>
            <option value="UGA">🇺🇬 Uganda</option>
            <option value="URY">🇺🇾 Uruguai</option>
            <option value="VEN">🇻🇪 Venezuela</option>
            <option value="VNM">🇻🇳 Vietname</option>
            <option value="ZMB">🇿🇲 Zâmbia</option>
            <option value="ZWE">🇿🇼 Zimbabué</option>
        </select>
        <input type="date" name="check_in" id="" placeholder="Data check-in" required>
        <input type="date" name="check_out" id="" placeholder="Data check-out" required>
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

        const NOMINATIM_CONTACTO = 'smallmarcio48@gmail.com';

        const LUGARES_CACHE_DIR = __DIR__ . '/cache_lugares';

        function formatarLugar(string $lugar, int $max = 30): string {
            $lugar = trim($lugar);
            if ($lugar === '') return '';

            // Alfabetos não latinos (cirílico, árabe, chinês...) → letras latinas. Só com a extensão intl.
            if (function_exists('transliterator_transliterate')) {
                $t = transliterator_transliterate('Any-Latin; Latin-ASCII', $lugar);
                if ($t !== false) $lugar = $t;
            }

            $lugar = mb_strtoupper($lugar, 'UTF-8');

            static $mapa = null;
            if ($mapa === null) {
                $mapa = ['Æ' => 'AE', 'Œ' => 'OE', 'Þ' => 'TH'];
                $grupos = [
                    'A' => 'ÀÁÂÃÄÅĀĂĄ', 'C' => 'ÇĆČ',  'D' => 'ĎĐ',   'E' => 'ÈÉÊËĒĖĘĚ',
                    'G' => 'Ğ',         'I' => 'ÌÍÎÏĪİĮ', 'L' => 'ŁĽ',  'N' => 'ÑŃŇ',
                    'O' => 'ÒÓÔÕÖØŌŐ',  'R' => 'ŘŔ',   'S' => 'ŚŠŞȘ', 'T' => 'ŤŢȚ',
                    'U' => 'ÙÚÛÜŪŮŰŲ',  'Y' => 'ÝŸ',   'Z' => 'ŹŻŽ',
                ];
                foreach ($grupos as $letra => $acentuadas) {
                    foreach (mb_str_split($acentuadas) as $c) $mapa[$c] = $letra;
                }
            }
            $lugar = strtr($lugar, $mapa);

            $lugar = preg_replace('/[^A-Z0-9]+/', ' ', $lugar);
            $lugar = trim($lugar);

            return rtrim(substr($lugar, 0, $max));
        }

        function iso2DoPais(string $icao): string {
            static $m = [
                'AFG'=>'AF','ZAF'=>'ZA','ALB'=>'AL','D'=>'DE','AND'=>'AD','AGO'=>'AO','SAU'=>'SA','DZA'=>'DZ',
                'ARG'=>'AR','ARM'=>'AM','AUS'=>'AU','AUT'=>'AT','AZE'=>'AZ','BHS'=>'BS','BGD'=>'BD','BEL'=>'BE',
                'BRA'=>'BR','CPV'=>'CV','CAN'=>'CA','CHL'=>'CL','CHN'=>'CN','COL'=>'CO','KOR'=>'KR','CRI'=>'CR',
                'CUB'=>'CU','DNK'=>'DK','EGY'=>'EG','ARE'=>'AE','ECU'=>'EC','ESP'=>'ES','USA'=>'US','PHL'=>'PH',
                'FIN'=>'FI','FRA'=>'FR','GRC'=>'GR','GTM'=>'GT','NLD'=>'NL','HND'=>'HN','HUN'=>'HU','IND'=>'IN',
                'IDN'=>'ID','IRQ'=>'IQ','IRN'=>'IR','IRL'=>'IE','ISL'=>'IS','ISR'=>'IL','ITA'=>'IT','JAM'=>'JM',
                'JPN'=>'JP','JOR'=>'JO','KWT'=>'KW','LBN'=>'LB','MYS'=>'MY','MAR'=>'MA','MEX'=>'MX','MOZ'=>'MZ',
                'NPL'=>'NP','NOR'=>'NO','NZL'=>'NZ','PAK'=>'PK','PRY'=>'PY','PER'=>'PE','POL'=>'PL','PRT'=>'PT',
                'GBR'=>'GB','DOM'=>'DO','ROU'=>'RO','RUS'=>'RU','SLV'=>'SV','SRB'=>'RS','SGP'=>'SG','SYR'=>'SY',
                'SWE'=>'SE','CHE'=>'CH','THA'=>'TH','TWN'=>'TW','TZA'=>'TZ','TUR'=>'TR','UKR'=>'UA','UGA'=>'UG',
                'URY'=>'UY','VEN'=>'VE','VNM'=>'VN','ZMB'=>'ZM','ZWE'=>'ZW',
            ];
            return $m[strtoupper(trim($icao))] ?? '';   // '' = país desconhecido → pesquisa sem filtro
        }

        function nominatimEncontra(string $q, string $iso2): ?bool {
            $params = ['q' => $q, 'format' => 'jsonv2', 'limit' => 5];
            if ($iso2 !== '') $params['countrycodes'] = strtolower($iso2);

            $ch = curl_init('https://nominatim.openstreetmap.org/search?' . http_build_query($params));
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_USERAGENT      => 'oasis-dune-checkin/1.0 (' . NOMINATIM_CONTACTO . ')',
            ]);
            $corpo  = curl_exec($ch);
            $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            usleep(1100000);

            if ($corpo === false || $codigo !== 200) return null;
            $dados = json_decode($corpo, true);
            if (!is_array($dados)) return null;

            foreach ($dados as $r) {
                if (in_array($r['category'] ?? '', ['place', 'boundary'], true)) return true;
            }
            return false;
        }

        function procurarLugar(string $lugar, string $codigoPais = '', bool $tentarMundo = false): array {
            $formatado = formatarLugar($lugar);
            if ($formatado === '') return ['estado' => 'vazio', 'formatado' => ''];

            $iso2 = iso2DoPais($codigoPais);

            // Cache (30 dias) para não repetir pedidos
            $ficheiro = LUGARES_CACHE_DIR . '/' . sha1("$formatado|$iso2|" . (int)$tentarMundo) . '.json';
            if (is_file($ficheiro) && filemtime($ficheiro) > time() - 30 * 86400) {
                $estado = json_decode((string)file_get_contents($ficheiro), true)['estado'] ?? null;
                if ($estado) return ['estado' => $estado, 'formatado' => $formatado];
            }

            $achou = nominatimEncontra($formatado, $iso2);
            if ($achou === false && $iso2 !== '' && $tentarMundo) {
                $achou = nominatimEncontra($formatado, '');
            }
            if ($achou === null) return ['estado' => 'erro', 'formatado' => $formatado];   // não vai para a cache

            $estado = $achou ? 'encontrado' : 'nao_encontrado';
            if (!is_dir(LUGARES_CACHE_DIR)) @mkdir(LUGARES_CACHE_DIR, 0700, true);
            @file_put_contents($ficheiro, json_encode(['estado' => $estado]));
            return ['estado' => $estado, 'formatado' => $formatado];
        }

        // Não tira o contra barra '\' 
        $codigosPaises = [
            'AFG', 'ALB', 'DZA', 'AND', 'AGO', 'ATG', 'ARG', 'ARM',
            'AUS', 'AUT', 'AZE', 'BHS', 'BHR', 'BGD', 'BRB', 'BLR',
            'PLW', 'BEL', 'BLZ', 'BEN', 'BMU', 'BTN', 'BOL', 'BIH',
            'BWA', 'BRA', 'BRN', 'BGR', 'BFA', 'MMR', 'BDI', 'KHM',
            'CMR', 'CAN', 'CPV', 'CAF', 'TCD', 'CHL', 'CHN', 'COL',
            'COM', 'COG', 'COK', 'CRI', 'HRV', 'CZE', 'CUB', 'CYP',
            'DNK', 'DJI', 'DMA', 'DOM', 'TLS', 'ECU', 'EGY', 'SLV',
            'GNQ', 'ERI', 'EST', 'ETH', 'FJI', 'FIN', 'FRA', 'GAB',
            'GMB', 'GEO', 'D  ', 'GHA', 'GRC', 'GRD', 'GUM', 'GTM',
            'GIN', 'GNB', 'GUY', 'HTI', 'HND', 'HKG', 'HUN', 'ISL',
            'IND', 'IDN', 'IRN', 'IRQ', 'IRL', 'ISR', 'ITA', 'CIV',
            'JAM', 'JPN', 'JOR', 'KAZ', 'KEN', 'KIR', 'PRK', 'KOR',
            'RKS', 'KWT', 'KGZ', 'LAO', 'LVA', 'LBN', 'LSO', 'LBR',
            'LBY', 'LIE', 'LTU', 'LUX', 'MAC', 'MKD', 'MDG', 'MWI',
            'MYS', 'MDV', 'MLI', 'MLT', 'MHL', 'MRT', 'MUS', 'MYT',
            'MEX', 'FSM', 'MDA', 'MCO', 'MNG', 'MSR', 'MNE', 'MAR',
            'MOZ', 'NAM', 'NRU', 'NPL', 'NLD', 'NCL', 'NZL', 'NIC',
            'NER', 'NGA', 'NIU', 'NOR', 'OMN', 'PAK', 'PSE', 'PAN',
            'PNG', 'PRY', 'PER', 'PHL', 'POL', 'PRT', 'PRI', 'QAT',
            'COD', 'REU', 'ROU', 'RUS', 'RWA', 'STP', 'VCT', 'WSM',
            'SMR', 'SAU', 'SEN', 'SRB', 'SYC', 'SLE', 'SGP', 'SVK',
            'SVN', 'SLB', 'SOM', 'ZAF', 'SSD', 'ESP', 'LKA', 'KNA',
            'LCA', 'SDN', 'SUR', 'SWZ', 'SWE', 'CHE', 'SYR', 'TWN',
            'TJK', 'TZA', 'THA', 'TGO', 'TON', 'TTO', 'TUN', 'TUR',
            'TKM', 'TUV', 'UGA', 'UKR', 'ARE', 'GBR', 'URY', 'USA',
            'UZB', 'VUT', 'VAT', 'VEN', 'VNM', 'YEM', 'ZMB', 'ZWE'
        ];
        // Iniciar variaveis
        $cabou = false;

        // verificar se o form foi preenchido
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $info['Apelido'] = '';
            $info['Nome'] = '';
            $nome = trim($_POST['nome']);

            //Verificar se tudo foi preenchido
            if( in_array("", $_POST) || in_array(NULL, $_POST)){
                mostrarMensagem("Algum pos parametros não foi preenchido");
                $cabou = True;
            }

            //---------------------------Verificar se os parametros estão validos !?!?!?!?-----------------------

            // Verificar se o nome tem multipla palavras e se é demasiado grande
            if(strlen($nome) > 40){
                mostrarMensagem('O nome é demasiado grande!');
                $cabou = True;
            }else{
                if(substr_count($nome, " ") > 0){
                    $nomepropio = strstr($nome, ' ', true);
                    $info['Nome'] = $nomepropio;
                    $apelido = strstr($nome, ' ');
                    $info['Apelido'] = $apelido;
                }else{
                    $info['Apelido'] = $nome;
                }
            }

            // Verificar se a data de nascimento é futura
            $dtN = $_POST['data_nasc'];
            if ($dtN > date('Y-m-d')) {
                mostrarMensagem("A data de nascimento não pode ser no futuro!");
            }

            // Verificar se o Local_Nascimento ultrapassa o limite de caracteres
            if(strlen($_POST['local_nasc']) > 30){
                mostrarMensagem('O parametro "Local Nascimento" é demasiado grande!');
                $cabou = True;
            }

            // Verificar se o parametro Numero Documento é demasiado grande
            if( strlen($_POST['num_doc']) > 16 ){
                mostrarMensagem('O Parametro "Numero Documento" está incorreto!');
                $cabou = True;
            }

            // Verificar se a Nacionalidade está de acordo com o Códigos de países definido pela ICAO norma 9303
            if(!in_array($_POST['Nacionalidade'], $codigosPaises)){
                mostrarMensagem('O atributo "Nacionalidade" está modificado!');
                $cabou = true;
            }

            //Verificar se o 'tipo_documento' não foi modificadox\
            if(strlen($_POST['tipo_documento']) > 1){
                mostrarMensagem('O atributo "Tipo Documento" está modificado!');
                $cabou = true;
            }

            // Verificar se o pais emissor de documento está de acordo com o Códigos de países definido pela ICAO norma 9303
            if(!in_array($_POST['Pais_Emissor_Documento'], $codigosPaises)){
                mostrarMensagem('O parametro "Pais Emissor Documento" está modificado!');
                $cabou = true;
            }

            // Verificar se o pais residencia está de acordo com o Códigos de países definido pela ICAO norma 9303
            if(!in_array($_POST['Pais_residencia'], $codigosPaises)){
                mostrarMensagem('O parametro "Pais_residencia" está modificado!');
                $cabou = true;
            }

            if(strlen($_POST['local_resid']) > 30){
                mostrarMensagem('O parametro "Local Residencia" é demasiado grande!');
                $cabou = true;
            }

            //Verificar se ouve algum erro, e se não proceder normalmente, 
            if(!$cabou){
                $locNasc  = procurarLugar($_POST['local_nasc'],  $_POST['Nacionalidade'],  true);
                $locResid = procurarLugar($_POST['local_resid'], $_POST['Pais_residencia'], false);
                if($locNasc['estado'] === 'nao_encontrado'){
                    mostrarMensagem('Não encontrámos o local de nascimento "' . $locNasc['formatado'] . '". Confirme se o nome está correto.');
                }
                if($locResid['estado'] === 'nao_encontrado'){
                    mostrarMensagem('Não encontrámos o local de residência "' . $locResid['formatado'] . '". Confirme se o nome está correto.');
                }

                $info['Local_Residencia'] = $locResid['formatado'];
                $info['Pais_Residencia'] = $_POST["Pais_residencia"];
                $info['Data_Saida'] = str_replace('-', '', $_POST['check_out']);
                $info['Data_Entrada'] = str_replace('-', '', $_POST['check_in']);
                $info['Pais_Emissor_Documento'] = $_POST['Pais_Emissor_Documento'];
                $info['Tipo_Documento_Identificacao'] = $_POST['tipo_documento'];
                $info['Local_Nascimento'] = $locNasc['formatado'];
                $info['Documento_Identificacao'] = $_POST['num_doc'];
                $info['Data_Nascimento'] = str_replace('-', '', $dtN);
                $info['Nacionalidade'] = $_POST['Nacionalidade'];

                var_dump($info);
                $arquivo = fopen('exemplo.xml','w');
                fwrite($arquivo, "\n0|BA03|123456789|0001|HOTEL EXEMPLO|RUA CENTRAL 10|PORTO|4000|123|220123456||JOAO SILVA|\n1|".$info['Apelido']."|".$info['Nome']."|".$info['Data_Nascimento']."|".$info['Nacionalidade']."|".$info['Documento_Identificacao']."|".$info['Tipo_Documento_Identificacao']."|".$info['Pais_Emissor_Documento']."|".$info['Pais_Residencia']."|".$info['Local_Residencia']."|".$info['Data_Entrada']."|".$info['Data_Saida']."|\n9|00002|20261030|00001|");
                fclose($arquivo);
            }
        }

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