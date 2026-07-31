<?php 
require_once("conexao.php");

$email = filter_var(@$_POST['email'], @FILTER_SANITIZE_STRING);

$query = $pdo->prepare("SELECT * from usuarios where email = :email and nivel != 'Administrador'");
$query->bindValue(":email", "$email");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
if($linhas > 0){
	$senha = $res[0]['senha'];
	$telefone = $res[0]['telefone'];

	$nova_senha = rand(100000, 100000000);
	$senha_crip = password_hash($nova_senha, PASSWORD_DEFAULT);
	$query = $pdo->prepare("UPDATE usuarios SET senha_crip = '$senha_crip' where email = :email");
	$query->bindValue(":email", "$email");
	$query->execute();
	
	//ENVIAR O EMAIL COM A SENHA
	$destinatario = $email;
	$assunto = $nome_sistema . ' - Recuperação de Senha';
	$mensagem = utf8_decode('Sua senha é ' .$nova_senha);
	$cabecalhos = "From: ".$email_sistema;

	@mail($destinatario, $assunto, $mensagem, $cabecalhos);
	echo 'Recuperado';


	if($api_whatsapp == 'Sim'){
		$telefone_envio = '55'.preg_replace('/[ ()-]+/' , '' , @$telefone);
		$mensagem = '*'.$nome_sistema.'* %0A';
		$mensagem .= '_Recuperação de Senha_ %0A';		
		$mensagem .= 'Sua Senha é: *'.$nova_senha.'*';
		
		require("painel/api/texto.php");
}

}else{
echo 'Email não Cadastrado!';
}	




 ?>
