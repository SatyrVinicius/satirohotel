<?php 
$tabela = 'valores_criancas';
require_once("../../../conexao.php");

$idade_inicial = $_POST['idade_inicial'];
$idade_final = $_POST['idade_final'];
$valor = $_POST['valor'];

$valor = str_replace('%', '', $valor);
$valor = str_replace(',', '.', $valor);

$id = $_POST['id'];



if($id == ""){

$query = $pdo->prepare("INSERT INTO $tabela SET idade_inicial = :idade_inicial, idade_final = :idade_final, valor = :valor");


}else{

$query = $pdo->prepare("UPDATE $tabela SET idade_inicial = :idade_inicial, idade_final = :idade_final, valor = :valor where id = '$id'");

}

$query->bindValue(":idade_inicial", "$idade_inicial");
$query->bindValue(":idade_final", "$idade_final");
$query->bindValue(":valor", "$valor");
$query->execute();



echo 'Salvo com Sucesso';

 ?>