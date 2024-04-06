<?php
include('conexao.php'); //Inclui todo o código do arquivo na index.php;

if (isset($_POST['email']) || isset($_POST['senha'])) //Verifica se exite uma entrada de dados - email e senha;

    if (strlen($_POST['email']) == 0) { //Verifica a quantidade de caracteres preenchidas pelo usuário (email e senha);
        echo 'Preencha seu e-mail!';
    } else if (strlen($_POST['senha']) == 0) { //Verifica a quantidade de caracteres preenchidas pelo usuário (email e senha);
        echo 'Preencha sua senha!';
    } else {

        $email = $mysqli->real_escape_string($_POST['email']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);
        $senha = $mysqli->real_escape_string($_POST['senha']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);

        $sql_cod = "SELECT * FROM usuario WHERE email = '$email' AND password = '$senha'"; //Seleciona os dados armazenados no banco, de email e senha;
        $sqlQuery = $mysqli->query($sql_cod) or die("Falha na execução do código Sql: " . $mysqli->connect_error); // Verifica se os dados retornados existem no banco de dados

        $quantidade = $sqlQuery->num_rows; //Retorna quantas linhas a consulta de $sqlQuery retornou;

        if ($quantidade == 1) { //Verifica se o resultado retornou apenas 1 registro, logo correspondendo ao banco de dados;

            $usuario = $sqlQuery->fetch_assoc(); //Associa os dados retornados na variável usuário, facilitando o acesso aos mesmos

            if (!isset($_SESSION)) { //Verifica se existe uma sessão iniciada;
                session_start(); //Inicia uma sessão caso não tenha;
            }

            $_SESSION['email'] = $usuario['email']; //Amazena o valores do banco relacionado ao ID, na sessão;
            $_SESSION['nome'] = $usuario['nome']; //Amazena o valores do banco relacionado ao nome, na sessão;
            header("Location: gen_tarefas.php"); //Redireciona o usuário para a tela principal;
            exit;
            //Basicamente a sessão deixa esses dados armazenados para que possa ser utilizado em outro momento, até mesmo em outra página; 
        } else {
            echo "Falha ao logar, Email ou Senha incorreto!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <h1>Acesse a sua conta</h1>
    <form action="" method="POST">

        <p>
            <label>Email</label>
            <input type="text" id="email" name="email">
        </p>

        <p>
            <label>Senha</label>
            <input type="password" id="senha" name="senha">
        </p>

        <p>
            <button type="submit">Entrar</button>
        </p>
    </form>

</body>

</html>


