<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');
@set_time_limit(0);
@ini_set('max_execution_time', '0');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$filtro_data = @$_POST['filtro_data'];
$filtro_tipo = @$_POST['filtro_tipo'];
$filtro_lancamento = @$_POST['filtro_lancamento'];
$filtro_pendentes = @$_POST['filtro_pendentes'];

if($dataInicial == "") $dataInicial = date('Y-m-d');
if($dataFinal == "") $dataFinal = date('Y-m-d');
if($filtro_data == "") $filtro_data = 'data_lanc';
if($filtro_tipo == "") $filtro_tipo = 'receber';
if($dataFinal < $dataInicial) $dataFinal = $dataInicial;

$cacheDir = "../pdf/financeiro";
if(!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);

$cacheKey = md5($dataInicial.'|'.$dataFinal.'|'.$filtro_data.'|'.$filtro_tipo.'|'.$filtro_lancamento.'|'.$filtro_pendentes);
$cacheName = "financeiro_" . $cacheKey . ".pdf";
$cacheFile = $cacheDir . '/' . $cacheName;
$ttl = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $ttl)){
	echo json_encode(['status'=>'ok','cached'=>true,'url'=>'pdf/financeiro/'.$cacheName.'?v='.@filemtime($cacheFile)]);
	exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("financeiro.php");
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

echo json_encode(['status'=>'ok','cached'=>false,'url'=>'pdf/financeiro/'.$cacheName.'?v='.@filemtime($cacheFile)]);
exit();
