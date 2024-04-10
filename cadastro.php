<?php
include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['email']) && !empty($_POST['password'])) {

        $email = $mysqli->real_escape_string($_POST['email']);
        $password = $mysqli->real_escape_string($_POST['password']);

        // Verifica se o e-mail já está cadastrado
        $check_email_sql = "SELECT * FROM usuario WHERE email = '$email'";
        $check_email_result = $mysqli->query($check_email_sql);

        if ($check_email_result->num_rows > 0) {
            echo "Este e-mail já está cadastrado!";
            exit; // Termina a execução do script aqui para evitar duplicatas
        } else {
            $insert_sql = "INSERT INTO usuario (email, password) VALUES ('$email', '$password')";

            if ($mysqli->query($insert_sql) === TRUE) {
                echo "sucesso"; // Retornando 'sucesso' para indicar um cadastro bem-sucedido
                exit; // Termina a execução do script aqui
            } else {
                echo "Erro ao cadastrar: " . $mysqli->error;
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

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "cadastro.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4) {
                    if (xhr.status == 200) {
                        // Resposta recebida com sucesso
                        if (xhr.responseText.trim() === "sucesso") { // Verifica se a resposta do servidor é "sucesso"
                            // Se o cadastro foi bem-sucedido, atualiza a interface
                            document.getElementById("mensagem").innerHTML = "Cadastro realizado com sucesso!";
                            document.getElementById("botaoCadastrar").style.display = "none"; // Remove o botão de cadastrar

                            // Adiciona um botão para redirecionar para a página de login
                            var btnLogin = document.createElement("submit");
                            btnLogin.innerHTML = "Volte à tela de Login";
                            btnLogin.style.backgroundColor = "#007bff"; // Cor de fundo azul
                            btnLogin.style.color = "#fff"; // Cor do texto branco
                            btnLogin.style.padding = "5px 5px"; // Espaçamento interno
                            btnLogin.style.border = "none"; // Sem borda
                            btnLogin.style.cursor = "pointer"; // Cursor de mão
                            btnLogin.style.borderRadius = "10px"; // Borda arredondada
                            btnLogin.style.marginTop = "10px"; // Margem superior
                            btnLogin.onclick = function() {
                                window.location.href = "index.php"; // Redireciona para a página de login
                            };
                            document.getElementById("cadastroForm").appendChild(btnLogin);
                        } else {
                            // Se a resposta do servidor não contém "sucesso", exibe a mensagem de erro
                            document.getElementById("mensagem").innerHTML = xhr.responseText;
                        }
                    } else {
                        // Erro ao enviar a requisição AJAX
                        document.getElementById("mensagem").innerHTML = "Erro ao realizar a requisição. Tente novamente.";
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