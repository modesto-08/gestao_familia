<?php
session_start();
include('../config/conexao.php');

$email = $mysqli->real_escape_string($_POST['email']);
$senha_digitada = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = '$email'";
$result = $mysqli->query($sql);

if($result && $result->num_rows > 0) {
    $dados = $result->fetch_assoc();
    
    // VERIFICAÇÃO DUPLA (Para aceitar senhas velhas e novas)
    $senha_hash_ok = password_verify($senha_digitada, $dados['senha']);
    $senha_texto_ok = ($senha_digitada === $dados['senha']);

    if ($senha_hash_ok || $senha_texto_ok) {
        // LOGIN COM SUCESSO
        $_SESSION['usuario_id'] = $dados['id'];
        $_SESSION['nome'] = $dados['nome'];
        $_SESSION['perfil'] = $dados['perfil'];
        
        header("Location: ../pages/dashboard.php");
        exit();
    } else {
        // Senha não bateu
        header("Location: ../includes/index.php?erro=login_invalido");
        exit();
    }
} else {
    // E-mail não encontrado
    header("Location: ../includes/index.php?erro=login_invalido");
    exit();
}