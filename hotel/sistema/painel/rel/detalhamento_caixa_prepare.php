<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
	echo json_encode(['status'=>'error','message'=>'Caixa inválida']);
	exit();
}

$cacheDir = "../pdf/detalhamento_caixa";
if(!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);

$cacheName = "detalhamento_caixa_" . $id . ".pdf";
$cacheFile = $cacheDir . '/' . $cacheName;
$ttl = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $ttl)){
	echo json_encode(['status'=>'ok','cached'=>true,'url'=>'pdf/detalhamento_caixa/'.$cacheName.'?v='.@filemtime($cacheFile)]);
	exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("detalhamento_caixa.php");
$html = ob_get_clean();

if(trim($html) == '' || stripos($html, 'Caixa não encontrado') !== false){
	echo json_encode(['status'=>'error','message'=>'Detalhamento não encontrado']);
	exit();
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

echo json_encode(['status'=>'ok','cached'=>false,'url'=>'pdf/detalhamento_caixa/'.$cacheName.'?v='.@filemtime($cacheFile)]);
exit();
