<?php 
if ($token_rel != 'FKLUY7852') {
  echo '<script>window.location="../../"</script>';
  exit();
}

include('data_formatada.php');

// datas de entrada (YYYY-mm-dd)
$dataInicial = $dataInicial ?? $_POST['dataInicial'] ?? date('Y-m-d');
$dataFinal   = $dataFinal   ?? $_POST['dataFinal']   ?? date('Y-m-d');

// formatações
$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF   = implode('/', array_reverse(explode('-', $dataFinal)));
$datas = ($dataInicial == $dataFinal) ? $dataInicialF : ($dataInicialF.' À '.$dataFinalF);
$texto_filtro = "PERÍODO DA APURAÇÃO : ".$datas;

// nº de diárias (checkout exclusivo), mínimo 1
$diffDias = (int) floor((strtotime($dataFinal) - strtotime($dataInicial)) / 86400);
$diarias  = max(1, $diffDias);

// total de quartos ativos
$total_quartos = (int) $pdo->query("SELECT COUNT(*) FROM quartos WHERE ativo='Sim'")->fetchColumn();

// quartos disponíveis
$sql = "
SELECT 
  q.id,
  q.numero,
  cq.id   AS id_categoria,
  cq.nome AS tipo_quarto,
  cq.valor AS valor_diaria
FROM quartos q
JOIN categorias_quartos cq ON cq.id = q.tipo
WHERE q.ativo = 'Sim'
  -- não pode ter reservas que se sobreponham
  AND NOT EXISTS (
    SELECT 1
    FROM reservas r
    WHERE r.quarto = q.id
      AND r.check_in  < :dataFinal
      AND r.check_out > :dataInicial
  )
  -- não pode haver bloqueio da categoria
  AND NOT EXISTS (
    SELECT 1
    FROM bloqueio_datas b
    WHERE b.categoria = cq.id
      AND b.data_inicial < :dataFinal
      AND b.data_final   > :dataInicial
  )
ORDER BY cq.nome, q.numero
";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  ':dataInicial' => $dataInicial,
  ':dataFinal'   => $dataFinal,
]);
$disponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

$qtd_disponiveis = count($disponiveis);
$qtd_ocupados    = max(0, $total_quartos - $qtd_disponiveis);
$ocupacao        = ($total_quartos > 0) ? ($qtd_ocupados / $total_quartos) * 100 : 0;

// Agrupamento por categoria
$grupo = [];
foreach ($disponiveis as $row) {
  $tipo = $row['tipo_quarto'] ?? '—';
  if (!isset($grupo[$tipo])) {
    $grupo[$tipo] = ['itens' => [], 'min' => null, 'max' => null];
  }
  $grupo[$tipo]['itens'][] = $row;
  $v = (float)$row['valor_diaria'];
  $grupo[$tipo]['min'] = is_null($grupo[$tipo]['min']) ? $v : min($grupo[$tipo]['min'], $v);
  $grupo[$tipo]['max'] = is_null($grupo[$tipo]['max']) ? $v : max($grupo[$tipo]['max'], $v);
}

// helper moeda
$moeda = function($v) { return 'R$ '.number_format((float)$v, 2, ',', '.'); };

?>
<!DOCTYPE html>
<html>
<head>
<style>
@import url('https://fonts.cdnfonts.com/css/tw-cen-mt-condensed');
@page { margin: 145px 20px 25px 20px; }
#header { position: fixed; left:0; top:-110px; bottom:100px; right:0; height:35px; text-align:center; padding-bottom:100px; }
#content { margin-top: 0px; }
#footer { position: fixed; left:0; bottom:-60px; right:0; height:80px; }
#footer .page:after { content: counter(page, my-sec-counter); }
body { font-family: 'Tw Cen MT', sans-serif; }
.marca{ position:fixed; left:50; top:100; width:80%; opacity:8%; }
.tbl { width:100%; table-layout: fixed; font-size:11px; border-collapse: collapse; }
.tbl td, .tbl th { border-bottom: 1px solid #ccc; padding: 4px; }
.tbl thead td { background:#CCC; font-weight:bold; }
.resumo { font-size:12px; margin-bottom:10px; }
</style>
</head>

<body>
<?php if($marca_dagua == 'Sim'){ ?>
<img class="marca" src="<?php echo $url_sistema ?>img/logo.jpg">	
<?php } ?>

<div id="header">
  <div style="border-style: solid; font-size: 10px; height: 50px;">
    <table style="width: 100%; border: 0;">
      <tr>
        <td style="width: 7%; text-align: left;">
          <img style="margin-top: 7px; margin-left: 7px;" src="<?php echo $url_sistema ?>img/logo.jpg" width="45px">
        </td>
        <td style="width: 33%; text-align: left; font-size: 13px;">
          <?php echo mb_strtoupper($nome_sistema) ?>	
        </td>
        <td style="width: 60%; text-align: right; font-size: 9px;padding-right: 10px;">
          <b><big>RELATÓRIO DE QUARTOS DISPONÍVEIS</big></b><br> 
          <?php echo mb_strtoupper($texto_filtro) ?> <br> <?php echo mb_strtoupper($data_hoje) ?>
        </td>
      </tr>		
    </table>
  </div>
  <br>
</div>

<div id="footer">
  <hr style="margin-bottom: 0;">
  <table style="width:100%;">
    <tr>
      <td style="width:60%; font-size: 10px; text-align: left;"><?php echo $nome_sistema ?> Telefone: <?php echo $telefone_sistema ?></td>
      <td style="width:40%; font-size: 10px; text-align: right;"><p class="page">Página  </p></td>
    </tr>
  </table>
</div>

<div id="content">

<div class="resumo">
  <b>Total de Quartos:</b> <?php echo $total_quartos ?> &nbsp;|&nbsp;
  <b>Disponíveis:</b> <?php echo $qtd_disponiveis ?> &nbsp;|&nbsp;
  <b>Ocupados:</b> <?php echo $qtd_ocupados ?> &nbsp;|&nbsp;
  <b>Ocupação:</b> <?php echo number_format($ocupacao,1,',','.') ?>% &nbsp;|&nbsp;
  <b>Diárias:</b> <?php echo $diarias ?>
</div>

<table class="tbl">
  <thead>
    <tr>
      <td style="width:10%">QUARTO</td>
      <td style="width:40%">TIPO QUARTO</td>	
      <td style="width:15%">VALOR DIÁRIA</td>
      <td style="width:10%">DIÁRIAS</td>
      <td style="width:25%">VALOR TOTAL</td>	
    </tr>
  </thead>
  <tbody>
  <?php foreach($grupo as $tipo=>$g): ?>
    <tr style="background:#EEE; font-weight:bold;">
      <td colspan="5"><?php echo $tipo ?> 
        (<?php echo count($g['itens']) ?> disponíveis, valores <?php echo $moeda($g['min']) ?> a <?php echo $moeda($g['max']) ?>)
      </td>
    </tr>
    <?php foreach($g['itens'] as $q): ?>
    <tr>
      <td><?php echo $q['numero'] ?></td>
      <td><?php echo $tipo ?></td>
      <td><?php echo $moeda($q['valor_diaria']) ?></td>
      <td><?php echo $diarias ?></td>
      <td><?php echo $moeda($q['valor_diaria'] * $diarias) ?></td>
    </tr>
    <?php endforeach; ?>
  <?php endforeach; ?>
  </tbody>
</table>

</div>
</body>
</html>
