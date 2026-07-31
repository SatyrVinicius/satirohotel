<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$cacheDir = "../pdf/estoque_baixo";
if(!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);

$cacheName = "estoque_baixo.pdf";
$cacheFile = $cacheDir . '/' . $cacheName;
$ttl = 900;

if(file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $ttl)){
	echo json_encode(['status'=>'ok','cached'=>true,'url'=>'pdf/estoque_baixo/'.$cacheName.'?v='.@filemtime($cacheFile)]);
	exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("estoque.php");
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

echo json_encode(['status'=>'ok','cached'=>false,'url'=>'pdf/estoque_baixo/'.$cacheName.'?v='.@filemtime($cacheFile)]);
exit();
