<?php 

$tabela = 'hospedes';

require_once("../../../conexao.php");



$nome = $_POST['nome'];

$email = $_POST['email'];

$telefone = $_POST['telefone'];

$cpf = $_POST['cpf'];

$endereco = $_POST['endereco'];

$obs = $_POST['obs'];

$responsavel = @$_POST['responsavel'];

$placa = $_POST['placa'];

$data_nasc = $_POST['data_nasc'];

$foto_antiga = @$_POST['foto_antiga'];

$id = $_POST['id'];




$data_nasc = implode('-', array_reverse(explode('/', $data_nasc)));

$foto = $foto_antiga != '' ? $foto_antiga : 'sem-foto.png';

if(@$_FILES['foto']['name'] != ''){
	$nome_img = date('d-m-Y-H-i-s') . '-' . @$_FILES['foto']['name'];
	$nome_img = preg_replace('/[ :]+/', '-', $nome_img);
	$caminho = '../../images/hospedes/' . $nome_img;
	$imagem_temp = @$_FILES['foto']['tmp_name'];
	$ext = strtolower(pathinfo($nome_img, PATHINFO_EXTENSION));

	if($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == 'webp'){
		move_uploaded_file($imagem_temp, $caminho);
		$foto = $nome_img;

		if($id != '' and $foto_antiga != '' and $foto_antiga != 'sem-foto.png'){
			@unlink('../../images/hospedes/' . $foto_antiga);
		}
	}else{
		echo 'Extensão de Imagem não permitida!';
		exit();
	}
}



if($id == ""){

$query = $pdo->prepare("INSERT INTO $tabela SET nome = :nome, email = :email, cpf = :cpf, obs = :obs, responsavel = :responsavel, placa = :placa, telefone = :telefone, data = curDate(), endereco = :endereco, data_nasc = :data_nasc, foto = :foto ");

	

else{

$query = $pdo->prepare("UPDATE $tabela SET nome = :nome, email = :email, cpf = :cpf, obs = :obs, telefone = :telefone, responsavel = :responsavel, placa = :placa, endereco = :endereco, data_nasc = :data_nasc, foto = :foto where id = '$id'");

}

$query->bindValue(":nome", "$nome");

$query->bindValue(":email", "$email");

$query->bindValue(":telefone", "$telefone");

$query->bindValue(":endereco", "$endereco");

$query->bindValue(":cpf", "$cpf");

$query->bindValue(":obs", "$obs");

$query->bindValue(":responsavel", "$responsavel");

$query->bindValue(":placa", "$placa");

$query->bindValue(":data_nasc", "$data_nasc");

$query->bindValue(":foto", "$foto");

$query->execute();



echo 'Salvo com Sucesso';

 ?>