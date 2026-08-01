<?php

require_once(__DIR__ . '/../../conexao.php');
header('Content-Type: application/json; charset=utf-8');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if($id <= 0){
	echo json_encode(['status' => 'error', 'message' => 'Reserva inválida']);
	exit();
}

$urlRelatorio = 'rel/reserva.php?token_rel=FKLUY7852&id=' . $id;

echo json_encode(['status' => 'ok', 'url' => $urlRelatorio]);
