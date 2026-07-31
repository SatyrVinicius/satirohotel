<?php 
require_once("conexao.php");
$query = $pdo->query("SELECT * from usuarios where nivel = 'Administrador' and ativo = 'Sim' ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$linhas = @count($res);
$senha = '123';
$senha_crip = password_hash($senha, PASSWORD_DEFAULT);
if($linhas == 0){
	$pdo->query("INSERT INTO usuarios SET nome = '$nome_sistema', email = '$email_sistema', senha = '', senha_crip = '$senha_crip', nivel = 'Administrador', ativo = 'Sim', foto = 'sem-foto.jpg', telefone = '$telefone_sistema', data = curDate() ");
}

 ?>
 <!DOCTYPE html>
<html>
<head>
	<title><?php echo $nome_sistema ?></title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" type="image/x-icon" href="img/icone.png">

	<script src="painel/js/jquery-1.11.1.min.js"></script>
	
</head>

	<?php 
if($fundo_login != "" and $fundo_login != "sem-foto.png"){ ?>
<body style="background: url('img/<?php echo $fundo_login ?>') no-repeat center center fixed; background-size: cover;">
<?php }else{ ?>
<body >
<?php } ?>

	<div class="login">		
		<div class="form">
			<img src="img/logo.png" class="imagem">
			<form method="post" action="autenticar.php">
				<input type="email" id="email" name="usuario" placeholder="Seu Email" <?php if($modo_teste == 'Sim'){ ?> value="contato@hugocursos.com.br" <?php } ?> required>
				<input type="password" name="senha" placeholder="Senha" required <?php if($modo_teste == 'Sim'){ ?> value="123" <?php } ?>>
				<button>Login</button>
			</form>	
			<div style="margin-top: 15px; font-size: 13px; "><a href="#" onclick="recuperar()" style="color:#666565" title="Recuperar Senha">Recuperar Senha?</a></div>
		</div>
	</div>
</body>
</html>


<script type="text/javascript">
	function recuperar(){
		var email = $('#email').val();
		if(email == ""){
			alert("Digite um email!");
			return;
		}

		$.ajax({
	        url: 'recuperar.php',
	        method: 'POST',
	        data: {email},
	        dataType: "html",

	        success:function(result){
	            if(result == 'Recuperado'){
	            	alert('Confira sua senha no email!')
	            }else{
	            	alert(result)
	            }
	            
	        }
    	});
	}
</script>