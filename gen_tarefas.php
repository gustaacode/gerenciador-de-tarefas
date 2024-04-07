<?php

if (!isset($_SESSION)) { //Verifica se existe uma sessão iniciada;
    session_start(); //Inicia uma sessão caso não tenha;
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
            <button id="push">Add</button>
        </div>
        <div id="tasks"></div>
    </div>
    <script src="script.js"></script>
</body>

</html>