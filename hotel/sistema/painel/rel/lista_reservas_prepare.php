<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

if (php_sapi_name() === 'cli') {
	parse_str(implode('&', array_slice($argv, 1)), $_POST);
}

function gerar_lista_reservas_pdf($tipo, $dataInicial, $dataFinal, $codigo){
	if($tipo == ""){
		$tipo = 'check_in';
	}

	if($dataInicial == ""){
		$dataInicial = date('Y-m-d');
	}

	if($dataFinal == ""){
		$dataFinal = date('Y-m-d');
	}

	if($codigo == ""){
		$codigo = '';
	}

	if(strtotime($dataFinal) < strtotime($dataInicial)){
		$dataFinal = $dataInicial;
	}

	$cacheDir = "../pdf/lista_reservas";
	if(!is_dir($cacheDir)){
		@mkdir($cacheDir, 0777, true);
	}

	$cacheKey = md5($tipo . '|' . $dataInicial . '|' . $dataFinal . '|' . $codigo);
	$cacheName = "lista_reservas_" . $cacheKey . ".pdf";
	$cacheFile = $cacheDir . "/" . $cacheName;

	$token_rel = "FKLUY7852";
	ob_start();
	include("lista_reservas.php");
	$html = ob_get_clean();

	require_once '../dompdf/autoload.inc.php';

	$options = new \Dompdf\Options();
	$options->set('isRemoteEnabled', true);

	$pdf = new \Dompdf\Dompdf($options);
	$pdf->set_paper('A4', 'portrait');
	$pdf->load_html($html);
	$pdf->render();

	$output = $pdf->output();
	file_put_contents($cacheFile, $output);

	return ['cacheName' => $cacheName, 'cacheFile' => $cacheFile];
}

$tipo = @$_POST['tipo'];
$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$codigo = @$_POST['codigo'];

$tipo = $tipo == '' ? 'check_in' : $tipo;
$dataInicial = $dataInicial == '' ? date('Y-m-d') : $dataInicial;
$dataFinal = $dataFinal == '' ? date('Y-m-d') : $dataFinal;
$codigo = $codigo == '' ? '' : $codigo;

$cacheKey = md5($tipo . '|' . $dataInicial . '|' . $dataFinal . '|' . $codigo);
$cacheDir = "../pdf/lista_reservas";
if(!is_dir($cacheDir)){
	@mkdir($cacheDir, 0777, true);
}
$cacheName = "lista_reservas_" . $cacheKey . ".pdf";
$cacheFile = $cacheDir . "/" . $cacheName;
$cacheTtlSegundos = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $cacheTtlSegundos)){
	echo json_encode(['status' => 'ok', 'cached' => true, 'url' => 'pdf/lista_reservas/' . $cacheName . '?v=' . @filemtime($cacheFile)]);
	exit();
}


if(php_sapi_name() === 'cli'){
	$resultado = gerar_lista_reservas_pdf($tipo, $dataInicial, $dataFinal, $codigo);
	echo json_encode(['status' => 'ok', 'cached' => false, 'url' => 'pdf/lista_reservas/' . $resultado['cacheName'] . '?v=' . @filemtime($resultado['cacheFile'])]);
	exit();
}

$argString = http_build_query([
	'tipo' => $tipo,
	'dataInicial' => $dataInicial,
	'dataFinal' => $dataFinal,
	'codigo' => $codigo
]);
$phpBin = PHP_BINARY;
$script = __FILE__;
@pclose(@popen('start "" /B ' . escapeshellarg($phpBin) . ' ' . escapeshellarg($script) . ' ' . $argString, 'r'));

echo json_encode(['status' => 'processing']);
exit();
