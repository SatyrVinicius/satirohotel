<?php 
$pag = 'bloqueio_datas';

//verificar se ele tem a permissão de estar nessa página

if(@$bloqueio_datas == 'ocultar'){
	echo "<script>window.location='../index.php'</script>";
	exit();
}

?>

<div class="margin_mobile">
	<a onclick="inserir()" type="button" class="btn btn-primary"><span class="fa fa-plus"></span> Bloquear Datas</a>


	<li class="dropdown head-dpdn2" style="display: inline-block;">	

		<a href="#" data-toggle="dropdown"  class="btn btn-danger dropdown-toggle" id="btn-deletar" style="display:none"><span class="fa fa-trash-o"></span> Deletar</a>

		<ul class="dropdown-menu">
			<li>
				<div class="notification_desc2">
					<p>Excluir Selecionados? <a href="#" onclick="deletarSel()"><span class="text-danger">Sim</span></a></p>
				</div>
			</li>										
		</ul>
	</li>
</div>



<div class="bs-example widget-shadow" style="padding:15px" id="listar">

</div>


<input type="hidden" id="ids">
<!-- Modal Perfil -->
<div class="modal fade" id="modalForm" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title" id="exampleModalLabel"><span id="titulo_inserir"></span></h4>
				<button id="btn-fechar" type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -25px">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">

				

						<div class="row">

							<div class="col-md-4">
							<label>Tipo de Quarto</label>
							<select class="form-control" id="categoria" name="categoria">
								<option value="0">Todos</option>
								<?php 
								$query = $pdo->query("SELECT * from categorias_quartos order by nome asc");
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);							
								for($i=0; $i<$linhas; $i++){
									?>
									<option value="<?php echo $res[$i]['id'] ?>"><?php echo $res[$i]['nome'] ?></option>
								<?php } ?>
							</select>				
						</div>	

						
							<div class="col-md-4">
								<label>Data Inicial</label>
								<input type="date" class="form-control" id="data_inicial" name="data_inicial" placeholder="Ex: 0" required>							

							</div>

							<div class="col-md-4">
								<label>Data Final</label>
								<input type="date" class="form-control" id="data_final" name="data_final" placeholder="Ex: 4" required>							

							</div>

						


						</div>				

						<input type="hidden" class="form-control" id="id" name="id">		

						<br>
						<small><div id="mensagem" align="center"></div></small>
					</div>	


					<div class="modal-footer">       

						<button type="submit" class="btn btn-primary">Salvar</button>

					</div>	

				</form>

			</div>

		</div>

	</div>









	<script type="text/javascript">var pag = "<?=$pag?>"</script>

	<script src="js/ajax.js"></script>







