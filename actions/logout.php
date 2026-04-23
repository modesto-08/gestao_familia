<?php
session_start(); // Primeiro, localiza a sessão atual
session_unset(); // Remove todas as variáveis da sessão
session_destroy(); // Destrói a sessão de vez

// Redireciona para o login (que está em includes/index.php)
header("Location: ../includes/index.php");
exit();
?>