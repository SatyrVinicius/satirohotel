<?php 
@session_start();
if (@$_SESSION['id'] == ""){
	echo '<script>window.location="../"</script>';
	@session_destroy();
	exit();
}

if (@$_SESSION['token_S520785'] != "ADFSDFDS8114"){
	echo '<script>window.location="../"</script>';
	@session_destroy();
	exit();
}

 ?>
