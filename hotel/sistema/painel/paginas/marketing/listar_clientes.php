<?php
require_once("../../../conexao.php");

$dataMes = Date('m');
$dataDia = Date('d');
$dataAno = Date('Y');
$data_atual = date('Y-m-d');

$data_semana = date('Y/m/d', strtotime("-7 days", strtotime($data_atual)));

@session_start();
$id_usuario = $_SESSION['id'];

$clientes = $_POST['cli'];

// Buscar os Contatos que serão enviados
if ($clientes == "Teste") {
	$resultado = $pdo->query("SELECT * FROM config where telefone != ''");

} else if ($clientes == "Clientes Mês") {
	$resultado = $pdo->query("SELECT * FROM hospedes where month(data) = '$dataMes' and year(data) = '$dataAno' and telefone != '' and (marketing = '' or marketing is null)");

} else if ($clientes == "Clientes Semana") {
	$resultado = $pdo->query("SELECT * FROM hospedes where data >= '$data_semana' and telefone != '' and (marketing = '' or marketing is null)");

} else if ($clientes == "Todos") {
	$resultado = $pdo->query("SELECT * FROM hospedes where telefone != '' and (marketing = '' or marketing is null)");

}else if ($clientes == "Aniversariantes Mês") {
	$resultado = $pdo->query("SELECT * FROM hospedes where telefone != '' and MONTH(data_nasc) = '$dataMes' and (marketing = '' or marketing is null)");
}else if ($clientes == "Aniversáriantes Dia"){
	$resultado = $pdo->query("SELECT * FROM hospedes where month(data_nasc) = '$dataMes' and day(data_nasc) = '$dataDia'  and telefone != '' and (marketing = '' or marketing is null)");	

}else if ($clientes == "Inadimplentes"){
	$resultado = $pdo->query("SELECT DISTINCT c.nome, c.telefone, c.id FROM hospedes c JOIN receber r ON c.id = r.hospede WHERE r.data_venc < CURDATE() AND r.pago = 'Não'");

}else{
	
}

$res = @$resultado->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

echo $total_reg;

?>