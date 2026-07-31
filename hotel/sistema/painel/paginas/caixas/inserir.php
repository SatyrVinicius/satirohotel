<?php 

$tabela = 'caixas';

require_once("../../../conexao.php");

@session_start();
$id_usuario = @$_SESSION['id'];

$operador = $_POST['operador'];
$valor_abertura = $_POST['valor_abertura'];
$valor_abertura = str_replace(',', '.', $valor_abertura);
$data_abertura = $_POST['data_abertura'];

$obs = $_POST['obs'];

$id = $_POST['id'];


//verificar se o caixa já está aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$data_abertura = @$res1[0]['data_abertura'];
	$data_aberturaF = @implode('/', array_reverse(explode('-', $data_abertura)));	
	echo 'O caixa já está aberto para esse operador, foi aberto dia '.$data_aberturaF;
	exit();
}


if($id == ""){

	$query = $pdo->prepare("INSERT INTO $tabela SET operador = '$operador', data_abertura = '$data_abertura', valor_abertura = :valor_abertura, usuario_abertura = '$id_usuario', obs = :obs");	

}else{

	$query = $pdo->prepare("UPDATE $tabela SET operador = '$operador', data_abertura = '$data_abertura', valor_abertura = :valor_abertura, usuario_abertura = '$id_usuario', obs = :obs where id = '$id'");

}



$query->bindValue(":valor_abertura", "$valor_abertura");
$query->bindValue(":obs", "$obs");

$query->execute();

echo 'Salvo com Sucesso'; 

?>