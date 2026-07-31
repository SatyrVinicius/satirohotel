<?php require_once("sistema/conexao.php"); 

if($_POST['name'] == ""){
	echo "Preencha o nome!";
	exit();
}

if($_POST['email'] == ""){
	echo "Preencha o email!";
	exit();
}

$destinatario = $email_sistema;
$assunto = $nome_sistema . ' - Email do Site';
$mensagem = utf8_decode('Nome: '.$_POST['name']. "\r\n"."\r\n" . 'Telefone: '.$_POST['telefone']. "\r\n"."\r\n" . 'Mensagem: ' . "\r\n"."\r\n" .$_POST['message']);
$cabecalhos = "From: ".$_POST['email'];
@mail($destinatario, $assunto, $mensagem, $cabecalhos);

echo 'Enviado';

?>