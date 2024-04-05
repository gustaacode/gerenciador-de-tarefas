<?php
$usuario = 'root';
$senha = '';
$dataBase = 'gen_tarefas';
$host = 'localhost';        


$mysqli = new mysqli($host, $usuario, $senha, $dataBase); //Conectando com o banco de dados 

if ($mysqli->connect_error) {
    die("Falha ao conectar o Banco de Dados" . $mysqli->connect_error); //Caso não tenha a conexão do banco, essa mensagem será exibida
}

?>