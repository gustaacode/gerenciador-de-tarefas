<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="imagens/task_icon.svg">
    <title>Login - Gerenciador de Tarefas</title>
</head>

<body>
    <section class="area-login">
        <div class="login">
            <div>
                <img src="imagens/task_icon.svg">
            </div>
            <form method="POST">
                <input type="text" id="email" name="email" placeholder="seu email" autofocus required>
                <input type="password" id="password" name="password" placeholder="sua senha" autofocus required>
                <input type="submit" value="Entrar">
            </form>
            <p>Ainda não tem uma conta?<a href="cadastro.php">Criar conta</a></p>

            <?php
            include('conexao.php'); //Inclui todo o código do arquivo na index.php;

            if (isset($_POST['email']) && isset($_POST['password'])) { //Verifica se exite uma entrada de dados - email e senha;

                $email = $mysqli->real_escape_string($_POST['email']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);
                $senha = $mysqli->real_escape_string($_POST['password']); //Limpa a entrada de dados feita pelo usuário (função - real_escape_string);

                $sql_cod = "SELECT * FROM usuario WHERE email = '$email' AND password = '$senha'"; //Seleciona os dados armazenados no banco, de email e senha;
                $sqlQuery = $mysqli->query($sql_cod) or die("Falha na execução do código Sql: " . $mysqli->connect_error); // Verifica se os dados retornados existem no banco de dados;

                $quantidade = $sqlQuery->num_rows; //Retorna quantas linhas a consulta de $sqlQuery retornou;

                if ($quantidade == 1) { //Verifica se o resultado retornou apenas 1 registro, logo correspondendo ao banco de dados;

                    $usuario = $sqlQuery->fetch_assoc(); //Associa os dados retornados na variável usuário, facilitando o acesso aos mesmos

                    if (!isset($_SESSION)) { //Verifica se existe uma sessão iniciada;
                        session_start(); //Inicia uma sessão caso não tenha;
                    }
                        
                    $_SESSION['id_user'] = $id_do_usuario['id_user'];

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
        </div>
    </section>
</body>