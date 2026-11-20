
<?php

function notificacao($mensagem, $tipo = "erro") {

    $cores = [
        "erro" => "#ef4444",
        "sucesso" => "#22c55e",
        "aviso" => "#f59e0b",
        "info" => "#3b82f6"
    ];

    $icones = [
        "erro" => "✖",
        "sucesso" => "✔",
        "aviso" => "⚠",
        "info" => "ℹ"
    ];

    $cor = $cores[$tipo] ?? $cores["erro"];
    $icone = $icones[$tipo] ?? $icones["erro"];

    $mensagem = htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8');

    echo "
    <div class='notificacao' style='--cor: $cor'>
        <div class='icone'>$icone</div>
        <div class='conteudo'>
            <strong>" . ucfirst($tipo) . "</strong>
            <p>$mensagem</p>
        </div>
        <button onclick='this.parentElement.remove()'>×</button>
    </div>

    <script>
        setTimeout(() => {
            const notificacao = document.querySelector('.notificacao:last-of-type');
            if (notificacao) {
                notificacao.classList.add('sair');
                setTimeout(() => notificacao.remove(), 400);
            }
        }, 4000);
    </script>
    ";
}

?>

<style>
.notificacao {
    position: fixed;
    top: 20px;
    right: 20px;
    width: 320px;
    background: #1f2937;
    color: white;
    border-left: 5px solid var(--cor);
    border-radius: 8px;
    padding: 16px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 5px 20px rgba(0,0,0,.25);
    z-index: 9999;
    animation: entrar .4s ease;
    font-family: Arial, sans-serif;
}

.notificacao .icone {
    color: var(--cor);
    font-size: 24px;
}

.notificacao .conteudo {
    flex: 1;
}

.notificacao strong {
    font-size: 15px;
}

.notificacao p {
    margin: 5px 0 0;
    font-size: 13px;
}

.notificacao button {
    background: none;
    border: none;
    color: white;
    font-size: 22px;
    cursor: pointer;
}

.notificacao.sair {
    animation: sair .4s forwards;
}

@keyframes entrar {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes sair {
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}
</style>
