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

            // Verifica se os campos estão preenchidos
            if (email.trim() === "" || password.trim() === "") {
                document.getElementById("mensagem").innerHTML = "Preencha todos os campos!";
                return;
            }

            // Fazer uma solicitação AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cadastro.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Resposta recebida com sucesso
                    document.getElementById("mensagem").innerHTML = xhr.responseText;
                    if (xhr.responseText.includes("sucesso")) {
                        // Se o cadastro foi bem-sucedido, atualiza a interface
                        document.getElementById("cadastroForm").innerHTML = "Cadastro realizado";
                        document.getElementById("mensagem").innerHTML = "";
                        // Adicionar botão "Entrar"
                        var entrarButton = document.createElement("button");
                        entrarButton.innerHTML = "Entrar";
                        entrarButton.onclick = function() {
                            window.location.href = 'gen_tarefas.php';
                        };
                        document.getElementById("cadastroForm").appendChild(entrarButton);
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
            <div id="cadastroForm">
                <input type="text" name="email" id="email" placeholder="Digite um email" autofocus>
                <input type="password" name="password" id="password" placeholder="Crie uma senha">
                <button type="button" id="botaoCadastrar" onclick="cadastrar()">Cadastrar</button>
            </div>
            <div id="mensagem"></div>
        </div>
    </section>
</body>

</html>