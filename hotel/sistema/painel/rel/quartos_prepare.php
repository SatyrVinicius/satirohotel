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

$urlRelatorio = 'rel/quartos.php?token_rel=FKLUY7852&dataInicial=' . urlencode($dataInicial) . '&dataFinal=' . urlencode($dataFinal);

echo json_encode([
    'status' => 'ok',
    'url' => $urlRelatorio
]);
exit();
