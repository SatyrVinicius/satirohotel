<?php 
require_once("../sistema/conexao.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$documento = $_POST['documento'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'];
$diarias = $_POST['diarias'];
$hospedes = $_POST['hospedes'];

$check_in = $_POST['checkin'];
$check_out = $_POST['checkout'];
$idade_crianca_1 = $_POST['idade_crianca_1'];
$idade_crianca_2 = $_POST['idade_crianca_2'];
$idade_crianca_3 = $_POST['idade_crianca_3'];
$idade_crianca_4 = $_POST['idade_crianca_4'];
$idade_crianca_5 = $_POST['idade_crianca_5'];
$idade_crianca_6 = $_POST['idade_crianca_6'];
$idade_crianca_7 = $_POST['idade_crianca_7'];
$idade_crianca_8 = $_POST['idade_crianca_8'];
$criancas = $_POST['criancas'];

$hora_atual = date('H:i:s');

// Somo 5 minutos (resultado em int)
$horaNova = strtotime("$hora_atual + $tempo_reserva minutes");
// Formato o resultado
$hora_excluir = date("H:i:s",$horaNova);


$id_hospede = "";

$query = $pdo->query("SELECT * from quartos where tipo = '$id' order by id asc");	
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	for($i=0; $i<$linhas; $i++){
	$id_quarto = $res[$i]['id'];

			//verificar se o quarto tem checkin no dia
				$query3 = $pdo->query("SELECT * from reservas where quarto = '$id_quarto' and check_in = '$check_in' and hora_checkout is null order by id desc");
				$res3 = $query3->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res3) == 0){
				
				//verificar se nesta data já possui reserva para o quarto
				$query2 = $pdo->query("SELECT * from reservas where (quarto = '$id_quarto' and check_in <= '$check_in' and check_out > '$check_in') or (quarto = '$id_quarto' and check_in < '$check_out' and check_out > '$check_out') order by id desc");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
				if(@count($res2) == 0){

					//ACRESCENTAR ESSA CONSUTA PARA PODER VERIFICAR RESERVAS QUE ESTIVEREM ANTES DO CHECKIN E DEPOIS DO CHECKOUT DE UMA EXISTENTE, OU SEJA, QUE PEGA TODO O PERIODO DELA
					$query2 = $pdo->query("SELECT * from reservas where (quarto = '$id_quarto' and check_in < '$check_out' and check_out >= '$check_out') or (quarto = '$id_quarto' and check_in > '$check_in' and check_out <= '$check_out') order by id desc");
				$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);		
				if(@count($res2) == 0){
					break;
				}

			}

		}

	}
}

$query_ct = $pdo->query("SELECT * from categorias_quartos where id = '$id'");
$res_ct = $query_ct->fetchAll(PDO::FETCH_ASSOC);
$nome_quarto = $res_ct[0]['nome'];
$valor = $res_ct[0]['valor'];
$valorF = number_format($valor, 2, ',', '.');




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



$valor_reserva = $valor * $diarias + $valor_extra_total;
$desconto = 0;

// Loop para processar todas as 8 idades das crianças
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
            $valor_reserva += $valor_extra;  // Adiciona ao valor total
        }
    }
}

$query = $pdo->query("SELECT * from hospedes where telefone = '$telefone'");	
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$id_hospede = $res[0]['id'];
	$query = $pdo->prepare("UPDATE hospedes SET nome = :nome, cpf = :documento, responsavel = 'Sim' WHERE telefone = '$telefone'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":documento", "$documento");
	$query->execute();
	
	
}


$query = $pdo->query("SELECT * from hospedes where cpf = '$documento'");	
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$id_hospede = $res[0]['id'];
	$query = $pdo->prepare("UPDATE hospedes SET nome = :nome, telefone = :telefone, responsavel = 'Sim' WHERE cpf = '$documento'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":telefone", "$telefone");
	$query->execute();
	
	
}

if($id_hospede == ""){
	$query = $pdo->prepare("INSERT INTO hospedes SET nome = :nome, cpf = :documento, telefone = '$telefone', data = curDate(), responsavel = 'Não'");

	$query->bindValue(":nome", "$nome");
	$query->bindValue(":documento", "$documento");
	$query->execute();
	$id_hospede = $pdo->lastInsertId();
}


//excluir a reserva anterior caso o mesmo hóspede faça uma nova
$pdo->query("DELETE FROM reservas WHERE hospede = '$id_hospede' and reserva_site = 'Sim' and no_show <= 0 and hora_excluir is not null");

//salvar a reserva

$stmt = $pdo->prepare("INSERT INTO reservas 
    SET hospede = :hospede, 
        tipo_quarto = :tipo_quarto, 
        quarto = :quarto, 
        funcionario = '0', 
        check_in = :check_in, 
        check_out = :check_out, 
        valor = :valor, 
        no_show = '0', 
        hospedes = :hospedes, 
        valor_diaria = :valor_diaria, 
        data = curDate(), 
        desconto = '0', 
        forma_pgto = '0', 
        reserva_site = 'Sim', 
        hora_excluir = :hora_excluir, 
        hospedes_criancas = :hospedes_criancas");

$stmt->bindValue(':hospede', $id_hospede);
$stmt->bindValue(':tipo_quarto', $id);
$stmt->bindValue(':quarto', $id_quarto);
$stmt->bindValue(':check_in', $checkin);
$stmt->bindValue(':check_out', $checkout);
$stmt->bindValue(':valor', $valor_reserva);
$stmt->bindValue(':hospedes', $hospedes);
$stmt->bindValue(':valor_diaria', $valor);
$stmt->bindValue(':hora_excluir', $hora_excluir);
$stmt->bindValue(':hospedes_criancas', $criancas);

$stmt->execute();
$id_reserva = $pdo->lastInsertId();



// Cria array com os valores recebidos do POST
$idades = [];
for ($i = 1; $i <= 8; $i++) {
    $campo = 'idade_crianca_' . $i;
    $idades[$i] = isset($_POST[$campo]) ? $_POST[$campo] : null;
}

// Monta o SQL de atualização
$sql = "UPDATE reservas SET 
    idade_1 = :idade_1,
    idade_2 = :idade_2,
    idade_3 = :idade_3,
    idade_4 = :idade_4,
    idade_5 = :idade_5,
    idade_6 = :idade_6,
    idade_7 = :idade_7,
    idade_8 = :idade_8
    WHERE id = :id";

$stmt = $pdo->prepare($sql);

// Faz o bind dos valores
for ($i = 1; $i <= 8; $i++) {
    $stmt->bindValue(":idade_$i", $idades[$i]);
}
$stmt->bindValue(":id", $id_reserva);

$stmt->execute();

echo 'Salvo com Sucesso*'.$id_reserva;
?>