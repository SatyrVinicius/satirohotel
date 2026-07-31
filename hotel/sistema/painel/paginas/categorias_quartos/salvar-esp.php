<?php 

$tabela = 'especificacoes_quartos';

require_once("../../../conexao.php");

$id_esp = $_POST['id_esp'];
$especificacao = $_POST['especificacao'];

$query = $pdo->query("INSERT INTO $tabela SET cat_quartos = '$id_esp', texto = '$especificacao'");
echo 'Salvo com Sucesso';

 ?>