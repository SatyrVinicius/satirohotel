<?php 

require_once("../sistema/conexao.php");

$pagina = 'especificacoes_quartos';

$id_cat = $_POST['id'];



echo <<<HTML

<small>

HTML;

$query = $pdo->query("SELECT * FROM $pagina where cat_quartos = '$id_cat' order by id desc");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$total_reg = @count($res);

if($total_reg > 0){
for($i=0; $i < $total_reg; $i++){

$id = $res[$i]['id'];
$nome = $res[$i]['texto'];

echo <<<HTML

<div style="border-bottom: 1px solid #959595; padding:5px"><i class="fa fa-check text-verde"></i> {$nome}</div>

HTML;

}

echo <<<HTML

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





</script>





