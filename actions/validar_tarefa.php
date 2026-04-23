<?php
session_start();
include('../config/conexao.php');

$id_tarefa = $_GET['id'];
$id_usuario = $_GET['user'];
$pontos = $_GET['pts'];

// 1. Marcar a tarefa como validada
$mysqli->query("UPDATE tarefas SET status = 'validada' WHERE id = '$id_tarefa'");

// 2. Somar os pontos ao usuário
$mysqli->query("UPDATE usuarios SET pontos = pontos + $pontos WHERE id = '$id_usuario'");

header("Location: ../pages/dashboard.php?msg=pontos_entregues");
?>