<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];
$operador = @$_POST['operador'];

if($dataInicial == "") $dataInicial = date('Y-m-d');
if($dataFinal == "") $dataFinal = date('Y-m-d');
if($dataFinal < $dataInicial) $dataFinal = $dataInicial;

$cacheDir = "../pdf/caixas";
if(!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);

$cacheKey = md5($dataInicial.'|'.$dataFinal.'|'.$operador);
$cacheName = "caixas_" . $cacheKey . ".pdf";
$cacheFile = $cacheDir . '/' . $cacheName;
$ttl = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $ttl)){
	echo json_encode(['status'=>'ok','cached'=>true,'url'=>'pdf/caixas/'.$cacheName.'?v='.@filemtime($cacheFile)]);
	exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("caixas.php");
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

echo json_encode(['status'=>'ok','cached'=>false,'url'=>'pdf/caixas/'.$cacheName.'?v='.@filemtime($cacheFile)]);
exit();
