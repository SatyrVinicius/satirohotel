<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

if (php_sapi_name() === 'cli') {
	parse_str(implode('&', array_slice($argv, 1)), $_POST);
}

function gerar_consumo_pdf_cache($id){
	$cacheDir = "../pdf/consumos";
	if(!is_dir($cacheDir)){
		@mkdir($cacheDir, 0777, true);
	}

	$cacheName = "reserva_" . $id . ".pdf";
	$cacheFile = $cacheDir . "/" . $cacheName;

	$enviar = 'nao';
	$token_rel = "FKLUY7852";
	ob_start();
	include("consumo.php");
	$html = ob_get_clean();

	if(trim($html) == '' || stripos($html, 'Reserva não encontrada!') !== false){
		return ['status' => 'error', 'message' => 'Detalhamento não encontrado'];
	}

	require_once '../dompdf/autoload.inc.php';

	$options = new \Dompdf\Options();
	$options->set('isRemoteEnabled', true);

	$pdf = new \Dompdf\Dompdf($options);
	$pdf->set_paper('A4', 'portrait');
	$pdf->load_html($html);
	$pdf->render();

	$output = $pdf->output();
	file_put_contents($cacheFile, $output);

	return ['status' => 'ok', 'cacheName' => $cacheName, 'cacheFile' => $cacheFile];
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
	echo json_encode(['status' => 'error', 'message' => 'Reserva inválida']);
	exit();
}

$cacheDir = "../pdf/consumos";
if(!is_dir($cacheDir)){
	@mkdir($cacheDir, 0777, true);
}

$cacheName = "reserva_" . $id . ".pdf";
$cacheFile = $cacheDir . "/" . $cacheName;
$cacheTtlSegundos = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $cacheTtlSegundos)){
	echo json_encode(['status' => 'ok', 'cached' => true, 'url' => 'pdf/consumos/' . $cacheName . '?v=' . @filemtime($cacheFile)]);
	exit();
}

if(php_sapi_name() === 'cli'){
	$resultado = gerar_consumo_pdf_cache($id);
	if($resultado['status'] !== 'ok'){
		echo json_encode($resultado);
		exit();
	}
	echo json_encode(['status' => 'ok', 'cached' => false, 'url' => 'pdf/consumos/' . $resultado['cacheName'] . '?v=' . @filemtime($resultado['cacheFile'])]);
	exit();
}

$argString = http_build_query(['id' => $id]);
$phpBin = PHP_BINARY;
$script = __FILE__;
@pclose(@popen('start "" /B ' . escapeshellarg($phpBin) . ' ' . escapeshellarg($script) . ' ' . $argString, 'r'));

echo json_encode(['status' => 'processing']);
exit();
