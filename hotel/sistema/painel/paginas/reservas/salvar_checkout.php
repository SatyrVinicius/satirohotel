<?php 

require_once("../../../conexao.php");



@session_start();

$id_usuario = @$_SESSION['id'];



$forma_pgto_checkout = $_POST['forma_pgto_checkout'];

$obs = $_POST['obs_checkout'];

$valor_checkout = $_POST['valor_checkout'];

$id = $_POST['id_checkout'];
$descricao_taxa = $_POST['descricao_taxa'];
$valor_taxa = $_POST['valor_taxa'];
$valor_taxa = str_replace(',', '.', $valor_taxa);

if($valor_taxa == ""){
	$valor_taxa = 0;
}

if($valor_taxa > 0 and $descricao_taxa == ""){
	echo 'Preencha a descrição da taxa!';
	exit();
}

$valor_checkout += $valor_taxa;

$query = $pdo->query("SELECT * from reservas where id = '$id'");	

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$hospedes = $res[0]['hospedes'];

$hospede = $res[0]['hospede'];

$quarto = $res[0]['quarto'];

$valor = $res[0]['valor'];

$no_show = $res[0]['no_show'];

$valor_checkin = $res[0]['valor_checkin'];



$restante_reserva = $valor - $no_show - $valor_checkin;



$query = $pdo->query("SELECT * from quartos where id = '$quarto'");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$id_quarto = $res[0]['id'];





$query = $pdo->prepare("UPDATE reservas SET funcionario_checkout = '$id_usuario', obs = :obs, hora_checkout = curTime(), check_out = curDate(), valor_checkout = '$valor_checkout', tipo_pgto_checkout = '$forma_pgto_checkout', descricao_taxa = :descricao_taxa, valor_taxa = :valor_taxa WHERE id = '$id'");



$query->bindValue(":obs", "$obs");
$query->bindValue(":descricao_taxa", "$descricao_taxa");
$query->bindValue(":valor_taxa", "$valor_taxa");

$query->execute();

echo 'Salvo com Sucesso';


//verificar caixa aberto
$query1 = $pdo->query("SELECT * from caixas where operador = '$id_usuario' and data_fechamento is null order by id desc limit 1");
$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
if(@count($res1) > 0){
	$id_caixa = @$res1[0]['id'];
}else{
	$id_caixa = 0;
}
//  


//lançar o valor no recebimento

if($valor_checkout > 0){

$pdo->query("UPDATE receber SET pago = 'Sim', data_pgto = curDate(), usuario_pgto = '$id_usuario', hora = curTime(), forma_pgto = '$forma_pgto_checkout', caixa = '$id_caixa' where id_ref = '$id' and (referencia = 'Venda' or referencia = 'Serviço') and pago = 'Não'");

}





//lancar restante da reserva nas contas receber

if($restante_reserva > 0){

	$pdo->query("INSERT INTO receber SET descricao = 'Restante Reserva Checkout', valor = '$restante_reserva', data_venc = curDate(), data_lanc = curDate(), usuario_lanc = '$id_usuario', arquivo = 'sem-foto.png', pago = 'Sim', data_pgto = curDate(), usuario_pgto = '$id_usuario', hospede = '$hospede', referencia = 'Restante', id_ref = '$id', forma_pgto = '$forma_pgto_checkout', hora = curTime(), caixa = '$id_caixa'");

}





?>