<?php
session_start();
include('../config/conexao.php');

$id_tarefa = $_GET['id'];

$sql = "UPDATE tarefas SET status = 'concluida' WHERE id = '$id_tarefa'";

if($mysqli->query($sql)) {
    header("Location: ../pages/dashboard.php?msg=concluido");
}
?>