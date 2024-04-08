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
    <script>
        function cadastrar() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            // Fazer uma solicitação AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cadastro.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Resposta recebida com sucesso
                    if (xhr.responseText.includes("sucesso")) {
                        // Se o cadastro foi bem-sucedido, atualiza a interface
                        document.getElementById("mensagem").innerHTML = "Cadastro realizado com sucesso!";

                        // Remove o botão de cadastrar
                        document.getElementById("botaoCadastrar").style.display = "none";

                        // Adiciona um novo botão de voltar para o login
                        var voltarLink = document.createElement("a");
                        voltarLink.innerHTML = "Voltar para o login";
                        voltarLink.href = 'index.php'; // Define o link para a página de login

                        // Adiciona um evento onclick para redirecionar para a página de login
                        voltarLink.onclick = function() {
                            window.location.href = this.href;
                            return false; // Impede o comportamento padrão do link
                        };

                        // Adiciona o link ao elemento "cadastroForm"
                        document.getElementById("cadastroForm").appendChild(voltarLink);


                        // Adiciona o botão de voltar ao elemento "cadastroForm"
                        document.getElementById("cadastroForm").appendChild(voltarButton);
                    } else {
                        // Se a resposta do servidor não contém "sucesso", exibe uma mensagem de erro
                        document.getElementById("mensagem").innerHTML = "Erro ao cadastrar. Tente novamente.";
                    }
                }
            };

            // Enviar os dados do formulário via AJAX
            xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
        }
    </script>
</head>

<body>
    <section class="area-login">
        <div class="login">
            <img src="imagens/task_icon.svg" style="margin-bottom: 20px;">
            <form id="cadastroForm">
                <input type="text" name="email" id="email" placeholder="Digite um email" autofocus required>
                <input type="password" name="password" id="password" placeholder="Crie uma senha" required>
                <input type="button" id="botaoCadastrar" onclick="cadastrar()" value="Cadastrar">
            </form>
            <div id="mensagem"></div>
        </div>
    </section>
</body>

</html>