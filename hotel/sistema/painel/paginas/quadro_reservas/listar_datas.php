<?php 

require_once("../../../conexao.php");

$id_quarto = @$_POST['id_quarto'];
$array_datas = '';

$query = $pdo->query("SELECT * from reservas where quarto = '$id_quarto' and check_out >= curDate()");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$linhas = @count($res);



if($linhas > 0){

for($i=0; $i<$linhas; $i++){

  $check_in = $res[$i]['check_in'];
  $check_out = $res[$i]['check_out'];
  $hora_checkout = $res[$i]['hora_checkout'];


    $inicio = new DateTime($check_in);
	$fim = new DateTime($check_out);
	$fim->modify('+1 day'); //habilitei para pegar a data atual



	$interval = new DateInterval('P1D');

	$periodo = new DatePeriod($inicio, $interval ,$fim);



	foreach($periodo as $data){
			if($hora_checkout == ""){
				echo $data->format("Y-m-d");
				if($data->format("Y-m-d") == $check_out){
					echo '_'.$data->format("Y-m-d");
				}	
			}
		
	    

	}



}



}



?>