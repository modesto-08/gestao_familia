<?php
include('../config/conexao.php');

$eventos = [];

// Buscamos as tarefas no banco de dados
$sql = "SELECT id, titulo as title, data_inicio as start, descricao as description FROM tarefas";
$resultado = $mysqli->query($sql);

while($row = $resultado->fetch_assoc()) {
    $eventos[] = $row;
}

// O FullCalendar exige que o retorno seja em formato JSON
echo json_encode($eventos);
?>