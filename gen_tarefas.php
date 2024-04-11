<?php
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');
session_start(); // Inicia uma sessão;

if (isset($_SESSION['id_user'])) {
    $id_do_usuario = $_SESSION['id_user'];

    // Verifica se a variável $descricao está definida antes de usá-la
    if (isset($_POST['descript'])) {
        $descricao = $_POST['descript'];
        $sql = "INSERT INTO tarefas (id_user, descript) VALUES ('$id_do_usuario', '$descricao')";
        mysqli_query($mysqli, $sql);
        echo "Tarefa inserida com sucesso!";
        exit; // Importante: encerra o script após enviar os dados
    }

    // Obter todas as tarefas do usuário atual
    if (isset($_POST['getTasks'])) {
        $sql_tasks = "SELECT * FROM tarefas WHERE id_user = $id_do_usuario";
        $result_tasks = $mysqli->query($sql_tasks);
        $tasks_output = "";
        if ($result_tasks->num_rows > 0) {
            while ($row = $result_tasks->fetch_assoc()) {
                $tasks_output .= "<div class='task'>" . $row['descript'] . "</div>";
            }
            echo $tasks_output;
        } else {
            echo "Nenhuma tarefa encontrada.";
        }
        exit; // Importante: encerra o script após enviar os dados
    }
} else {
    echo "O ID do usuário não está definido na sessão.";
    exit; // Importante: encerra o script após enviar os dados
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
    <link rel="icon" href="imagens/task_icon.svg">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="gen.css">
</head>

<body>
    <div class="container">
        <h1>Gerenciador de Tarefas</h1>
        <input type="text" class="input-task" name="descript" placeholder="Adicione sua tarefa aqui...">
        <button class="btn-add-task" id="push">Adicionar</button>
        <div id="tasks"></div>
    </div>

    <script>
        //Function To Add New Task
        document.querySelector("#push").addEventListener("click", () => {
            // Obter a descrição da nova tarefa
            let descricao = document.querySelector("input[name='descript']").value;

            // Verificar se a descrição está vazia
            if (descricao.trim() === "") {
                alert("Por favor, adicione a tarefa...");
                return; // Não faça nada se a descrição estiver vazia
            }

            // Criar uma instância do objeto XMLHttpRequest
            var xhr = new XMLHttpRequest();

            // Configurar a requisição
            xhr.open("POST", "gen_tarefas.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Lidar com a resposta
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Atualizar a lista de tarefas após inserção bem-sucedida
                        if (xhr.responseText.includes("sucesso")) {
                            // Atualizar a lista de tarefas
                            fetchTasks();
                        }
                    } else {
                        // Exibir mensagem de erro se a requisição falhar
                        alert("Erro ao adicionar tarefa.");
                    }
                }
            };

            // Enviar a solicitação com os dados da tarefa
            xhr.send("descript=" + encodeURIComponent(descricao));
        });

        // Exibir todas as tarefas do banco de dados
        const fetchTasks = () => {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "gen_tarefas.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Atualizar a lista de tarefas na interface
                        document.getElementById("tasks").innerHTML = xhr.responseText;
                    } else {
                        alert("Erro ao buscar tarefas.");
                    }
                }
            };
            xhr.send("getTasks=true");
        };

        // Atualize a função displayTasks para incluir um botão para excluir cada tarefa
        const displayTasks = (tasks) => {
            const tasksDiv = document.querySelector("#tasks");
            tasksDiv.innerHTML = "";

            tasks.forEach((task, index) => {
                let taskDiv = document.createElement("div");
                taskDiv.classList.add("task");
                taskDiv.innerHTML = `
            <span class="taskname">${task}</span>
            <button class="delete-task" data-index="${index}">Delete</button>
        `;
                tasksDiv.appendChild(taskDiv);
            });

            tasksDiv.style.display = "block";
        };

        // Adicione um evento de clique para os botões de exclusão
        document.getElementById("tasks").addEventListener("click", (e) => {
            if (e.target.classList.contains("delete-task")) {
                const index = e.target.getAttribute("data-index");
                deleteTask(index);
            }
        });

        // Função para excluir uma tarefa
        const deleteTask = (index) => {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_task.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Atualizar a lista de tarefas após excluir a tarefa
                        fetchTasks();
                    } else {
                        alert("Erro ao excluir tarefa.");
                    }
                }
            };
            xhr.send("index=" + index);
        };
    </script>
</body>

</html>