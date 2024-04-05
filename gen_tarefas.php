<?php

if (!isset($_SESSION)) { //Verifica se existe uma sessão iniciada;
    session_start(); //Inicia uma sessão caso não tenha;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tarefas</title>
</head>

<body>
    Olá, <?php echo $_SESSION['nome']; ?>
</body>

</html>