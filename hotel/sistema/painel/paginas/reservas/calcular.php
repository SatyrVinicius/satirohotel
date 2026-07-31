<?php 
$tabela = 'quartos';
require_once("../../../conexao.php");

$checkin = @$_POST['checkin'];
$checkout = @$_POST['checkout'];
$diaria = @$_POST['diaria'];
$desconto = @$_POST['desconto'];
$idade_crianca_1 = @$_POST['idade_crianca_1'];
$idade_crianca_2 = @$_POST['idade_crianca_2'];
$idade_crianca_3 = @$_POST['idade_crianca_3'];
$idade_crianca_4 = @$_POST['idade_crianca_4'];
$idade_crianca_5 = @$_POST['idade_crianca_5'];
$idade_crianca_6 = @$_POST['idade_crianca_6'];
$idade_crianca_7 = @$_POST['idade_crianca_7'];
$idade_crianca_8 = @$_POST['idade_crianca_8'];




//calcular se o periodo da estadia pega datas especiais
$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$checkoutDate->modify('-1 day'); // opcional: se o checkout não conta como diária

$total_dias_bonus = 0;
$valor_extra_total = 0;
$texto_valor = '';

$stmt = $pdo->prepare("SELECT * FROM valores_datas WHERE data_final >= :checkin AND data_inicial <= :checkout");
$stmt->bindValue(':checkin', $checkin);
$stmt->bindValue(':checkout', $checkout);
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
        $valor_dia = $diaria; // valor da diária
        $valor_extra = ($valor_dia * $dias) * ($periodo['valor'] / 100); // aplica %
        $valor_extra_total += $valor_extra;

        $texto_valor = 'Você selecionou um período que pega datas de maiores valores para as diárias! '.$descricao_periodo;
    }
}



//verificar se as datas estão disponiveis ou se estão bloqueadas
$checkinDate = new DateTime($checkin);
$checkoutDate = new DateTime($checkout);
$checkoutDate->modify('-1 day'); // opcional: se o checkout não conta como diária
$texto_valor_bloqueio = '';

$stmt = $pdo->prepare("SELECT * FROM bloqueio_datas WHERE data_final >= :checkin AND data_inicial <= :checkout");
$stmt->bindValue(':checkin', $checkin);
$stmt->bindValue(':checkout', $checkout);
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

    if ($inicio <= $fim) {     
        $texto_valor_bloqueio = 'Você selecionou datas que estão indisponíveis para reserva! '.$nome_categoria;
    }
}



if($desconto == "" or $desconto < 0){
	$desconto = 0;
}

//calcular diferença de dias entre as datas
$diferenca = strtotime($checkout) - strtotime($checkin);
$dias = floor($diferenca / (60 * 60 * 24));
if($dias == 0){
	$dias = 1;
}

$valor_reserva = $diaria * $dias - $desconto + $valor_extra_total;

// Loop para processar todas as 8 idades das crianças
for ($i = 1; $i <= 8; $i++) {
    $idade = @$_POST['idade_crianca_' . $i];  // Captura a idade de cada criança

    if (!empty($idade)) {
        // Verifica na tabela valores_criancas a faixa correspondente
        $query = $pdo->query("SELECT * FROM valores_criancas WHERE idade_inicial <= '$idade' AND idade_final >= '$idade'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($res) > 0) {
            $valor_tabela = $res[0]['valor'];
            // Calcula o valor extra a ser adicionado
            $valor_extra = ($diaria * $dias - $desconto) * ($valor_tabela / 100);
            $valor_reserva += $valor_extra; // Adiciona ao valor da reserva
        }
    }
}



$vlr_no_show = ($valor_reserva * $no_show) / 100;
$valor_reserva = number_format($valor_reserva, 2);
$vlr_no_show = number_format($vlr_no_show, 2);
$valor_reserva = str_replace(',', '', $valor_reserva);
$vlr_no_show = str_replace(',', '', $vlr_no_show);

echo $valor_reserva.'**'.$vlr_no_show.'**'.$texto_valor.'**'.$texto_valor_bloqueio;

?>


