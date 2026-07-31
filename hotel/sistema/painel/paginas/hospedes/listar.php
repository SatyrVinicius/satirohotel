<?php 

$tabela = 'hospedes';

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

	<th>Nome</th>	

	<th class="esc">Telefone</th>	

	<th class="esc">Email</th>	

	<th class="esc">Documento</th>	

	<th class="esc">Cadastrado</th>

	<th>Ações</th>

	</tr> 

	</thead> 

	<tbody>	

HTML;



for($i=0; $i<$linhas; $i++){

	$id = $res[$i]['id'];

	$nome = $res[$i]['nome'];

	$telefone = $res[$i]['telefone'];

	$email = $res[$i]['email'];

	$cpf = $res[$i]['cpf'];

	$obs = $res[$i]['obs'];

	$placa = $res[$i]['placa'];

	$responsavel = $res[$i]['responsavel'];

	$data_nasc = $res[$i]['data_nasc'];

	

	$endereco = $res[$i]['endereco'];	

	$data = $res[$i]['data'];
	$marketing = $res[$i]['marketing'];
		$foto = $res[$i]['foto'];

	if ($marketing == 'Não') {
			$ocultar_mark = 'ocultar';
		} else {
			$ocultar_mark = '';
		}



	$dataF = implode('/', array_reverse(@explode('-', $data)));

	$data_nascF = implode('/', array_reverse(@explode('-', $data_nasc)));



	

echo <<<HTML

<tr>

<td>

<input type="checkbox" id="seletor-{$id}" class="form-check-input" onchange="selecionar('{$id}')">

{$nome}

</td>

<td class="esc">{$telefone}</td>

<td class="esc">{$email}</td>

<td class="esc">{$cpf}</td>

<td class="esc">{$dataF}</td>

<td>

	<big><a href="#" onclick="editar('{$id}','{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$obs}','{$placa}','{$responsavel}','{$data_nascF}','{$foto}')" title="Editar Dados"><i class="fa fa-edit text-primary"></i></a></big>



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



	<big><a href="#" onclick="mostrar('{$nome}','{$email}','{$telefone}','{$endereco}','{$cpf}','{$dataF}','{$obs}','{$placa}','{$responsavel}','{$data_nascF}','{$foto}')" title="Mostrar Dados"><i class="fa fa-info-circle text-primary"></i></a></big>





<big><a href="#" onclick="arquivo('{$id}', '{$nome}')" title="Inserir / Ver Arquivos"><i class="fa fa-file-o " style="color:#22146e"></i></a></big>



<big><a href="#" onclick="reservas('{$id}', '{$nome}')" title="Ver Reservas"><i class="fa fa-calendar" style="color:#827878"></i></a></big>


	<a class="{$ocultar_mark}" href="#" onclick="excluirMarketing('{$id}')" title="Remover do Marketing Whatsapp"><i class="fa fa-ban text-danger"></i></a>



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

	function editar(id, nome, email, telefone, endereco, cpf, obs, placa, responsavel, nasc, foto){

		$('#mensagem').text('');

    	$('#titulo_inserir').text('Editar Registro');



    	$('#id').val(id);

    	$('#nome').val(nome);

    	$('#email').val(email);

    	$('#telefone').val(telefone);

    	$('#endereco').val(endereco);

    	$('#documento').val(cpf);

    	$('#obs').val(obs);

    	$('#placa').val(placa);

    	$('#responsavel').val(responsavel).change();



    	$('#data_nascimento').val(nasc);
		$('#foto_antiga').val(foto);
		if(foto != '' && foto != 'sem-foto.png'){
			$('#target').attr('src', 'images/hospedes/' + foto);
		}else{
			$('#target').attr('src', 'images/sem-foto.png');
		}



    	$('#modalForm').modal('show');

	}





	function mostrar(nome, email, telefone, endereco, cpf, data, obs, placa, responsavel, nasc, foto){

		    	

    	$('#titulo_dados').text(nome);

    	$('#email_dados').text(email);

    	$('#telefone_dados').text(telefone);

    	$('#endereco_dados').text(endereco);

    	$('#cpf_dados').text(cpf);

    	$('#data_dados').text(data);

    	$('#obs_dados').text(obs);

    	$('#placa_dados').text(placa);

    	$('#responsavel_dados').text(responsavel);

    	$('#nasc_dados').text(nasc);
		if(foto != '' && foto != 'sem-foto.png'){
			$('#foto_dados').attr('src', 'images/hospedes/' + foto);
		}else{
			$('#foto_dados').attr('src', 'images/sem-foto.png');
		}

    	    	



    	$('#modalDados').modal('show');

	}



	function limparCampos(){

		$('#id').val('');

    	$('#nome').val('');

    	$('#email').val('');

    	$('#telefone').val('');

    	$('#endereco').val('');

    	$('#obs').val('');

    	$('#cpf').val('');

    	$('#placa').val('');
		$('#foto_antiga').val('');
		$('#foto').val('');
		$('#target').attr('src', 'images/sem-foto.png');



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





	function arquivo(id, nome){

    $('#id-arquivo').val(id);    

    $('#nome-arquivo').text(nome);

    $('#modalArquivos').modal('show');

    $('#mensagem-arquivo').text(''); 

    listarArquivos();   

}





function reservas(id, nome){

    $('#id-reservas').val(id);    

    $('#nome-reservas').text(nome);

    $('#modalReservas').modal('show');   

    listarReservas();   

}

</script>



<script>
	// EXCLUIR MARKETING #######################################
	function excluirMarketing(id) {
		const swalWithBootstrapButtons = Swal.mixin({
			customClass: {
				confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
				cancelButton: "btn btn-danger me-1"
			},
			buttonsStyling: false
		});
		swalWithBootstrapButtons.fire({
			title: "Excluir do Marketing?",
			text: "Ele não receberá mais mensagens!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonText: "Sim, Excluir!",
			cancelButtonText: "Não, Cancelar!",
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'paginas/' + pag + "/excluir_marketing.php",
					method: 'POST',
					data: { id },
					dataType: "html",

					success: function (mensagem) {
						if (mensagem.trim() == "Excluído com Sucesso") {

							// Ação de exclusão aqui
							Swal.fire({
								title: 'Excluido com Sucesso!',
								text: 'Fecharei em 1 segundo.',
								icon: "success",
								timer: 1000
							})
							//excluido();
							listar();
							limparCampos();


						} else {
							$('#mensagem-excluir').addClass('text-danger')
							$('#mensagem-excluir').text(mensagem)
						}
					}
				});
			} else if (result.dismiss === Swal.DismissReason.cancel) {
				swalWithBootstrapButtons.fire({
					title: "Cancelado",
					text: "Fecharei em 1 segundo.",
					icon: "error",
					timer: 1000,
					timerProgressBar: true,
				});
			}
		});
	}



	

</script>