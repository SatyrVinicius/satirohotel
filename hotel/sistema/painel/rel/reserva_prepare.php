<?php

require_once(__DIR__ . '/../../conexao.php');
require_once(__DIR__ . '/pdf_engine.php');
header('Content-Type: application/json; charset=utf-8');

if (php_sapi_name() === 'cli') {
	parse_str(implode('&', array_slice($argv, 1)), $_POST);
}

function iniciar_processo_em_segundo_plano($scriptPath, $argString){
	$phpBin = PHP_BINARY;

	if (DIRECTORY_SEPARATOR === '\\') {
		$cmd = 'start "" /B ' . escapeshellarg($phpBin) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($argString);
		@pclose(@popen($cmd, 'r'));
		return true;
	}

	$cmd = 'nohup ' . escapeshellarg($phpBin) . ' ' . escapeshellarg($scriptPath) . ' ' . escapeshellarg($argString) . ' > /dev/null 2>&1 &';
	@exec($cmd);
	return true;
}

function gerar_reserva_pdf_cache($id){
	$cacheDir = __DIR__ . '/../pdf/reservas';
	if(!is_dir($cacheDir)){
		@mkdir($cacheDir, 0777, true);
	}

	$cacheName = "reserva_" . $id . ".pdf";
	$cacheFile = $cacheDir . "/" . $cacheName;

	$token_rel = "FKLUY7852";
	$logoAbs = realpath(__DIR__ . '/../img/logo.jpg');
	if($logoAbs){
		$logo_relatorio = 'file:///' . str_replace('\\', '/', $logoAbs);
	}
	ob_start();
	include(__DIR__ . '/reserva.php');
	$html = ob_get_clean();

	if(trim($html) == '' || stripos($html, 'Reserva não encontrada!') !== false){
		return ['status' => 'error', 'message' => 'Reserva não encontrada'];
	}

	$resultadoGeracao = gerar_pdf_por_html($html, $cacheFile, 'portrait');
	if(!$resultadoGeracao['ok']){
		return ['status' => 'error', 'message' => 'Falha ao gerar PDF', 'debug' => $resultadoGeracao];
	}

	return ['status' => 'ok', 'cacheName' => $cacheName, 'cacheFile' => $cacheFile];
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
	echo json_encode(['status' => 'error', 'message' => 'Reserva inválida']);
	exit();
}

$cacheDir = __DIR__ . '/../pdf/reservas';
if(!is_dir($cacheDir)){
	@mkdir($cacheDir, 0777, true);
}

$cacheName = "reserva_" . $id . ".pdf";
$cacheFile = $cacheDir . "/" . $cacheName;
$cacheTtlSegundos = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $cacheTtlSegundos)){
	echo json_encode(['status' => 'ok', 'cached' => true, 'url' => 'pdf/reservas/' . $cacheName . '?v=' . @filemtime($cacheFile)]);
	exit();
}

if(php_sapi_name() === 'cli'){
	$resultado = gerar_reserva_pdf_cache($id);
	if($resultado['status'] !== 'ok'){
		echo json_encode($resultado);
		exit();
	}
	echo json_encode(['status' => 'ok', 'cached' => false, 'url' => 'pdf/reservas/' . $resultado['cacheName'] . '?v=' . @filemtime($resultado['cacheFile'])]);
	exit();
}

$argString = http_build_query(['id' => $id]);
$script = __FILE__;
iniciar_processo_em_segundo_plano($script, $argString);

echo json_encode(['status' => 'processing']);
exit();
