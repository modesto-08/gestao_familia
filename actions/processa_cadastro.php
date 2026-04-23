<?php
// Ativar exibição de erros para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../config/conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $mysqli->real_escape_string($_POST['nome']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $_POST['senha']; // Vamos simplificar sem hash por enquanto para testar
    $perfil = $_POST['perfil'];

    // 1. Verificar se o email já existe
    $check = $mysqli->query("SELECT id FROM usuarios WHERE email = '$email'");
    
   

    // 2. Tentar inserir
    $sql = "INSERT INTO usuarios (nome, email, senha, perfil, pontos) 
            VALUES ('$nome', '$email', '$senha', '$perfil', 0)";

    if($mysqli->query($sql)) {
        // Sucesso! Redireciona para o login
        // Verifique se o caminho ../index.php está correto na sua estrutura
        header("Location: ../pages/cadastro.php?erro=email_existe");
exit();
    } else {
        // Se der erro no SQL, ele vai mostrar aqui
        die("❌ Erro no Banco de Dados: " . $mysqli->error);
    }
} else {
    die("Acesso inválido.");
}

if($mysqli->query($sql)) {
    // Redireciona para o login passando o parâmetro de sucesso na URL
    header("Location: ../index.php?msg=sucesso"); 
    exit();
}
?>