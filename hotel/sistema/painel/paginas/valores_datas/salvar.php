<?php 
$tabela = 'valores_datas';
require_once("../../../conexao.php");

$data_inicial = $_POST['data_inicial'];
$data_final = $_POST['data_final'];
$valor = $_POST['valor'];
$descricao = $_POST['descricao'];

$valor = str_replace('%', '', $valor);
$valor = str_replace(',', '.', $valor);

$id = $_POST['id'];



if($id == ""){

$query = $pdo->prepare("INSERT INTO $tabela SET data_inicial = :data_inicial, data_final = :data_final, valor = :valor, descricao = :descricao");


}else{

$query = $pdo->prepare("UPDATE $tabela SET data_inicial = :data_inicial, data_final = :data_final, valor = :valor, descricao = :descricao where id = '$id'");

}

$query->bindValue(":data_inicial", "$data_inicial");
$query->bindValue(":data_final", "$data_final");
$query->bindValue(":valor", "$valor");
$query->bindValue(":descricao", "$descricao");
$query->execute();



echo 'Salvo com Sucesso';

 ?>