<?php 

$tabela = 'hospedes';

require_once("../../../conexao.php");



$id = $_POST['id'];

$query = $pdo->query("SELECT foto FROM $tabela WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$foto = @$res[0]['foto'];

if($foto != '' and $foto != 'sem-foto.png'){
	@unlink('../../images/hospedes/' . $foto);
}



$pdo->query("DELETE FROM $tabela WHERE id = '$id' ");

echo 'Excluído com Sucesso';

?>