<?php 

$tabela = 'valores_criancas';

require_once("../../../conexao.php");



$query = $pdo->query("SELECT * from $tabela order by id desc");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$linhas = @count($res);

if($linhas > 0){

echo <<<HTML

<small>

	<table class="table table-hover" id="tabela">

	<thead> 

	<tr> 

	<th>Idade Inicial</th>
	<th>Idade Final</th>
	<th>Valor</th>
	

	<th>Ações</th>

	</tr> 

	</thead> 

	<tbody>	

HTML;



for($i=0; $i<$linhas; $i++){

	$id = $res[$i]['id'];

	$idade_inicial = $res[$i]['idade_inicial'];
	$idade_final = $res[$i]['idade_final'];
	$valor = $res[$i]['valor'];

	

echo <<<HTML

<tr>

<td>

<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">

{$idade_inicial} Anos

</td>

<td>{$idade_final} Anos</td>
<td>{$valor}% <small>(Aumento na Reserva)</small></td>
<td>

	<big><a href="#" onclick="editar('{$id}','{$idade_inicial}','{$idade_final}','{$valor}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>



	<li class="dropdown head-dpdn2" style="display: inline-block;">

		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><big><i class="fa fa-trash-o text-danger"></i></big></a>



		<ul class="dropdown-menu" style="margin-left:-230px;">

		<li>

		<div class="notification_desc2">

		<p>Confirmar Exclusão? <a href="#" onclick="excluir('{$id}')"><span class="text-danger">Sim</span></a></p>

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

<small><div align="center" id="mensagem-excluir"></div></small>

</table>

HTML;

}else{

	echo 'Nenhum registro Cadastrado!';

}

?>







<script type="text/javascript">

	$(document).ready( function () {		

    $('#tabela').DataTable({

    	"language" : {

            //"url" : '//cdn.datatables.net/plug-ins/1.13.2/i18n/pt-BR.json'

        },

        "ordering": false,

		"stateSave": true

    });

} );

</script>



<script type="text/javascript">

	function editar(id, idade_inicial, idade_final, valor){

		$('#mensagem').text('');

    	$('#titulo_inserir').text('Editar Registro');



    	$('#id').val(id);

    	$('#idade_inicial').val(idade_inicial);
    	$('#idade_final').val(idade_final);
    	$('#valor').val(valor);

    	

    	$('#modalForm').modal('show');

	}



	

	function limparCampos(){

		$('#id').val('');

    	$('#idade_inicial').val('');
    	$('#idade_final').val('');
    	$('#valor').val('');



    	$('#ids').val('');

    	$('#btn-deletar').hide();	

	}



	function selecionar(id){



		var ids = $('#ids').val();



		if($('#seletor-'+id).is(":checked") == true){

			var novo_id = ids + id + '-';

			$('#ids').val(novo_id);

		}else{

			var retirar = ids.replace(id + '-', '');

			$('#ids').val(retirar);

		}



		var ids_final = $('#ids').val();

		if(ids_final == ""){

			$('#btn-deletar').hide();

		}else{

			$('#btn-deletar').show();

		}

	}



	function deletarSel(){

		var ids = $('#ids').val();

		var id = ids.split("-");

		

		for(i=0; i<id.length-1; i++){

			excluir(id[i]);			

		}



		limparCampos();

	}

</script>