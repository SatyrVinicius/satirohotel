<?php
// paginas/mapa_reservas/mapa.php
header('Content-Type: application/json; charset=UTF-8');

// ajuste o caminho da conexão ao seu projeto
require_once('../../../conexao.php');

// Datas (YYYY-mm-dd) – se não vier por GET, usa hoje → +30 dias
$ini = isset($_GET['ini']) ? $_GET['ini'] : date('Y-m-d');
$fim = isset($_GET['fim']) ? $_GET['fim'] : date('Y-m-d', strtotime('+30 days'));

try {
  // QUARTOS como resources: traz também o NOME da categoria
  $sqlResources = "
    SELECT 
      q.id,
      q.numero            AS title,
      cq.id               AS groupId,    -- id da categoria (se precisar)
      cq.nome             AS groupName   -- NOME da categoria (para exibir no agrupamento)
    FROM quartos q
    JOIN categorias_quartos cq ON cq.id = q.tipo
    WHERE q.ativo = 'Sim'
    ORDER BY cq.nome, q.numero
  ";
  $resources = $pdo->query($sqlResources)->fetchAll(PDO::FETCH_ASSOC);

  // RESERVAS no intervalo (checkout exclusivo: [check_in, check_out) )
  $stmtRes = $pdo->prepare("
    SELECT 
      r.id, r.quarto, r.check_in, r.check_out, r.hospede,
      r.hora_checkin, r.hora_checkout,
      h.nome AS hospede_nome
    FROM reservas r
    JOIN hospedes h ON h.id = r.hospede
    WHERE r.check_in < :fim AND r.check_out > :ini
    ORDER BY r.check_in
  ");
  $stmtRes->execute([':ini' => $ini, ':fim' => $fim]);

  $events = [];
  while ($r = $stmtRes->fetch(PDO::FETCH_ASSOC)) {
    $status = ($r['hora_checkin'] && !$r['hora_checkout']) ? 'hospedado'
            : (!$r['hora_checkin'] ? 'reservado' : 'finalizado');

    // Opcional: não mostrar finalizadas
    if ($status === 'finalizado') continue;

    $events[] = [
      'id'         => 'res-' . $r['id'],
      'resourceId' => $r['quarto'],
      'start'      => $r['check_in'],
      'end'        => $r['check_out'],
      'title'      => $r['hospede_nome'],
      'classNames' => ['evt', 'status-' . $status],
    ];
  }

  // BLOQUEIOS por categoria no intervalo
  $stmtBlk = $pdo->prepare("
    SELECT b.id, b.categoria, b.data_inicial, b.data_final
    FROM bloqueio_datas b
    WHERE b.data_inicial < :fim AND b.data_final > :ini
  ");
  $stmtBlk->execute([':ini' => $ini, ':fim' => $fim]);
  $bloqueios = $stmtBlk->fetchAll(PDO::FETCH_ASSOC);

  // Indexa quartos por categoria para replicar bloqueio
  $quartosPorCat = [];
  foreach ($resources as $r) {
    // groupId = id da categoria; groupName = nome da categoria
    $quartosPorCat[$r['groupId']][] = $r['id'];
  }

  foreach ($bloqueios as $b) {
    foreach ($quartosPorCat[$b['categoria']] ?? [] as $qid) {
      $events[] = [
        'id'         => 'blk-' . $b['id'] . '-' . $qid,
        'resourceId' => $qid,
        'start'      => $b['data_inicial'],
        'end'        => $b['data_final'],
        'title'      => 'Bloqueado',
        'classNames' => ['evt', 'bloqueio'],
        'display'    => 'background',
      ];
    }
  }

  echo json_encode(['resources' => $resources, 'events' => $events], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => true, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}
