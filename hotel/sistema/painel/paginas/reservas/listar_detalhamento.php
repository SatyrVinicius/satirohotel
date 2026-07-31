<?php 
$tabela = 'reservas';
require_once("../../../conexao.php");
$id_reserva = @$_POST['id_reserva'];
$id = @$_POST['id_reserva'];

$total_servicos = 0;
$total_consumo = 0;
$total_final = 0;
$total_servicosF = 0;
$total_consumoF = 0;
$total_finalF = 0;

$query = $pdo->query("SELECT * from receber where (referencia = 'Venda' or referencia = 'Serviço') and pago = 'Não' and id_ref = '$id' order by data_lanc asc, hora asc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);

if($linhas > 0){

echo <<<HTML
	<div style="border-bottom:1px solid #000; margin-top: 15px">DETALHAMENTO DE CONSUMO</div>
	<small>
	<table class="table table-hover" id="">
	<thead> 
	<tr> 

	<th>Descrição</th>	
	<th>Valor</th>
	<th>Data</th>
	<th>Hora</th>
	<th>Hóspede</th>
	<th>Funcionário</th>
	<th>Ações</th>
	</tr> 
	</thead> 
	<tbody>	

HTML;





for($i=0; $i<$linhas; $i++){

	$id = $res[$i]['id'];
$descricao = $res[$i]['descricao'];
$valor = $res[$i]['valor'];
$data_lanc = $res[$i]['data_lanc'];
$data_venc = $res[$i]['data_venc'];
$data_pgto = $res[$i]['data_pgto'];
$usuario_lanc = $res[$i]['usuario_lanc'];
$usuario_pgto = $res[$i]['usuario_pgto'];
$arquivo = $res[$i]['arquivo'];
$pago = $res[$i]['pago'];
$obs = $res[$i]['obs'];
$hospede = $res[$i]['hospede'];
$hora = $res[$i]['hora'];
$referencia = $res[$i]['referencia'];

$data_lancF = implode('/', array_reverse(explode('-', $data_lanc)));
$data_vencF = implode('/', array_reverse(explode('-', $data_venc)));
$data_pgtoF = implode('/', array_reverse(explode('-', $data_pgto)));

$valorF = @number_format($valor, 2, ',', '.');
$horaF = date("H:i", strtotime($hora));


$query2 = $pdo->query("SELECT * FROM usuarios where id = '$usuario_lanc'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_usu_lanc = $res2[0]['nome'];
}else{
	$nome_usu_lanc = 'Sem Usuário';
}



$query2 = $pdo->query("SELECT * FROM hospedes where id = '$hospede'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_hospede = $res2[0]['nome'];
}else{
	$nome_hospede = '';
}

if($referencia == 'Venda'){
	$total_consumo += $valor;
}else{
	$total_servicos += $valor;
}

$total_final = $total_consumo + $total_servicos;
$total_consumoF = @number_format($total_consumo, 2, ',', '.');
$total_servicosF = @number_format($total_servicos, 2, ',', '.');
$total_finalF = @number_format($total_final, 2, ',', '.');	

echo <<<HTML

<tr class="">
<td>{$descricao}</td>
<td>R$ {$valor}</td>
<td>{$data_lancF}</td>
<td>{$horaF}</td>
<td>{$nome_hospede}</td>
<td>{$nome_usu_lanc}</td>
<td>

		

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Confirmar Exclusão? <a href="#" onclick="excluirDetalhamento('{$id}')"><span class="text-danger">Sim</span></a></p>

		</div>

		</li>										

		</ul>

		</li>





		</td>

</tr>

HTML;



}



echo <<<HTML

</tbody>

<small><div align="center" id=""></div></small>

</table>

</small>
<div style="display: flex; justify-content: flex-end; gap: 20px; font-size: 12px;">
    <div><b>Total Serviços:</b> R$ {$total_servicosF}</div>
    <div><b>Total Consumo:</b> R$ {$total_consumoF}</div>
    <div><b>Total Geral:</b> <span style="color:red">R$ {$total_finalF}</span></div>
</div>
HTML;





}else{

	echo '';

}

?>


<script type="text/javascript">
	function excluirDetalhamento(id){	

    $('#mensagem-excluir').text('Excluindo...')    

    $.ajax({
        url: 'paginas/' + pag + "/excluir_detalhamento.php",
        method: 'POST',
        data: {id},
        dataType: "html",

        success:function(mensagem){
            if (mensagem.trim() == "Excluído com Sucesso") {        
            			
                listarDetalhamento();
            } else {

                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)

            }

        }

    });

}
</script>