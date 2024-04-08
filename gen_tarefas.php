<?php
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

if (!isset($_SESSION)) { // Verifica se existe uma sessão iniciada
    session_start(); // Inicia uma sessão caso não tenha
}

// Verifique se a variável de sessão está definida e obtenha seu valor
if (isset($_SESSION['id_user'])) {
    $id_do_usuario = $_SESSION['id_user'];


    $sql = "INSERT INTO tarefas (id_user, descript) VALUES ('$id_do_usuario', '$descricao')"; // Constrói a consulta SQL

    mysqli_query($conn, $sql);
    // Executa a consulta SQL 

    // Use o valor da variável de sessão onde for necessário
    echo "O ID do usuário é: " . $id_do_usuario;
} else {
    echo "O ID do usuário não está definido na sessão.";
}

// Adiciona uma nova tarefa
if (isset($_POST['new-task'])) {
    // Obtém a descrição da tarefa do   
    $descricao = $_POST['descript'];

    // Obtém o ID do usuário logado
    $id_do_usuario = $_SESSION['id_user'];
}

// Editar uma tarefa
if (isset($_POST['edit'])) {
    $task_id = $_POST['id_task'];
    $descricao = $_POST['descript'];
    $id_do_usuario = $_SESSION['id_user']; // Obtém o ID do usuário logado

    // Verifica se a tarefa pertence ao usuário logado
    $sql_check = "SELECT * FROM tarefas WHERE id_task = $task_id AND id_user = $id_do_usuario";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Atualiza a tarefa apenas se ela pertencer ao usuário logado
        $sql_update = "UPDATE tarefas SET descript = '$descricao' WHERE id_task = $task_id";
        if ($conn->query($sql_update) === TRUE) {
            echo "Tarefa atualizada com sucesso!";
        } else {
            echo "Erro ao atualizar a tarefa: " . $conn->error;
        }
    } else {
        echo "Você não tem permissão para editar esta tarefa.";
    }
}

// Excluir uma tarefa
if (isset($_POST['delete'])) {
    $task_id = $_POST['id_task'];
    $id_do_usuario = $_SESSION['id_user']; // Obtém o ID do usuário logado

    // Verifica se a tarefa pertence ao usuário logado
    $sql_check = "SELECT * FROM tarefas WHERE id_task = $task_id AND id_user = $id_do_usuario";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        // Exclui a tarefa apenas se ela pertencer ao usuário logado
        $sql_delete = "DELETE FROM tarefas WHERE id_task = $task_id";
        if ($conn->query($sql_delete) === TRUE) {
            echo "Tarefa excluída com sucesso!";
        } else {
            echo "Erro ao excluir a tarefa: " . $conn->error;
        }
    } else {
        echo "Você não tem permissão para excluir esta tarefa.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>To Do List With Local Storage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="gen_style.css" />
</head>

<body>
    <div class="container">
        <div id="new-task">
            <input type="text" placeholder="Enter The Task Here..." />
            <button id="push" name="new-task">Add</button>
        </div>
        <div id="tasks"></div>
    </div>
    <script>
        //Initial References
        const newTaskInput = document.querySelector("#new-task input");
        const tasksDiv = document.querySelector("#tasks");
        let count;

        //Function on window load
        window.onload = () => {
            count = Object.keys(localStorage).length;
            displayTasks();
        };

        //Function to Display The Tasks
        const displayTasks = () => {
            if (Object.keys(localStorage).length > 0) {
                tasksDiv.style.display = "inline-block";
            } else {
                tasksDiv.style.display = "none";
            }

            //Clear the tasks
            tasksDiv.innerHTML = "";

            //Fetch All The Keys in local storage
            let tasks = Object.keys(localStorage);
            tasks = tasks.sort();

            for (let key of tasks) {
                let classValue = "";

                //Get all values
                let value = localStorage.getItem(key);
                let taskInnerDiv = document.createElement("div");
                taskInnerDiv.classList.add("task");
                taskInnerDiv.setAttribute("id", key);
                taskInnerDiv.innerHTML = `<span id="taskname">${key.split("_")[1]}</span>`;
                //localstorage would store boolean as string so we parse it to boolean back
                let editButton = document.createElement("button");
                editButton.classList.add("edit");
                editButton.innerHTML = `<i class="fas fa-pen"></i>`;
                if (!JSON.parse(value)) {
                    editButton.style.visibility = "visible";
                } else {
                    editButton.style.visibility = "hidden";
                    taskInnerDiv.classList.add("completed");
                }
                taskInnerDiv.appendChild(editButton);
                taskInnerDiv.innerHTML += `<button class="delete"><i class="fas fa-trash"></i></button>`;
                tasksDiv.appendChild(taskInnerDiv);
            }
        };

        //Edit and Delete Task
        tasksDiv.addEventListener("click", (e) => {
            if (e.target.classList.contains("edit")) {
                e.stopPropagation();
                disableButtons(true);
                let parent = e.target.parentElement;
                newTaskInput.value = parent.querySelector("#taskname").innerText;
                localStorage.setItem("updateNote", parent.id);
            }

            if (e.target.classList.contains("delete")) {
                e.stopPropagation();
                let parent = e.target.parentElement;
                localStorage.removeItem(parent.id);
                parent.remove();
                count -= 1;
            }
        });

        //Disable Edit Button
        const disableButtons = (bool) => {
            let editButtons = document.getElementsByClassName("edit");
            Array.from(editButtons).forEach((element) => {
                element.disabled = bool;
            });
        };

        //Function To Add New Task
        document.querySelector("#push").addEventListener("click", () => {
            disableButtons(false);
            if (newTaskInput.value.length == 0) {
                alert("Please Enter A Task");
            } else {
                if (localStorage.getItem("updateNote") == null) {
                    localStorage.setItem(count + "_" + newTaskInput.value, false);
                } else {
                    let existingCount = localStorage.getItem("updateNote").split("_")[0];
                    localStorage.removeItem(localStorage.getItem("updateNote"));
                    localStorage.setItem(existingCount + "_" + newTaskInput.value, false);
                    localStorage.removeItem("updateNote");
                }
                count += 1;
                newTaskInput.value = "";
                displayTasks();
            }
        });
    </script>
</body>

</html>