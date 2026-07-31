<?php 
include("../conexao.php");

$id_pre_reserva = @$_POST['id_reserva'];

if($id_pre_reserva == ""){
	$query = $pdo->query("SELECT * from reservas where ref_pgto = '$ref_pgto'");
}else{
	$query = $pdo->query("SELECT * from reservas where id = '$id_pre_reserva'");
	$total_pago_api = 0;
}


$res = $query->fetchAll(PDO::FETCH_ASSOC);
$hospede = $res[0]['hospede'];
$tipo_quarto = $res[0]['tipo_quarto'];
$quarto = $res[0]['quarto'];
$funcionario = $res[0]['funcionario'];
$check_in = $res[0]['check_in'];
$check_out = $res[0]['check_out'];
$valor = $res[0]['valor'];
$hospedes = $res[0]['hospedes'];
$obs = $res[0]['obs'];
$valor_diaria = $res[0]['valor_diaria'];
$data = $res[0]['data'];
$desconto = $res[0]['desconto'];
$forma_pgto = $res[0]['forma_pgto'];
$id_reserva = @$res[0]['id'];

$valor = $total_pago_api;

$query2 = $pdo->query("SELECT * from hospedes where id = '$hospede'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_hospede = @$res2[0]['nome'];
$cpf = @$res2[0]['cpf'];
$telefone_hospede = @$res2[0]['telefone'];

$check_inF = implode('/', array_reverse(@explode('-', $check_in)));
$check_outF = implode('/', array_reverse(@explode('-', $check_out)));

 

if($id_pre_reserva == ""){
	$pdo->query("UPDATE reservas SET no_show = '$valor', hora_excluir = null where ref_pgto = '$ref_pgto'");
}else{
	$pdo->query("UPDATE reservas SET no_show = '0', hora_excluir = null where id = '$id_reserva'");
	$valor = '0';
}

$valorF = @number_format($valor, 2, ',', '.'); 

$pdo->query("INSERT INTO receber SET descricao = 'Entrada Reserva', valor = '$valor', data_venc = curDate(), data_lanc = curDate(), usuario_lanc = '0', arquivo = 'sem-foto.png', pago = 'Sim', data_pgto = curDate(), usuario_pgto = '0', hospede = '$hospede', referencia = 'Entrada', id_ref = '$id_reserva'");	



if($id_pre_reserva != ""){
	echo '<script>window.location="../painel/rel/reserva_class.php?id='.$id_reserva.'"</script>';
}

//api do whatsapp
if($api_whatsapp == 'Sim'){
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_hospede);
		
		$mensagem = '_*Reserva Confirmada* ('.$nome_sistema.')_ %0A';
		
		$mensagem .= 'Número da Reserva: *'.$id_reserva.'* %0A';
		$mensagem .= 'Hóspede: *'.$nome_hospede.'* %0A';
		$mensagem .= 'Telefone: *'.$telefone_hospede.'* %0A';
		$mensagem .= 'Check-In: *'.$check_inF.'* %0A';
		$mensagem .= 'Check-Out: *'.$check_outF.'* %0A';
		$mensagem .= 'Quantidade Hóspedes: *'.$hospedes.'* %0A';
		$mensagem .= 'Total Pago: *R$ '.$valorF.'* %0A%0A';
		

		$mensagem .= '_*Atenção* '.$info_reserva.'_ %0A%0A';

		$mensagem .= '_Abaixo o detalhamento em PDF da Reserva_ %0A';
		require("../painel/api/texto.php");
	}
?>