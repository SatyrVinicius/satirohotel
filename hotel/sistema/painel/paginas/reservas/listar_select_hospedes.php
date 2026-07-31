<?php 
$tabela = 'hospedes';

require_once("../../../conexao.php");

$valor = @$_POST['valor'];
	
	echo '
	<select class="form-control sel8" name="hospedes_reserva" id="hospedes_reserva" style="width:100%" >';

								if($valor == ""){
									echo '<option value="">Selecionar demais Hóspedes</option>';
								}
								
								
								$query = $pdo->query("SELECT * from hospedes order by id desc");
								$res = $query->fetchAll(PDO::FETCH_ASSOC);
								$linhas = @count($res);
								if($linhas > 0){
									for($i=0; $i<$linhas; $i++){
										$nomeF = mb_strimwidth($res[$i]['nome'], 0, 26, "...");
										
										echo '<option value="'.$res[$i]['id'].'">'.$nomeF.' - CPF '.$res[$i]['cpf'].'</option>';
									 } }else{ 
										echo '<option value="">Cadastre um Hóspede</option>';
									 } 
								echo '</select>
								';		


?>


<script type="text/javascript">
	$('.sel8').select2({

		dropdownParent: $('#modalCheckin')

	});
</script>