<?php 

$pag = 'quadro_reservas';



//verificar se ele tem a permissão de estar nessa página

if(@$quadro_reservas == 'ocultar'){

	echo "<script>window.location='../index.php'</script>";

	exit();

}

?>

<div class="margin_mobile">


	<div class="row">
		<div class="col-md-4 ">
			<select class="form-control sel" id="tipo_busca" onchange="buscar()" style="width:200px; display:inline">

				<option value="">Filtrar por Quarto</option>

				<?php 

				$query = $pdo->query("SELECT * from quartos order by id asc");

				$res = $query->fetchAll(PDO::FETCH_ASSOC);

				$linhas = @count($res);									

				for($i=0; $i<$linhas; $i++){

					?>



					<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['numero'] ?></option>



				<?php } ?>

			</select>	
		</div>

		<div class="col-md-8 " style="margin-top: 5px">
			<i class="fa fa-square" style="color:#40db47"></i> <span style="margin-right:10px">Disponíveis</span>
			<i class="fa fa-square" style="color:#e84343"></i> <span style="margin-right:10px">Ocupados</span>
			<i class="fa fa-square" style="color:#f57d7d"></i> <span style="margin-right:10px">Check-out</span>
		</div>
	</div>
</div>								





<input type="hidden" id="array_datas" >
<input type="hidden" id="array_datas2" >

<div class="bs-example widget-shadow" style="padding:15px; margin-top: 10px">

	<div class="calendario_classe">

		<div id="container" style="margin-top: -30px">

			<div id="header">

				<div id="monthDisplay"></div>



				<div>

					<button class="botao_calendar" id="backButton"><</button>

					<button class="botao_calendar" id="nextButton">></button>

				</div>



			</div>



			<div id="weekdays">

				<div>Dom</div>

				<div>Seg</div>

				<div>Ter</div>

				<div>Qua</div>

				<div>Qui</div>

				<div>Sex</div>

				<div>Sáb</div>

			</div>





			<!-- div dinamic -->

			<div id="calendar" ></div>





		</div>





	</div>

</div>









<script src="js/script-calendario.js"></script>

<script type="text/javascript">var pag = "<?=$pag?>"</script>

<script src="js/ajax.js"></script>





<script type="text/javascript">



	$(document).ready( function () {	

		$('.sel').select2({



		});

	});



	function buscar(){

		var id_quarto = $('#tipo_busca').val();

		$.ajax({

			url: 'paginas/' + pag + "/listar_datas.php",

			method: 'POST',

			data: {id_quarto},

			dataType: "html",



			success:function(result){
				
				var split = result.split("_");
				var datas = split[0];
				var data_checkout = split[1];

				$('#array_datas').val(result)
				$('#array_datas2').val(data_checkout);

				load();

			}

		});

		

	}

</script>







