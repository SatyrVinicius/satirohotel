<?php 
require_once("../sistema/conexao.php");

$check_in = @$_POST['checkin'];
$check_out = @$_POST['checkout'];
$diarias = @$_POST['diarias'];
$idade_crianca_1 = $_POST['idade_crianca_1'];
$idade_crianca_2 = $_POST['idade_crianca_2'];
$idade_crianca_3 = $_POST['idade_crianca_3'];
$idade_crianca_4 = $_POST['idade_crianca_4'];
$idade_crianca_5 = $_POST['idade_crianca_5'];
$idade_crianca_6 = $_POST['idade_crianca_6'];
$idade_crianca_7 = $_POST['idade_crianca_7'];
$idade_crianca_8 = $_POST['idade_crianca_8'];


$id_quarto_post = '';
$id = '';

$total_quartos = 0;

//percorrer as categorias dos quartos
$query_ct = $pdo->query("SELECT * from categorias_quartos");
$res_ct = $query_ct->fetchAll(PDO::FETCH_ASSOC);
$linhas_ct = @count($res_ct);
if($linhas_ct > 0){
	for($ic=0; $ic<$linhas_ct; $ic++){
$valor = $res_ct[$ic]['valor'];
$tipo = $res_ct[$ic]['id'];
$nome_quarto = $res_ct[$ic]['nome'];
$descricao = $res_ct[$ic]['descricao'];
$especificacoes = $res_ct[$ic]['especificacoes'];
$especificacoesF = str_replace('**', ', ', $especificacoes);
$valor = $res_ct[$ic]['valor'];
$foto = $res_ct[$ic]['foto'];


$inicio = "10";
$fim = "5";

//verificar se as datas estão disponiveis ou se estão bloqueadas
$checkinDate = new DateTime($check_in);
$checkoutDate = new DateTime($check_out);
$checkoutDate->modify('-1 day'); // opcional: se o checkout não conta como diária
$texto_valor_bloqueio = '';

$stmt = $pdo->prepare("SELECT * FROM bloqueio_datas WHERE data_final >= :checkin AND data_inicial <= :checkout");
$stmt->bindValue(':checkin', $check_in);
$stmt->bindValue(':checkout', $check_out);
$stmt->execute();
$periodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($periodos as $periodo) {
    $dataInicio = new DateTime($periodo['data_inicial']);
    $dataFim = new DateTime($periodo['data_final']);
    $categoria = $periodo['categoria'];

    $query20 = $pdo->query("SELECT * from categorias_quartos where id = '$categoria'");
	$res20 = $query20->fetchAll(PDO::FETCH_ASSOC);
	if(@count($res20) > 0){
		$nome_categoria = 'Tipo de Quarto '.$res20[0]['nome'];
	}else{
		$nome_categoria = '';
	}

    // Define o intervalo de interseção
    $inicio = $checkinDate > $dataInicio ? $checkinDate : $dataInicio;
    $fim = $checkoutDate < $dataFim ? $checkoutDate : $dataFim;

    
}

if ($inicio <= $fim) { 
	if($categoria == 0){
		continue;
	}else{
		if($categoria == $tipo){
			continue;
		}
	}    
}



//calcular se o periodo da estadia pega datas especiais
$checkinDate = new DateTime($check_in);
$checkoutDate = new DateTime($check_out);
$checkoutDate->modify('-1 day'); // opcional: se o checkout não conta como diária

$total_dias_bonus = 0;
$valor_extra_total = 0;
$texto_valor = '';

$stmt = $pdo->prepare("SELECT * FROM valores_datas WHERE data_final >= :checkin AND data_inicial <= :checkout");
$stmt->bindValue(':checkin', $check_in);
$stmt->bindValue(':checkout', $check_out);
$stmt->execute();
$periodos = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($periodos as $periodo) {
    $dataInicio = new DateTime($periodo['data_inicial']);
    $dataFim = new DateTime($periodo['data_final']);
    $descricao_periodo = $periodo['descricao'];

    // Define o intervalo de interseção
    $inicio = $checkinDate > $dataInicio ? $checkinDate : $dataInicio;
    $fim = $checkoutDate < $dataFim ? $checkoutDate : $dataFim;

    if ($inicio <= $fim) {
        $dias = $inicio->diff($fim)->days + 1; // +1 para incluir o último dia
        $total_dias_bonus += $dias;

        // Calcula o extra proporcional
        $valor_dia = $valor; // valor da diária
        $valor_extra = ($valor_dia * $diarias) * ($periodo['valor'] / 100); // aplica %
        $valor_extra_total += $valor_extra;
        
    }
}


$total_valor = $valor * $diarias + $valor_extra_total;  // Valor inicial

// Loop para processar todas as 8 idades das crianças
for ($i = 1; $i <= 8; $i++) {
    $idade = @$_POST['idade_crianca_' . $i];  // Captura a idade de cada criança

    if (!empty($idade)) {
        // Verifica na tabela valores_criancas a faixa correspondente
        $query = $pdo->query("SELECT * FROM valores_criancas WHERE idade_inicial <= '$idade' AND idade_final >= '$idade'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) > 0) {
            $valor_tabela = $res[0]['valor'];  // Valor para a faixa etária

            // Calcula o valor extra com base no valor inicial
            $valor_extra = $valor * $diarias * ($valor_tabela / 100);  // Calcula o valor extra com base no valor inicial
            $total_valor += $valor_extra;  // Adiciona ao valor total
        }
    }
}


$total_valorF = number_format($total_valor, 2, ',', '.');

//excluir reservas pendentes de finalização
$pdo->query("DELETE FROM reservas WHERE reserva_site = 'Sim' and no_show <= 0 and hora_excluir < curTime()");  

$query = $pdo->query("SELECT * from quartos where ativo = 'Sim' and tipo = '$tipo' order by numero asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	for($i=0; $i<$linhas; $i++){
		$id_quarto = $res[$i]['id'];

		//verificar se o quarto tem checkin no dia
		$query3 = $pdo->prepare("SELECT * from reservas where quarto = '$id_quarto' and check_in = :check_in and hora_checkout is null order by id desc");
		$query3->bindValue(":check_in", "$check_in");
		$query3->execute();
		$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res3) == 0){
		
		//verificar se nesta data já possui reserva para o quarto
		$query2 = $pdo->prepare("SELECT * from reservas where (quarto = '$id_quarto' and check_in <= :check_in and check_out > :check_in) or (quarto = '$id_quarto' and check_in < :check_out and check_out > :check_out) order by id desc");
		$query2->bindValue(":check_in", "$check_in");
		$query2->bindValue(":check_out", "$check_out");
		$query2->execute();
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res2) == 0){

			//ACRESCENTAR ESSA CONSUTA PARA PODER VERIFICAR RESERVAS QUE ESTIVEREM ANTES DO CHECKIN E DEPOIS DO CHECKOUT DE UMA EXISTENTE, OU SEJA, QUE PEGA TODO O PERIODO DELA
			$query2 = $pdo->prepare("SELECT * from reservas where (quarto = '$id_quarto' and check_in < :check_out and check_out >= :check_out) or (quarto = '$id_quarto' and check_in > :check_in and check_out <= :check_out) order by id desc");
			$query2->bindValue(":check_in", "$check_in");
		$query2->bindValue(":check_out", "$check_out");
		$query2->execute();
		$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);		
		if(@count($res2) == 0){

			$total_quartos += 1;

			echo '<div class="section-top-border">

			<h3 class="mb-30 title_color">'.$nome_quarto.'<span style="color:green"> R$ '.$total_valorF.'</span> <a style="margin-left:10px" href="#" onclick="comprar('.$tipo.')" title="Reservar Quarto" class="btn theme_btn button_hover">Reservar</a> </h3>
			<div class="row">
				<div class="col-lg-10">
					<blockquote class="generic-blockquote">
					<strong><span style="color:blue;">Descrição:</span> </strong><span style="font-size:13px">'.$descricao.'</span> <br>';
							
					$query20 = $pdo->query("SELECT * FROM especificacoes_quartos where cat_quartos = '$tipo' order by id asc");
$res20 = $query20->fetchAll(PDO::FETCH_ASSOC);
$total_reg20 = @count($res20);
if($total_reg20 > 0){
for($i20=0; $i20 < $total_reg20; $i20++){
$nome20 = $res20[$i20]['texto'];

					echo '<div style="border-bottom: 1px solid #959595; padding:2px; font-size:12px"><i class="fa fa-check text-verde"></i>'.$nome20.'</div>';
					} }

					echo '</blockquote>
				</div>
				<div class="col-lg-2" align="center">
				<img src="sistema/painel/images/quartos/'.$foto.'" width="250px">
				</div>
			</div>
		</div>';	

		break;	

			}

			
		}	

		}

	} }else{
		echo '';
	 } 

	}

}else{
	echo '';
}

if($total_quartos == 0){
	echo '<div style="color: red; font-size: 18px; font-weight: bold; padding: 10px; background-color: #ffe6e6; border: 1px solid red; border-radius: 5px; margin-bottom:10px">
    ❗ Nenhum quarto disponível nessa data!
</div>';
}
?>

