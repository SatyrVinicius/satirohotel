<?php 

require_once("../../../conexao.php");

$pagina = 'especificacoes_quartos';

$id_cat = $_POST['id'];



echo <<<HTML

<small>

HTML;

$query = $pdo->query("SELECT * FROM $pagina where cat_quartos = '$id_cat' order by id desc");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_reg = @count($res);

if($total_reg > 0){

echo <<<HTML

	<table class="table table-hover" id="">

		<thead> 

			<tr> 				

				<th>Especificação</th>						

				<th>Excluir</th>

			</tr> 

		</thead> 

		<tbody> 

HTML;

for($i=0; $i < $total_reg; $i++){

	foreach ($res[$i] as $key => $value){}

$id = $res[$i]['id'];
$nome = $res[$i]['texto'];

echo <<<HTML

			<tr>					

				<td class=""><i class="fa fa-check text-verde"></i> {$nome}</td>

					<td>
					<li class="dropdown head-dpdn2" style="display: inline-block;">

									<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-trash-o text-danger"></i></a>

									<ul class="dropdown-menu">

										<li>

											<div class="notification_desc2">

												<p>Confirmar Exclusão? <a href="#" onclick="excluirEsp('{$id}', '{$id_cat}')"><span class="text-danger">Sim</span></a></p>

												

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

	</table>

</small>

HTML;

}else{

	echo 'Não possui nenhum arquivo cadastrado!';

}



?>





<script type="text/javascript">





	$(document).ready( function () {

	    $('#tabela_arquivos').DataTable({

	    	"ordering": false,

	    	"stateSave": true,

	    });

	    $('#tabela_filter label input').focus();	    

	} );





	function excluirEsp(id, id_cat){	

    

    $.ajax({

        url: 'paginas/' + pag + "/excluir-esp.php",

        method: 'POST',

        data: {id, id_cat},

        dataType: "text",



        success: function (mensagem) {

            $('#mensagem-esp').text('');

            $('#mensagem-esp').removeClass()

            if (mensagem.trim() == "Excluído com Sucesso") {                

                listarEsp(id_cat);                

            } else {



                $('#mensagem-esp').addClass('text-danger')

                $('#mensagem-esp').text(mensagem)

            }





        },      



    });

}





</script>





