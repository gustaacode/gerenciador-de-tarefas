<?php
$usuario = 'root'; //Usuário ADM, pode editar o banco de dados
$senha = ''; //Usado senha somente em ambientes de produção(empresarial, mercado de trabalho), próprio para a segurança do banco;
$dataBase = 'gen_tarefas'; //Nome do banco de dados 
$host = 'localhost'; //Endereço onde o banco está sendo executado


$mysqli = new mysqli($host, $usuario, $senha, $dataBase); //Conectando com o banco de dados (é usado também para realizar operações e consultas no banco) 

if ($mysqli->connect_error) {
    die("Falha ao conectar o Banco de Dados" . $mysqli->connect_error); //Caso não tenha a conexão do banco, essa mensagem será exibida
}