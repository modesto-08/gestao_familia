<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('../config/conexao.php');
session_start();
include('./config/conexao.php');

$titulo = $_POST['titulo'];
$usuario_id = $_POST['usuario_id'];
$categoria_id = $_POST['categoria_id'];
$pontos = $_POST['pontos'];
$data_inicio = $_POST['data_inicio'];

$sql = "INSERT INTO tarefas (titulo, usuario_id, categoria_id, pontos, data_inicio, status) 
        VALUES ('$titulo', '$usuario_id', '$categoria_id', '$pontos', '$data_inicio', 'pendente')";

if($mysqli->query($sql)) {
    header("Location: ../pages/calendario.php");
} else {
    echo "Erro ao salvar: " . $mysqli->error;
}
?>