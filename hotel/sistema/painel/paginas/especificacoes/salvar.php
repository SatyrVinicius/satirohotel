<?php 
$tabela = 'especificacoes';
require_once("../../../conexao.php");

$nome = $_POST['nome'];
$id = $_POST['id'];
$descricao = $_POST['descricao'];
$foto = $_POST['foto'];



if($id == ""){
$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, descricao = :descricao, foto = :foto");
	
}else{
$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, descricao = :descricao, foto = :foto where id = '$id'");
}
$query->bindValue(":nome", "$nome");
$query->bindValue(":descricao", "$descricao");
$query->bindValue(":foto", "$foto");
$query->execute();

echo 'Salvo com Sucesso';
 ?>