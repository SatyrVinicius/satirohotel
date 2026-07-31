<?php 
$tabela = 'config';
require_once("../conexao.php");

if($modo_teste == 'Sim'){
	echo 'Não é possível editar esse recurso em modo Teste!';
	exit();
}

$nome = $_POST['nome_sistema'];
$email = $_POST['email_sistema'];
$telefone = $_POST['telefone_sistema'];
$endereco = $_POST['endereco_sistema'];
$instagram = $_POST['instagram_sistema'];
$api_whatsapp = $_POST['api_whatsapp'];
$token = $_POST['token'];
$instancia = $_POST['instancia'];
$no_show = $_POST['no_show'];
$dias_cancelamento = $_POST['dias_cancelamento'];
$taxa_cancelamento = $_POST['taxa_cancelamento'];
$marca_dagua = $_POST['marca_dagua'];
$info_reserva = $_POST['info_reserva'];
$info_checkin = $_POST['info_checkin'];
$prazo_devolucao = $_POST['prazo_devolucao'];
$ocultar_acessos = $_POST['ocultar_acessos'];
$tipo_api = $_POST['tipo_api'];
$marketing_whats = $_POST['marketing_whats'];
$tempo_reserva = $_POST['tempo_reserva'];
$pagamento_percentual = $_POST['pagamento_percentual'];
$impressao_automatica = $_POST['impressao_automatica'];
$access_token = @$_POST['access_token'];
$public_key = @$_POST['public_key'];

//foto logo
$caminho = '../img/logo.png';
$imagem_temp = @$_FILES['foto-logo']['tmp_name']; 

if(@$_FILES['foto-logo']['name'] != ""){
	$ext = pathinfo($_FILES['foto-logo']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png'){ 	
				
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto logo rel
$caminho = '../img/logo.jpg';
$imagem_temp = @$_FILES['foto-logo-rel']['tmp_name']; 

if(@$_FILES['foto-logo-rel']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-logo-rel']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}


//foto icone
$caminho = '../img/icone.png';
$imagem_temp = @$_FILES['foto-icone']['tmp_name']; 

if(@$_FILES['foto-icone']['name'] != ""){
	$ext = pathinfo(@$_FILES['foto-icone']['name'], PATHINFO_EXTENSION);   
	if($ext == 'png'){ 	
			
		move_uploaded_file($imagem_temp, $caminho);
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}





//foto fundo login
$nome_img = date('d-m-Y H:i:s') . '-' . @$_FILES['fundo_login']['name'];
$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
$caminho = '../img/'.$nome_img;
$imagem_temp = @$_FILES['fundo_login']['tmp_name']; 

if(@$_FILES['fundo_login']['name'] != ""){
	$ext = pathinfo(@$_FILES['fundo_login']['name'], PATHINFO_EXTENSION);   
	if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'JPG' || $ext == 'png' || $ext == 'PNG'|| $ext == 'gif' || $ext == 'GIF' || $ext == 'webp' || $ext == 'WEBP'){			
		move_uploaded_file($imagem_temp, $caminho);
		$fundo_login = $nome_img;

		$query = $pdo->query("SELECT * FROM config");
		$res = $query->fetchAll(PDO::FETCH_ASSOC);
		$fundo_login_antigo = @$res[0]['fundo_login'];

		if($fundo_login_antigo != "sem-foto.png"){
			@unlink('../img/'.$fundo_login_antigo);
		} 

	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}

$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, telefone = :telefone, endereco = :endereco, instagram = :instagram, api_whatsapp = :api_whatsapp, token = :token, instancia = :instancia, no_show = :no_show, taxa_cancelamento = :taxa_cancelamento, dias_cancelamento = :dias_cancelamento, marca_dagua = '$marca_dagua', info_reserva = :info_reserva, info_checkin = :info_checkin, prazo_devolucao = '$prazo_devolucao', ocultar_acessos = '$ocultar_acessos', tipo_api = '$tipo_api', marketing_whats = '$marketing_whats', tempo_reserva = '$tempo_reserva', pagamento_percentual = '$pagamento_percentual', impressao_automatica = '$impressao_automatica', fundo_login = '$fundo_login', access_token = :access_token, public_key = :public_key where id = 1");

$query->bindValue(":nome", "$nome");
$query->bindValue(":email", "$email");
$query->bindValue(":telefone", "$telefone");
$query->bindValue(":endereco", "$endereco");
$query->bindValue(":instagram", "$instagram");
$query->bindValue(":api_whatsapp", "$api_whatsapp");
$query->bindValue(":token", "$token");
$query->bindValue(":instancia", "$instancia");
$query->bindValue(":no_show", "$no_show");
$query->bindValue(":dias_cancelamento", "$dias_cancelamento");
$query->bindValue(":taxa_cancelamento", "$taxa_cancelamento");
$query->bindValue(":info_reserva", "$info_reserva");
$query->bindValue(":info_checkin", "$info_checkin");
$query->bindValue(":access_token", "$access_token");
$query->bindValue(":public_key", "$public_key");
$query->execute();

echo 'Editado com Sucesso';
 ?>