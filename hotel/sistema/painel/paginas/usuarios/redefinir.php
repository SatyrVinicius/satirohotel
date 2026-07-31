<?php 

$tabela = 'usuarios';
require_once("../../../conexao.php");
$id = $_POST['id'];

$senha_crip = password_hash('123', PASSWORD_DEFAULT);

$pdo->query("UPDATE $tabela SET senha_crip = '$senha_crip' WHERE id = '$id' ");

?>