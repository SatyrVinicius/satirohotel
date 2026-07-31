<?php 
include('../../conexao.php');

$id = $_GET['id'];

$total_consumo = 0;
$total_servicos = 0;
//BUSCAR AS INFORMAÇÕES DO PEDIDO
$query = $pdo->query("SELECT * from receber where id = '$id' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$descricao     = $res[0]['descricao'];
$valor         = $res[0]['valor'];
$data_lanc     = $res[0]['data_lanc'];
$data_venc     = $res[0]['data_venc'];
$data_pgto     = $res[0]['data_pgto'];
$usuario_lanc  = $res[0]['usuario_lanc'];
$usuario_pgto  = $res[0]['usuario_pgto'];
$arquivo       = $res[0]['arquivo'];
$pago          = $res[0]['pago'];
$obs           = $res[0]['obs'];
$hospede       = $res[0]['hospede'];
$hora          = $res[0]['hora'];
$referencia    = $res[0]['referencia'];
$quantidade    = $res[0]['quantidade'];
$forma_pgto    = $res[0]['forma_pgto'];

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

$query2 = $pdo->query("SELECT * FROM formas_pgto where id = '$forma_pgto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
if(@count($res2) > 0){
	$nome_forma_pgto = $res2[0]['nome'];
}else{
	$nome_forma_pgto = '';
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


?>


<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php if(@$impressao_automatica == 'Sim' and @$_GET['imprimir'] != 'Não'){ ?>
<script type="text/javascript">
	$(document).ready(function() {    		
		window.print();
		window.close(); 
	} );
</script>
<?php } ?>


<style type="text/css">
	*{
	margin:0px;

	/*Espaçamento da margem da esquerda e da Direita*/
	padding:0px;
	background-color:#ffffff;
	
	font-color:#000;	
	font-family: TimesNewRoman, Geneva, sans-serif; 

}
.text {
	&-center { text-align: center; }
}
.ttu { text-transform: uppercase;
	font-weight: bold;
	font-size: 1.2em;
 }

.printer-ticket {
	display: table !important;
	width: 100%;

	/*largura do Campos que vai os textos*/
	max-width: 400px;
	font-weight: light;
	line-height: 1.3em;

	/*Espaçamento da margem da esquerda e da Direita*/
	padding: 0px;
	font-family: TimesNewRoman, Geneva, sans-serif; 

	/*tamanho da Fonte do Texto*/
	font-size: 12px; 
	font-color:#000;
	
	
	}
	
	th { 
		font-weight: inherit;

		/*Espaçamento entre as uma linha para outra*/
		padding:5px;
		text-align: center;

		/*largura dos tracinhos entre as linhas*/
		border-bottom: 1px dashed #000000;
	}


	

	
	
		
	.cor{
		color:#000000;
	}
	
	
	

	/*margem Superior entre as Linhas*/
	.margem-superior{
		padding-top:5px;
	}
	
	
} 
</style>



<table class="printer-ticket">

		<td>
		<img style="margin-top: 10px; margin-left: 40px" id="imag" src="<?php echo $url_sistema ?>img/logo.jpg" width="80px">
	</td>

	<tr>
		<th class="ttu" class="title" colspan="3"></th>
	</tr>
	<tr style="font-size: 10px">
		<th colspan="3">
			<?php echo $endereco_sistema ?> <br />			
			Contato: <?php echo $telefone_sistema ?> 
		</th>
	</tr>

	<tr >
		<th colspan="3">Hóspede <?php echo $nome_hospede ?> - Data: <?php echo $data_lancF ?>			
			<br>
			Venda: <?php echo $id ?> - <?php if(@$cancelada == 'Sim'){
				echo 'CANCELADA';
			}else{ ?>Pago : <?php echo $pago ?> <?php } ?>
			
			
		</th>
	</tr>
	
	<tr>
		<th class="ttu margem-superior" colspan="3">
			Comprovante de Venda
			
		</th>
	</tr>
	<tr>
		
		<th colspan="3">
			CUMPOM NÃO FISCAL
			
		</th>
	
	</tr>
	
	<tbody>

		
			<tr>
				
					<td colspan="2" width="70%"><?php echo $descricao ?>
					</td>			

				<td align="right">R$ <?php echo $valorF ?></td>
			</tr>

	
				
	</tbody>
	<tfoot>

		<tfoot>

		<tr>
			<th class="ttu"  colspan="3" class="cor">
			<!-- _ _	_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ -->
			</th>
		</tr>	
		
		
		
		</tr>

			<tr>
			<td colspan="2">SubTotal</td>			
			<td align="right">R$ <?php echo $valorF ?></td>	
		</tr>	

		
		<tr>
			<th class="ttu" colspan="3" class="cor">
			<!-- _ _	_ _ _ _ _ _ _ _ _ _ _ _ _ _ _ -->
			</th>
		</tr>	


	
		<?php if($pago == "Sim"){ ?>
		<tr>
			<td colspan="2">Forma de Pagamento</td>
			<td align="right"><?php echo $nome_forma_pgto ?></td>
		</tr>
	<?php } ?>

	


		<tr>
			<td colspan="2">Vendedor</td>
			<td align="right"><?php echo $nome_usu_lanc ?></td>
		</tr>

		

	</tfoot>
</table>

<?php if($pago == 'Não'){ ?>
<br><br>
<div align="center">__________________________</div>
<div align="center"><small>Assinatura do Cliente</small></div>
<?php } ?>