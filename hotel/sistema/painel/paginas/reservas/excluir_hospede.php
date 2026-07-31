<?php 

$tabela = 'hospedes';

require_once("../../../conexao.php");



$id = $_POST['id'];



$pdo->query("UPDATE $tabela SET reserva = '0' WHERE id = '$id' ");

echo 'Excluído com Sucesso';

?>