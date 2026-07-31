<?php 
$tabela = 'bloqueio_datas';
require_once("../../../conexao.php");

$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];
$categoria = $_POST['categoria'];

$id = $_POST['id'];



if($id == ""){

$query = $pdo->prepare("INSERT INTO $tabela SET data_inicial = :data_inicial, data_final = :data_final, categoria = :categoria");


}else{

$query = $pdo->prepare("UPDATE $tabela SET data_inicial = :data_inicial, data_final = :data_final, categoria = :categoria where id = '$id'");

}

$query->bindValue(":data_inicial", "$data_inicial");
$query->bindValue(":data_final", "$data_final");
$query->bindValue(":categoria", "$categoria");
$query->execute();



echo 'Salvo com Sucesso';

 ?>