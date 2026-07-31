<?php 

$pag = 'filtrar_reservas';



//verificar se ele tem a permissão de estar nessa página

if(@$filtrar_reservas == 'ocultar'){

    echo "<script>window.location='../index.php'</script>";

    exit();

}



 ?>

<div class="margin_mobile row">



<form method="post" id="formFiltroReservas">

<button type="button" style="position:absolute; right:30px" class="btn btn-success" onclick="gerarRelatorioReservas()"><span class="fa fa-file-pdf-o"></span> Relatório</button>



<select class="form-control estilo_block" style="width:150px;" id="filtro" name="filtro" onchange="buscar()">

	<option value="check_in">Data Check-In</option>

	<option value="check_out">Data Check-Out</option>

	<option value="data">Data Lançamento</option>

</select>

<i class="fa fa-search text-primary" style="margin-right: 20px"></i>



<span class="estilo_block">

<span style="margin-right: 5px;"><small>Data Inicial</small></span>

<input type="date" id="dataInicial" name="dataInicial" class="form-control input_data" value="<?php echo $data_atual ?>" onchange="buscar()">

</span>



<span class="estilo_block">

<span style="margin-right: 5px;"><small>Data Final</small></span>

<input type="date" id="dataFinal" name="dataFinal" class="form-control input_data" value="<?php echo $data_atual ?>" onchange="buscar()">

</span>



<input style="width:100px" class="form-control estilo_block" type="text" name="id_reserva" placeholder="ID Reserva" id="id_da_reserva">

<button class="btn btn-info estilo_block" type="button" onclick="buscar()"><i class="fa fa-search"></i></button>



</form>

</div>								


<div class="modal fade" id="modalGerandoPdfFiltro" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-sm modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Gerando PDF</h4>
			</div>
			<div class="modal-body" style="text-align:center;">
				<div style="font-size: 28px; margin-bottom: 8px;"><i class="fa fa-spinner fa-spin text-primary"></i></div>
				<div id="statusGerandoPdfFiltro">Preparando relatório...</div>
				<small class="text-muted">A geração pode demorar alguns segundos.</small>
			</div>
		</div>
	</div>
</div>



<div class="bs-example widget-shadow" style="padding:15px" id="listar">



</div>







<script type="text/javascript">var pag = "<?=$pag?>"</script>

<script src="js/ajax.js"></script>



<script type="text/javascript">



	$(document).ready( function () {	

		 $('.sel').select2({



    	});

	});



	function buscar(){

		var busca = $('#filtro').val();

		var dataInicial = $('#dataInicial').val();

		var dataFinal = $('#dataFinal').val();

		var codigo = $('#id_da_reserva').val();		

		listar(busca, dataInicial, dataFinal, codigo);

		$('#id_da_reserva').val('');

	}

	function abrirPdfAssincrono(urlPreparar, dados, label){
		dados = dados || {};
		dados._tentativas = dados._tentativas || 0;
		$('#statusGerandoPdfFiltro').text(label ? label : 'Preparando relatório...');
		$('#modalGerandoPdfFiltro').modal({backdrop: 'static', keyboard: false});
		$('#modalGerandoPdfFiltro').modal('show');

		$.ajax({
			url: urlPreparar,
			type: 'POST',
			data: dados,
			dataType: 'json',
			success: function(resp){
				if(resp && resp.status === 'ok' && resp.url){
					window.open(resp.url, '_blank');
					$('#statusGerandoPdfFiltro').text('PDF pronto. Abrindo...');
					setTimeout(function(){
						$('#modalGerandoPdfFiltro').modal('hide');
					}, 500);
				} else if(resp && resp.status === 'processing'){
					if(dados._tentativas < 15){
						dados._tentativas++;
						$('#statusGerandoPdfFiltro').text('Gerando em segundo plano...');
						setTimeout(function(){
							abrirPdfAssincrono(urlPreparar, dados, label);
						}, 1200);
					} else {
						alert('O PDF ainda está sendo gerado. Tente novamente em alguns segundos.');
						$('#modalGerandoPdfFiltro').modal('hide');
					}
				}else{
					alert('Não foi possível gerar o PDF agora.');
					$('#modalGerandoPdfFiltro').modal('hide');
				}
			},
			error: function(){
				alert('Erro ao gerar PDF.');
				$('#modalGerandoPdfFiltro').modal('hide');
			},
			complete: function(){}
		});
	}

	function gerarRelatorioReservas(){
		var url = 'rel/lista_reservas.php?token_rel=FKLUY7852&tipo=' + encodeURIComponent($('#filtro').val()) + '&dataInicial=' + encodeURIComponent($('#dataInicial').val()) + '&dataFinal=' + encodeURIComponent($('#dataFinal').val()) + '&codigo=' + encodeURIComponent($('#id_da_reserva').val());
		window.open(url, '_blank');
	}

	function abrirPdfReservaDetalhe(id){
		var url = 'rel/reserva.php?token_rel=FKLUY7852&id=' + encodeURIComponent(id);
		window.open(url, '_blank');
	}

	function abrirPdfConsumoReserva(id){
		var url = 'rel/consumo.php?token_rel=FKLUY7852&id=' + encodeURIComponent(id);
		window.open(url, '_blank');
	}



	

</script>







