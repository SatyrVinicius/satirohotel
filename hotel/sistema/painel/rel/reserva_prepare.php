<?php

require_once(__DIR__ . '/../../conexao.php');
header('Content-Type: application/json; charset=utf-8');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
	echo json_encode(['status' => 'error', 'message' => 'Reserva inválida']);
	exit();
}

$token_rel = 'FKLUY7852';
$logoAbs = realpath(__DIR__ . '/../img/logo.jpg');
if($logoAbs){
	$logo_relatorio = 'file:///' . str_replace('\\', '/', $logoAbs);
}

ob_start();
include(__DIR__ . '/reserva.php');
$html = ob_get_clean();

if(trim($html) == '' || stripos($html, 'Reserva não encontrada!') !== false){
	echo json_encode(['status' => 'error', 'message' => 'Reserva não encontrada']);
	exit();
}

$cacheDir = __DIR__ . '/../pdf/reservas';
if(!is_dir($cacheDir)){
	@mkdir($cacheDir, 0777, true);
}

$cacheName = 'reserva_' . $id . '.html';
$cacheFile = $cacheDir . '/' . $cacheName;
file_put_contents($cacheFile, $html);

echo json_encode(['status' => 'ok', 'cached' => false, 'url' => 'pdf/reservas/' . $cacheName . '?v=' . @filemtime($cacheFile)]);
