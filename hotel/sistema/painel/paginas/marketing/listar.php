<?php
$tabela = 'marketing';
@session_start();
$id_usuario = @$_SESSION['id'];
require_once("../../../conexao.php");

$busca = @$_POST['p1'];

$query = $pdo->query("SELECT * from $tabela where titulo like '%$busca%' order by id desc");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if ($linhas > 0) {
	?>

	

	<!-- Grid de Cards -->
	<div class="row row-cards" id="contenedor-cards">
		<?php
		for ($i = 0; $i < $linhas; $i++) {
			$id = $res[$i]['id'];
			$arquivo = $res[$i]['arquivo'];
			$audio = $res[$i]['audio'];
			$data_envio = $res[$i]['data_envio'];
			$data = $res[$i]['data'];
			$envios = $res[$i]['envios'];
			$forma_envio = $res[$i]['forma_envio'];
			$documento = $res[$i]['documento'];



			// Obter dados brutos e decodificar as entidades HTML
			$titulo = html_entity_decode($res[$i]['titulo'], ENT_QUOTES, 'UTF-8');
			$msg = html_entity_decode($res[$i]['mensagem'], ENT_QUOTES, 'UTF-8');
			$msg2 = html_entity_decode($res[$i]['mensagem2'], ENT_QUOTES, 'UTF-8');

			// Codificar a mensagem para ser enviada via URL
			$msgF = rawurlencode($msg);
			$msg2F = rawurlencode($msg2);

			$data_envioF = implode('/', array_reverse(@explode('-', $data_envio)));
			$dataF = implode('/', array_reverse(@explode('-', $data)));

			if ($forma_envio == "") {
				$forma_envio = "Todos";
			}

			$tituloF = mb_strimwidth($titulo, 0, 40, "...");


			$ocultar_audio = 'ocultar';
			if ($audio != "") {
				$ocultar_audio = '';
			}

			$ocultar_foto = 'ocultar';
			if ($arquivo != "sem-foto.png") {
				$ocultar_foto = '';
			}

			if ($forma_envio == "") {
				$forma_envio = "Todos";
			}

			$ocultar_doc = 'ocultar';
			if ($documento != "sem-foto.png") {
				$ocultar_doc = '';
			}

			$ocultar_reg = '';

			//extensão do arquivo
			$ext = pathinfo($documento, PATHINFO_EXTENSION);
			if ($ext == 'pdf') {
				$tumb_arquivo = 'pdf.png';
			} else if ($ext == 'rar' || $ext == 'zip') {
				$tumb_arquivo = 'rar.png';
			} else {
				$tumb_arquivo = $documento;
			}

			$query2 = $pdo->query("SELECT * FROM disparos where campanha = '$id' ORDER BY id desc");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$total_envios = @count($res2);

			$query2 = $pdo->query("SELECT * FROM disparos where campanha = '$id' ORDER BY id desc limit 1");
			$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
			$hora_ultimo_envio = @$res2[0]['hora'];

			$ocultar_parar = '';
			if($total_envios == 0){
				$ocultar_parar = 'ocultar';
			}
			?>

			<!-- Card de Campanha -->
			<div class="col-lg-4 col-md-6 col-sm-12 mb-4 card-campanha" style="background:#fafafa; padding:10px; border:1px solid #cecece">
				<div class="card card-sm h-100 shadow-sm hover-shadow">
					<!-- Cabeçalho do Card -->
				<div class="card-header bg-transparent border-bottom-0 d-flex justify-content-between align-items-center" style="padding-bottom: 0;">
				  <h5 class="card-title mb-0" style="color: #2b4eff; font-size: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
				    <?= $tituloF ?>
				  </h5>
				</div>
					<!-- Corpo do Card -->
					<div class="card-body pt-0" style="padding:5px">
						<!-- Informações da Campanha -->
						<div style="display: flex; justify-content: space-between; margin-bottom: 16px; flex-wrap: wrap; gap: 12px;">
  
							  <!-- Tipo de Envio -->
							  <div style="flex: 1; min-width: 180px;">
							    <small style="color: #6c757d; font-weight: 500; display: block; margin-bottom: 4px;">Tipo de Envio</small>
							    <span style="background-color: #e5f8ff; color: #17a2b8; padding: 6px 12px; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; font-size: 12px;">
							      <i class="bi bi-send" style="margin-right: 6px;"></i>
							      <?= $forma_envio ?>
							    </span>
							  </div>

							  <!-- Último Envio -->
							  <div style="flex: 1; min-width: 180px;">
							    <small style="color: #6c757d; font-weight: 500; display: block; margin-bottom: 4px;">Último Envio</small>
							    <?php if($hora_ultimo_envio != ""): ?>
							      <span style="background-color: #f1f1f1; color: #6c757d; padding: 6px 12px; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; font-size: 12px;">
							        <i class="fa fa-clock" style="margin-right: 6px;"></i>
							        <?= $hora_ultimo_envio ?>
							      </span>
							    <?php else: ?>
							      <span style="background-color: #ffe2e6; color: #d63384; padding: 6px 12px; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; font-size: 14px;">
							        <i class="fa fa-clock" style="margin-right: 6px;"></i>
							        Aguardando Disparo
							      </span>
							    <?php endif; ?>
							  </div>

							</div>


						<!-- Barra de Progresso para Envios Pendentes -->
								<div class="mb-3">
								  <div class="d-flex justify-content-between align-items-center mb-2">
								    <span style="font-weight: 600; color: #333;">Envios Pendentes</span>
								    <span class="badge rounded-pill" style="background-color: #e6f0ff; color: #2b4eff; font-weight: 600; padding: 5px 10px;">
								      <?= $total_envios ?>
								    </span>
								  </div>

								  <div style="background-color: #f0f4f8; border-radius: 30px; height: 10px; width: 100%; overflow: hidden; padding:5px">
								    <?php $porcentagem = min(100, $total_envios * 5); ?>
								    <div style="height: 100%; width: <?= $porcentagem ?>%; background: linear-gradient(to right, #2b4eff, #7b9dff); border-radius: 30px;"></div>
								  </div>
								</div>


							<!-- Elementos de Mídia lado a lado com display inline-block -->
<div style="margin-bottom: 16px; margin-top:15px; height:50px">
	<?php if ($arquivo != "sem-foto.png"): ?>
		<div style="display: inline-block; margin-right: 12px; text-align: center;">
			<img src="images/marketing/<?= $arquivo ?>" class="rounded shadow-sm" width="40" height="40"
				data-bs-toggle="tooltip" title="Imagem">

		<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-close text-danger"></i></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Excluir Imagem? <a href="#" onclick="excluirImagem(<?= $id ?>)"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

			

		</div>
	<?php endif; ?>

	<?php if ($audio != ""): ?>
		<div style="display: inline-block; margin-right: 12px; text-align: center;">
			<span class="rounded shadow-sm p-2 bg-light d-inline-block" data-bs-toggle="tooltip" title="Áudio">
				<i class="fa fa-music text-warning fs-5"></i>
			</span>

			<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-close text-danger"></i></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Excluir Áudio? <a href="#" onclick="excluirAudio(<?= $id ?>)"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>

			

		</div>
	<?php endif; ?>

	<?php if ($documento != "sem-foto.png"): ?>
		<div style="display: inline-block; margin-right: 12px; text-align: center;">
			<a href="images/marketing/<?= $documento ?>" target="_blank">
				<img src="images/marketing/<?= $tumb_arquivo ?>" class="rounded shadow-sm" width="40" height="40"
					data-bs-toggle="tooltip" title="Documento">
			</a>


				<li class="dropdown head-dpdn2" style="display: inline-block;">
		<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-close text-danger"></i></a>

		<ul class="dropdown-menu" style="margin-left:-230px;">
		<li>
		<div class="notification_desc2">
		<p>Excluir Documento? <a href="#" onclick="excluirDoc(<?= $id ?>)"><span class="text-danger">Sim</span></a></p>
		</div>
		</li>										
		</ul>
		</li>



			
		</div>
	<?php endif; ?>
</div>

<hr>

					</div>

					<!-- Rodapé do Card -->
					<div class="card-footer bg-transparent border-top d-flex justify-content-between align-items-center">
						<small class="text-muted">Criado em: <?= $dataF ?></small>

						<!-- Botões Principais com cores -->
<div class="btn-group">
	<!-- Editar -->
	<button class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="Editar Campanha"
		onclick="editar('<?= $id ?>','<?= $titulo ?>', '<?= $msgF ?>', '<?= $msg2F ?>', '<?= $arquivo ?>', '<?= $audio ?>', '<?= $documento ?>')">
		<i class="fa fa-edit"></i>
	</button>

	<!-- Visualizar -->
	<button class="btn btn-sm btn-warning text-white" data-bs-toggle="tooltip" title="Visualizar Campanha"
		onclick="mostrar('<?= $id ?>','<?= $titulo ?>', '<?= $msgF ?>', '<?= $msg2F ?>', '<?= $arquivo ?>', '<?= $audio ?>', '<?= $documento ?>', '<?= $dataF ?>', '<?= $data_envioF ?>', '<?= $arquivo ?>', '<?= $tumb_arquivo ?>')">
		<i class="fa fa-eye"></i>
	</button>

	<!-- Disparar -->
	<button class="btn btn-sm btn-success text-white" data-bs-toggle="tooltip" title="Disparar Campanha"
		onclick="disparar('<?= $id ?>','<?= $titulo ?>', '<?= $msgF ?>', '<?= $msg2F ?>', '<?= $arquivo ?>', '<?= $audio ?>', '<?= $tumb_arquivo ?>')">
		<i class="fa fa-paper-plane"></i>
	</button>

	<!-- Parar -->
	<button class="btn btn-sm btn-dark text-white <?= $ocultar_parar ?>" data-bs-toggle="tooltip" title="Parar Campanha"
		onclick="pararModal('<?= $id ?>')">
		<i class="fa fa-ban"></i>
	</button>

	<!-- Excluir -->
	<button class="btn btn-sm btn-danger text-white" data-bs-toggle="tooltip" title="Excluir Campanha"
		onclick="excluirAlert('<?= $id ?>')">
		<i class="fa fa-trash"></i>
	</button>
</div>

					</div>
				</div>
			</div>
		<?php } ?>
	</div>

	<div class="text-center mt-4" id="sem-registros" style="display: none;">
		<div class="alert alert-warning">
			<i class="fa fa-exclamation-triangle me-2"></i>
			Nenhuma campanha encontrada!
		</div>
	</div>

	<?php
} else {
	?>
	<div class="alert alert-info">
		<i class="fa fa-info-circle me-2"></i>
		Você ainda não possui nenhuma campanha de marketing cadastrada.
		<button type="button" class="btn btn-sm btn-primary float-end" onclick="$('#modalForm').modal('show');">
			<i class="fa fa-plus me-1"></i> Criar Campanha
		</button>
	</div>
	<?php
}
?>

<style>

</style>

<script type="text/javascript">
// Script para funcionalidades adicionais na listagem de marketing

$(document).ready(function() {
    
      
    
    // Adicionar classe para exibir animação quando um novo card é adicionado
    function refreshCardAnimations() {
        $(".card-campanha").each(function(index) {
            var $card = $(this);
            setTimeout(function() {
                $card.addClass("show");
            }, index * 100);
        });
    }
    
    refreshCardAnimations();
});

// Funções para os botões principais (reutilizando as funções existentes)
function formatarMensagemModal(msg) {
    // Essa função pode ser mantida conforme estava no código original
    // Ajuste conforme necessário para formatar a mensagem corretamente
    return msg.replace(/\n/g, '<br>');
}





function pararModal(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Parar os Disparos?",
        text: "Você irá parar todos os disparos dessa campanha!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Parar!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            pararDisparo(id);
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

// Sobrescrever a função de parar para usar a versão com Sweet Alert
function pararDisparo(id) {
    $.ajax({
        url: 'paginas/' + pag + "/parar_envios.php",
        method: 'POST',
        data: {id: id},
        dataType: "text",
        success: function (mensagem) {
            if (mensagem.trim() == "Parado com Sucesso") {
                Swal.fire({
                    title: 'Parado!',
                    text: 'Os envios foram interrompidos com sucesso.',
                    icon: 'success',
                    timer: 1500,
                    timerProgressBar: true,
                });
                
                // Atualizar a listagem
                listar();
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: mensagem,
                    icon: 'error'
                });
            }
        }
    });
}
</script>


<script type="text/javascript">
	function editar(id, titulo, msg, msg2, arquivo, audio, documento) {

		// Antes de definir o src, verifique a extensão
var ext = documento.split('.').pop().toLowerCase();
if (ext == 'pdf') {
  $('#target_documento').attr('src','images/marketing/pdf.png');
} else if (ext == 'rar' || ext == 'zip') {
  $('#target_documento').attr('src','images/marketing/rar.png');
} else {
  $('#target_documento').attr('src','images/marketing/' + documento);
}
		
		
		msg = decodeURIComponent(msg);
		msg2 = decodeURIComponent(msg2);
		

		// Atualiza os campos do formulário
		$('#titulo_inserir').text('Editar Registro');
		$('#id').val(id);
		$('#titulo').val(titulo);
		$('#msg').val(msg);  // Alterado de .html() para .val()
		$('#msg2').val(msg2);

		$('#titulo_inserir').text('Editar Campanha');
		$('#foto').val('');
		$('#target').attr('src','images/marketing/' + arquivo);
		


		// Atualiza o preview após preencher o campo msg
		atualizarPreview();
		atualizarPreview2();

		// Mostra a aba de edição
		$('#modalForm').modal('show');
	}




function mostrar(id, titulo, msg, msg2, arquivo, audio, documento, dataF, data_envioF, arquivo, tumb_arquivo) {
    msg = decodeURIComponent(msg);  // Decodifica a mensagem URL-encoded
	msg2 = decodeURIComponent(msg2);

    $('#id_dados').text(id);
    $('#titulo_dados').text(titulo);
	$('#data_envio_dados').text(data_envioF);
	$('#data_dados').text(dataF);
    $('#mensagem_dados').html(formatarMensagemModal(msg));
    $('#mensagem2_dados').html(formatarMensagemModal(msg2));


	$('#target_dados').attr('src','images/marketing/' + arquivo);
		$('#audio_dados').attr('src','images/marketing/' + audio);
		$('#target_documento_dados').attr('src','images/marketing/' + tumb_arquivo);

    $('#modalDados').modal('show');
}

	function limparCampos() {
		$('#id').val('');
		$('#titulo').val('');
		$('#preview').text('');
		$('#msg').val('');
		$('#msg2').val('');
		$('#ids').val('');
		$('#btn-deletar').hide();
		


		$('#foto').val('');
		$('#audio').val('');
		$('#documento').val('');
		$('#target').attr('src','images/marketing/sem-foto.png');
		$('#target_documento').attr('src','images/marketing/sem-foto.png');

		atualizarPreview();
		atualizarPreview2();
	}
</script>


<script>
	
	function disparar(id, titulo, msg, msg2, arquivo, audio, documento){

 msg = decodeURIComponent(msg);  // Decodifica a mensagem URL-encoded
	msg2 = decodeURIComponent(msg2);

		$('#nome_entrada').text(titulo);		
		$('#id_entrada').val(id);	

		$('#total_clientes').text("Alterar Opção de Teste: 0");	

		$('#titulo_disparar').text(titulo);

		$('#mensagem_disparar').html(formatarMensagemModal(msg));
		$('#mensagem2_disparar').html(formatarMensagemModal(msg2));

		$('#clientes').val('Teste');	

		$('#target_disparar').attr('src','images/marketing/' + arquivo);
		$('#audio_disparar').attr('src','images/marketing/' + audio);
		$('#target_documento_disparar').attr('src','images/marketing/' + documento);

		if(arquivo == 'sem-foto.png'){
			$('#target_disparar_div').hide();
		}else{
			$('#target_disparar_div').show();
		}

		if(audio == ''){
			$('#audio_disparar_div').hide();
		}else{
			$('#audio_disparar_div').show();
		}

		if(documento == 'sem-foto.png'){
			$('#target_documento_disparar_div').hide();
		}else{
			$('#target_documento_disparar_div').show();
		}

		$('#modalEntrada').modal('show');
	}
</script>




<script type="text/javascript">
	

	function excluirImagem(id){
		
    $.ajax({
        url: 'paginas/' + pag + "/excluir_imagem.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {            
            if (mensagem.trim() == "Excluído com Sucesso") {                
				alertsucesso('Imagem excluída com sucesso!');        
                listar();                
            } else {
				alertError('Erro ao excluir imagem: ' + mensagem);
            }

        },      

    });
}

function excluirAudio(id){
    $.ajax({
        url: 'paginas/' + pag + "/excluir_audio.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {    
            if (mensagem.trim() == "Excluído com Sucesso") {        
				alertsucesso('Áudio excluído com sucesso!');        
                listar();                
            } else {
				alertError('Erro ao excluir áudio: ' + mensagem);
            }

        },      

    });
}


function excluirDoc(id){
    $.ajax({
        url: 'paginas/' + pag + "/excluir_doc.php",
        method: 'POST',
        data: {id},
        dataType: "text",

        success: function (mensagem) {            
            if (mensagem.trim() == "Excluído com Sucesso") {  
				alertsucesso('Documento excluído com sucesso!');              
                listar();                
            } else {
					alertError('Erro ao excluir documento: ' + mensagem);
                }

        },      

    });
}



</script>
