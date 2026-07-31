<?php 
$tabela = 'hospedes';

require_once("../../../conexao.php");

$id = $_POST['id_reserva'];
$hospede = $_POST['hospede'];


$pdo->query("UPDATE $tabela SET reserva = '$id' WHERE id = '$hospede' ");


?>