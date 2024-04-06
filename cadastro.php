<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") { //Verifica se o método ude envio usado foi = "POST"

    if (!empty($_POST['email']) && !empty($_POST['password'])) { //Verifica se os campos foram preenchidos

        $email = $mysqli->real_escape_string($_POST['email']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);
        $password = $mysqli->real_escape_string($_POST['password']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);


        $check_email_sql = "SELECT * FROM usuario WHERE email = '$email'"; //Consulta para verificar o email do usuário no banco de dados;
        $check_email_result = $mysqli->query($check_email_sql); //Com o método Query verifica se o email realmente já existe no banco de dados;

        // Verifica se o e-mail já está cadastrado
        if ($check_email_result->num_rows > 0) { //Se o número de linha retornado da consulta sql for > 0, significa que já existe esse registro de email;
            echo "Este e-mail já está cadastrado!";
        } else {
            $insert_sql = "INSERT INTO usuario (email, password) VALUES ('$email', '$password')"; // Consulta SQL para inserir os dados na tabela

            if ($mysqli->query($insert_sql) === TRUE) { //verifica se a inserção dos dados no banco deu certo;
                echo "Cadastro realizado com sucesso!";
            } else {
                echo "Erro ao cadastrar: " . $mysqli->error; // Caso não tenha dado certo a inserção aparecerá essa mensagem de erro;
            }
        }
    } else {
        echo "Preencha todos os campos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="imagens/task_icon.svg">
    <title>Cadastro - Gerenciador de Tarefas</title>
</head>

<body>
    <section class="area-login">
        <div class="login">
            <div>
                <img src="imagens/task_icon.svg">
            </div>
            <form method="POST">
                <input type="text" name="email" placeholder="Digite um email" autofocus required>
                <input type="password" name="password" placeholder="Crie uma senha" required>
                <input type="submit" value="Cadastrar">
            </form>
        </div>
    </section>
</body>

</html>