<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <div class="login-card" style="border: 1px solid black; border-radius: 5px; width: 350px; height: 200px; justify-self: center;">
            <form action="" method="post" style="display: flex; flex-direction: column;">
                <input type="text" name="user" id="" placeholder="Username" required>
                <input type="email" name="email" id="" placeholder="E-mail" required>
                <input type="password" name="password" id="" placeholder="Password" required>
                <input type="submit" value="Login">

                <?php

                require_once 'C:/xampp/database.php';

                if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $nome = $_POST['user'];
                    $email = $_POST['email'];
                    $pdo = ConnectDB();

                     $sql = "
                        SELECT * FROM Utilizador
                        WHERE nomeUtilizador = :nome AND emailUtilizador = :email";

                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':nome' => $nome, ':email' => $email]);

                    $Utilizador = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if(!$Utilizador){
                        echo '<p style="color: red;">Nome de utilizador ou E-mail incorretos</p>';
                    }else{
                        $Utilizador = $Utilizador[0];
                        if(password_verify($_POST['password'], $Utilizador['palavraPasse'])){
                            echo "<p> login feito com sucesso.</p>";
                        }
                    }
                }
                ?>

                <p>Não tens uma conta? <a href="../alojamento/register.php">Registra-te</a></p>
            </form>
        </div>
    </div>
</body>
</html>