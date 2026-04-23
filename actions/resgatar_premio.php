<?php
session_start();
include('../config/conexao.php');

// Verifica se o usuário está logado e se os parâmetros foram passados
if (!isset($_SESSION['usuario_id']) || !isset($_GET['id']) || !isset($_GET['custo'])) {
    header("Location: ../pages/recompensas.php?status=erro");
    exit;
}

$id_user = $_SESSION['usuario_id'];
$id_recompensa = intval($_GET['id']);
$custo = intval($_GET['custo']);

// 1. Verificar novamente se o usuário tem saldo (segurança extra)
$check_user = $mysqli->query("SELECT pontos FROM usuarios WHERE id = '$id_user'");
$user = $check_user->fetch_assoc();

if ($user['pontos'] < $custo) {
    header("Location: ../pages/recompensas.php?status=erro");
    exit;
}

// 2. Verificar se ainda tem estoque
$check_recompensa = $mysqli->query("SELECT estoque FROM recompensas WHERE id = '$id_recompensa'");
$recompensa = $check_recompensa->fetch_assoc();

if ($recompensa['estoque'] <= 0) {
    header("Location: ../pages/recompensas.php?status=estoque_esgotado");
    exit;
}

// 3. INICIAR TRANSAÇÃO (Garante que ou faz tudo, ou não faz nada)
$mysqli->begin_transaction();

try {
    // 1. Subtrair pontos do usuário
    $mysqli->query("UPDATE usuarios SET pontos = pontos - $custo WHERE id = '$id_user'");

    // 2. Diminuir estoque da recompensa
    $mysqli->query("UPDATE recompensas SET estoque = estoque - 1 WHERE id = '$id_recompensa'");

    // 3. BUSCAR NOME DA RECOMPENSA PARA O HISTÓRICO
    $info = $mysqli->query("SELECT nome FROM recompensas WHERE id = '$id_recompensa'")->fetch_assoc();
    $nome_premio = $info['nome'];

    // 4. SALVAR NO HISTÓRICO
    $mysqli->query("INSERT INTO historico_resgates (usuario_id, recompensa_nome, custo_pontos) 
                    VALUES ('$id_user', '$nome_premio', '$custo')");

    $mysqli->commit();
    header("Location: ../pages/recompensas.php?status=sucesso");

} catch (Exception $e) {
    $mysqli->rollback();
    header("Location: ../pages/recompensas.php?status=erro");
}

exit;
?>