<?php 
@session_start();
require_once("../../conexao.php");
require_once(__DIR__ . '/pdf_engine.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if($id <= 0){
    http_response_code(400);
    exit('Reserva inválida!');
}

$enviar = @$_GET['enviar'];

$download = @$_GET['download'];

$rapido = @$_GET['rapido'];

$token_rel = "FKLUY7852";
$logoAbs = realpath(__DIR__ . '/../img/logo.jpg');
if($logoAbs){
    $logo_relatorio = 'file:///' . str_replace('\\', '/', $logoAbs);
}
ob_start();
include("reserva.php");
$html = ob_get_clean();

$arquivo = "../pdf/reservas/reserva_".$id.".pdf";

$resultadoGeracao = gerar_pdf_por_html($html, $arquivo, 'portrait');
if(!$resultadoGeracao['ok']){
    http_response_code(500);
    exit('Falha ao gerar PDF!');
}







$query = $pdo->query("SELECT * from reservas where id = '$id' ");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$hospede = $res[0]['hospede'];

$ref_pgto = $res[0]['ref_pgto'];



// Consulta externa so e necessaria quando houver envio por WhatsApp.
if($rapido != 'sim' && $enviar == 'sim'){
    ob_start();
    require("../../pagamentos/consultar_pagamento.php");
    ob_end_clean();
}



$query = $pdo->query("SELECT * from hospedes where id = '$hospede' ");

$res = $query->fetchAll(PDO::FETCH_ASSOC);

$telefone = $res[0]['telefone'];



// Cria uma chave única para identificar essa reserva
$chave_envio = 'enviado_reserva_' . $id;

// Verifica se já enviou antes
if ($enviar == 'sim' && $api_whatsapp == 'Sim' && empty($_SESSION[$chave_envio])) {
    
    // Marca como enviado
    $_SESSION[$chave_envio] = true;

    // Envia só uma vez
    $telefone_envio = '55' . preg_replace('/[ ()-]+/', '', $telefone);
    $mensagem = '';
    $url_envio = $url_sistema . "painel/pdf/reservas/reserva_" . $id . ".pdf";

    ob_start();
    require("../api/doc.php");
    ob_end_clean();
}
$nomeArquivo = "reserva_{$id}.pdf";
header('Content-Type: application/pdf');
header('Content-Length: ' . filesize($arquivo));
header('Content-Disposition: ' . (($download == 'sim') ? 'attachment' : 'inline') . '; filename="' . $nomeArquivo . '"');
readfile($arquivo);
exit();



 ?>