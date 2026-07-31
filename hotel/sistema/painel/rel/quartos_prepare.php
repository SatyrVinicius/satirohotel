<?php

require_once("../../conexao.php");
header('Content-Type: application/json; charset=utf-8');

$dataInicial = @$_POST['dataInicial'];
$dataFinal = @$_POST['dataFinal'];

if ($dataInicial == "") {
    $dataInicial = date('Y-m-d');
}

if ($dataFinal == "") {
    $dataFinal = date('Y-m-d');
}

if (strtotime($dataFinal) < strtotime($dataInicial)) {
    $dataFinal = $dataInicial;
}

$cacheDir = "../pdf/quartos_disponiveis";
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0777, true);
}

$cacheKey = md5($dataInicial . '|' . $dataFinal);
$cacheName = "quartos_" . $cacheKey . ".pdf";
$cacheFile = $cacheDir . "/" . $cacheName;
$cacheTtlSegundos = 900;

if (file_exists($cacheFile) && (time() - @filemtime($cacheFile) <= $cacheTtlSegundos)) {
    echo json_encode([
        'status' => 'ok',
        'cached' => true,
        'url' => 'pdf/quartos_disponiveis/' . $cacheName . '?v=' . @filemtime($cacheFile)
    ]);
    exit();
}

$token_rel = "FKLUY7852";
ob_start();
include("quartos.php");
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

echo json_encode([
    'status' => 'ok',
    'cached' => false,
    'url' => 'pdf/quartos_disponiveis/' . $cacheName . '?v=' . @filemtime($cacheFile)
]);
exit();
