<?php 
require_once("cabecalho.php");

$checkin = filter_var(@$_POST['checkin'], @FILTER_SANITIZE_STRING);
$checkout = filter_var(@$_POST['checkout'], @FILTER_SANITIZE_STRING);
$adultos = filter_var(@$_POST['adultos'], @FILTER_SANITIZE_STRING);
$criancas = filter_var(@$_POST['criancas'], @FILTER_SANITIZE_STRING);

$hospedes = intval($adultos) + intval($criancas);

$checkinF = implode('/', array_reverse(explode('-', $checkin)));
$checkoutF = implode('/', array_reverse(explode('-', $checkout)));

//calcular diferença de dias entre as datas
$diferenca = strtotime($checkout) - strtotime($checkin);
$dias = floor($diferenca / (60 * 60 * 24));

$idade_crianca_1 = filter_var(@$_POST['idade_crianca_1'], @FILTER_SANITIZE_STRING);
$idade_crianca_2 = filter_var(@$_POST['idade_crianca_2'], @FILTER_SANITIZE_STRING);
$idade_crianca_3 = filter_var(@$_POST['idade_crianca_3'], @FILTER_SANITIZE_STRING);
$idade_crianca_4 = filter_var(@$_POST['idade_crianca_4'], @FILTER_SANITIZE_STRING);
$idade_crianca_5 = filter_var(@$_POST['idade_crianca_5'], @FILTER_SANITIZE_STRING);
$idade_crianca_6 = filter_var(@$_POST['idade_crianca_6'], @FILTER_SANITIZE_STRING);
$idade_crianca_7 = filter_var(@$_POST['idade_crianca_7'], @FILTER_SANITIZE_STRING);
$idade_crianca_8 = filter_var(@$_POST['idade_crianca_8'], @FILTER_SANITIZE_STRING);


if($dias <= 0){
	$dias = 1;
}

?>  


<div class="whole-wrap" style="margin-top: 50px;">



	<div class="container">
		<br>
		<div style="border:1px solid #000; margin-top: 20px; padding: 10px; font-size:15px">

		  <div class="row">
		    <div class="col-md-3 col-6">
		      <span style="color:#f4a300;">CheckIn:</span>
		      <input type="date" name="checkin" id="checkin" value="<?php echo $checkin ?>" 
		             style="border:none; background:transparent; color:#6c757d; width:auto; display:inline-block; font-size: 14px">
		    </div>

		    <div class="col-md-3 col-6">
		      <span style="color:#f4a300;">CheckOut:</span>
		      <input type="date" name="checkout" id="checkout" value="<?php echo $checkout ?>" 
		             style="border:none; background:transparent; color:#6c757d; width:auto; display:inline-block; font-size: 14px">
		    </div>

		    <div class="col-md-3 col-6">
		      <span style="color:#f4a300;">Hóspedes:</span>
		      <span style="color:#6c757d;"><?php echo $hospedes ?></span>
		    </div>

		    <div class="col-md-3 col-6">
		      <span style="color:#f4a300;">Diárias:</span>
		      <span id="span-diarias" style="color:#6c757d;"><?php echo $dias ?></span>

		    </div>
		  </div>
		</div>


		<div style="margin-top: 20px;">
			
			 <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">



<div class="form-container">
    <form class="row contact_form" method="post" id="contactForm" novalidate="novalidate">
        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="nome">Nome</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-user"></i>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Seu Nome">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="telefone">Telefone</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-phone"></i>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Seu Telefone">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="form-label" for="documento">CPF ou RG</label>
                <div class="input-icon-wrapper">
                    <i class="fa fa-id-card"></i>
                    <input type="text" class="form-control" id="documento" name="documento" placeholder="Seu CPF ou RG" onkeyup="verMasc()">
                </div>
            </div>
        </div>
    </form>
</div>

		</div>

		<div id="blocos">

		

		</div>



	</div>
</div>

<form method="post" action="sistema/pagamentos/index.php" style="display:none">
<input type="text" name="id_reserva" id="id_reserva">
<button id="form_comprar" type="submit"></button>
</form>





<!-- FORM OCULTO que receberá as novas datas e mantém os demais campos -->
<form id="formDatas" method="post" style="display:none">
  <input type="date" name="checkin" id="post_checkin" value="<?php echo $checkin ?>">
  <input type="date" name="checkout" id="post_checkout" value="<?php echo $checkout ?>">

  <!-- Mantém demais parâmetros do fluxo -->
  <input type="hidden" name="adultos"  value="<?php echo $adultos ?>">
  <input type="hidden" name="criancas" value="<?php echo $criancas ?>">

  <input type="hidden" name="idade_crianca_1" value="<?php echo $idade_crianca_1 ?>">
  <input type="hidden" name="idade_crianca_2" value="<?php echo $idade_crianca_2 ?>">
  <input type="hidden" name="idade_crianca_3" value="<?php echo $idade_crianca_3 ?>">
  <input type="hidden" name="idade_crianca_4" value="<?php echo $idade_crianca_4 ?>">
  <input type="hidden" name="idade_crianca_5" value="<?php echo $idade_crianca_5 ?>">
  <input type="hidden" name="idade_crianca_6" value="<?php echo $idade_crianca_6 ?>">
  <input type="hidden" name="idade_crianca_7" value="<?php echo $idade_crianca_7 ?>">
  <input type="hidden" name="idade_crianca_8" value="<?php echo $idade_crianca_8 ?>">
</form>


<?php require_once("rodape.php") ?>    


<script type="text/javascript">

		$(document).ready( function () {

		const nome = localStorage.getItem('nome_cli');
        const telefone = localStorage.getItem('telefone_cli');
        const documento = localStorage.getItem('doc_cli');

        // Preenche os inputs se os dados existirem
        if (nome) {
            $("#nome").val(nome);
        }
        if (telefone) {
            $("#telefone").val(telefone);
        }
        if (documento) {
            $("#documento").val(documento);
        }


						verMasc()
						listar_quartos();
					});
	
		function listar_quartos(){

		var checkin = "<?=$checkin?>";
		var checkout = "<?=$checkout?>";
		var diarias = "<?=$dias?>";
		var idade_crianca_1 = "<?=$idade_crianca_1?>";
		var idade_crianca_2 = "<?=$idade_crianca_2?>";
		var idade_crianca_3 = "<?=$idade_crianca_3?>";
		var idade_crianca_4 = "<?=$idade_crianca_4?>";
		var idade_crianca_5 = "<?=$idade_crianca_5?>";
		var idade_crianca_6 = "<?=$idade_crianca_6?>";
		var idade_crianca_7 = "<?=$idade_crianca_7?>";
		var idade_crianca_8 = "<?=$idade_crianca_8?>";
		
		$.ajax({
			url: 'ajax/listar_quartos.php',
			method: 'POST',
			data: {checkin, checkout, diarias, idade_crianca_1, idade_crianca_2, idade_crianca_3, idade_crianca_4, idade_crianca_5, idade_crianca_6, idade_crianca_7, idade_crianca_8},
			dataType: "html",

			success:function(result){
				
				$("#blocos").html(result);           
			}
		});
	}



	function comprar(id){
		var nome = $("#nome").val();
		var telefone = $("#telefone").val();
		var documento = $("#documento").val();

		var checkin = "<?=$checkin?>";
		var checkout = "<?=$checkout?>";
		var diarias = "<?=$dias?>";
		var hospedes = "<?=$hospedes?>";
		var idade_crianca_1 = "<?=$idade_crianca_1?>";
		var idade_crianca_2 = "<?=$idade_crianca_2?>";
		var idade_crianca_3 = "<?=$idade_crianca_3?>";
		var idade_crianca_4 = "<?=$idade_crianca_4?>";
		var idade_crianca_5 = "<?=$idade_crianca_5?>";
		var idade_crianca_6 = "<?=$idade_crianca_6?>";
		var idade_crianca_7 = "<?=$idade_crianca_7?>";
		var idade_crianca_8 = "<?=$idade_crianca_8?>";
		var criancas = "<?=$criancas?>";


		//criar as variaveis de console localStorage
		localStorage.setItem('nome_cli', nome);
    	localStorage.setItem('telefone_cli', telefone);
    	localStorage.setItem('doc_cli', documento);


		if(nome == ""){
			alert('Preencha o Nome!');
			return;
		}

		if(telefone == ""){
			alert('Preencha o Telefone!');
			return;
		}

		if(documento == ""){
			alert('Preencha o Documento!');
			return;
		}

		$("#tipo_q").val(id);

		//salvar a reserva
		 $.ajax({
		        url: 'ajax/salvar_prereserva.php',
		        method: 'POST',
		        data: {id, nome, telefone, documento, checkin, checkout, diarias, hospedes, idade_crianca_1, idade_crianca_2, idade_crianca_3, idade_crianca_4, idade_crianca_5, idade_crianca_6, idade_crianca_7, idade_crianca_8, criancas
},
		        dataType: "html",

		        success:function(result){		        	
		        	var split = result.split("*");
		            if(split[0].trim() == 'Salvo com Sucesso'){
		            	var id_reserva = split[1];		            	
		            	$("#id_reserva").val(id_reserva);
		            	$("#form_comprar").click();
		            }
		        }
		    });

		
	}

</script>


	<!-- Mascaras JS -->
<script type="text/javascript" src="sistema/painel/js/mascaras.js"></script>

<!-- Ajax para funcionar Mascaras JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script> 


<script type="text/javascript">
	function verMasc(){
		var cpf = $('#documento').val();
		
		if(cpf.length >= 15){
			$('#documento').mask('AA 00 000 000-0');
		}else{
			$('#documento').mask('AA0.000.000-000');
		}
	}
</script>


<style>
    .form-container {
        background: #f9f9f9;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 1.8rem;
    }

    .input-icon-wrapper {
        display: flex;
        align-items: center;
        position: relative;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .input-icon-wrapper:focus-within {
        border-color: #0d6efd;
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.3);
    }

    .input-icon-wrapper i {
        padding: 0 15px;
        font-size: 1.2rem;
        color: #0d6efd;
    }

    .form-control {
        border: none;
        outline: none;
        height: 48px;
        width: 100%;
        font-size: 1rem;
        padding-right: 15px;
        background: transparent;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
        color: #333;
    }
</style>




<script>
// Ao mudar qualquer data, validamos e reenviamos por POST
(function() {
  const $inCheckin  = document.getElementById('checkin');
  const $inCheckout = document.getElementById('checkout');
  const $postCheckin  = document.getElementById('post_checkin');
  const $postCheckout = document.getElementById('post_checkout');
  const $formDatas = document.getElementById('formDatas');
  const $spanDiarias = document.getElementById('span-diarias');

  function calcDiarias(ci, co){
    if(!ci || !co) return null;
    const d1 = new Date(ci + 'T00:00:00');
    const d2 = new Date(co + 'T00:00:00');
    let diff = Math.round((d2 - d1) / (1000*60*60*24));
    if (isNaN(diff) || diff <= 0) diff = 1;
    return diff;
  }

  function onDateChange() {
    let ci = $inCheckin.value;
    let co = $inCheckout.value;

    // Se checkout < checkin, ajusta checkout para o próprio checkin
    if (ci && co && co < ci) {
      co = ci;
      $inCheckout.value = co;
    }

    // feedback instantâneo nas diárias (antes do POST)
    const diarias = calcDiarias(ci, co);
    if (diarias !== null) $spanDiarias.textContent = diarias;

    // Copia pro form oculto e envia
    $postCheckin.value  = ci || '';
    $postCheckout.value = co || '';
    $formDatas.submit();
  }

  $inCheckin.addEventListener('change', onDateChange);
  $inCheckout.addEventListener('change', onDateChange);

  // Regras mínimas para evitar datas inválidas
  $inCheckout.min = $inCheckin.value || '';
  $inCheckin.addEventListener('change', () => { $inCheckout.min = $inCheckin.value; });
})();
</script>
