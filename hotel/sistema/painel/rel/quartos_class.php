<?php 

require_once("../../conexao.php");

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];

if($dataInicial == ""){
	$dataInicial = date('Y-m-d');
}

if($dataFinal == ""){
	$dataFinal = date('Y-m-d');
}

if(strtotime($dataFinal) < strtotime($dataInicial)){
	$dataFinal = $dataInicial;
}

$cacheDir = "../pdf/quartos_disponiveis";
if(!is_dir($cacheDir)){
	@mkdir($cacheDir, 0777, true);
}

$cacheKey = md5($dataInicial . '|' . $dataFinal);
$cacheFile = $cacheDir . "/quartos_" . $cacheKey . ".pdf";
$cacheTtlSegundos = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $cacheTtlSegundos)){
	header("Content-Type: application/pdf");
	header("Content-Disposition: inline; filename=quartos.pdf");
	header("Content-Length: " . filesize($cacheFile));
	readfile($cacheFile);
	exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("quartos.php");
$html = ob_get_clean();

require_once '../dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled', TRUE);

$pdf = new DOMPDF($options);
$pdf->set_paper('A4', 'portrait');
$pdf->load_html($html);
$pdf->render();

$output = $pdf->output();
file_put_contents($cacheFile, $output);

header("Content-Type: application/pdf");
header("Content-Disposition: inline; filename=quartos.pdf");
header("Content-Length: " . strlen($output));
echo $output;
exit();

?>

 ?>