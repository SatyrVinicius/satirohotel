<?php
if(!isset($pdo)) require_once("../../conexao.php");
include('data_formatada.php');
require_once(__DIR__ . '/modelo_html_helper.php');

if(!isset($token_rel)) $token_rel = @$_GET['token_rel'];
if(!isset($id)) $id = @$_GET['id'];

if ($token_rel != 'FKLUY7852') {
    echo '<script>window.location="../../"</script>';
    exit();
}

$id = (int)$id;
if($id <= 0){
    echo 'Reserva invalida!';
    exit();
}

$query = $pdo->query("SELECT * FROM reservas WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
    echo 'Reserva nao encontrada!';
    exit();
}

$r = $res[0];
$hospede = $r['hospede'];
$quarto = $r['quarto'];

$query2 = $pdo->query("SELECT * FROM quartos WHERE id = '$quarto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$numero_quarto = @$res2[0]['numero'];

$query2 = $pdo->query("SELECT * FROM hospedes WHERE id = '$hospede'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_hospede = @$res2[0]['nome'];

$texto_filtro = 'HOSPEDE RESPONSAVEL: ' . $nome_hospede;

$modelo_html = obter_modelo_relatorio_html();
$cor_primaria_modelo = $modelo_html['cor_primaria'];
$cor_fundo_cabecalho_modelo = $modelo_html['cor_fundo_cabecalho'];
$cor_subtitulo_modelo = $modelo_html['cor_subtitulo'];
$fonte_base_modelo = $modelo_html['fonte_base'];
$tamanho_base_modelo = (int)$modelo_html['tamanho_base'];
$mostrar_botao_imprimir_modelo = ($modelo_html['mostrar_botao_imprimir'] == 'Sim');
$mostrar_logo_cabecalho_modelo = ($modelo_html['mostrar_logo_cabecalho'] == 'Sim');
$nome_sistema_cabecalho_modelo = trim($modelo_html['nome_sistema_cabecalho']) != '' ? $modelo_html['nome_sistema_cabecalho'] : $nome_sistema;

$logo_cabecalho_modelo = $url_sistema . 'img/logo.jpg';
if (!empty($modelo_html['logo_cabecalho_arquivo'])) {
    $logo_cabecalho_modelo = '../img/' . $modelo_html['logo_cabecalho_arquivo'];
}

$exibir_marca_dagua_modelo = ($marca_dagua == 'Sim');
if($modelo_html['mostrar_marca_dagua'] == 'Sim') $exibir_marca_dagua_modelo = true;
if($modelo_html['mostrar_marca_dagua'] == 'Nao') $exibir_marca_dagua_modelo = false;
if($mostrar_logo_cabecalho_modelo) $exibir_marca_dagua_modelo = false;

$total_servicos = 0;
$total_consumo = 0;
$total_geral = 0;

$query = $pdo->query("SELECT * FROM receber WHERE (referencia = 'Venda' OR referencia = 'Serviço' OR referencia = 'Servico') AND id_ref = '$id' ORDER BY data_lanc ASC, hora ASC");
$lancamentos = $query->fetchAll(PDO::FETCH_ASSOC);

function nomePorIdCons($pdo, $tabela, $id, $campo = 'nome'){
    $id = (int)$id;
    if($id <= 0) return '';
    $q = $pdo->query("SELECT $campo FROM $tabela WHERE id = '$id' LIMIT 1");
    $r = $q->fetchAll(PDO::FETCH_ASSOC);
    return @count($r) ? $r[0][$campo] : '';
}
?>
<!DOCTYPE html>
<html>
<head>
<style>
/* INICIO CSS DO RELATORIO */
:root {
    --line: #d9e0e8;
    --head: #f3f6fa;
}
body {
    margin: 0;
    padding: 18px;
    background: #f5f7fb;
    font-family: <?php echo $fonte_base_modelo ?>;
    font-size: <?php echo $tamanho_base_modelo ?>px;
    color: #22303c;
}
.report-header {
    max-width: 1180px;
    margin: 0 auto 14px auto;
    padding: 18px 20px;
    border-radius: 10px;
    background: <?php echo $cor_fundo_cabecalho_modelo ?>;
    border: 1px solid var(--line);
}
.brand-row {
    display: flex;
    align-items: center;
    gap: 12px;
}
.brand-row img {
    width: 100px;
    height: 100px;
    object-fit: contain;
}
.brand-title {
    font-size: 24px;
    font-weight: 700;
}
.subtitle {
    margin-top: 4px;
    color: <?php echo $cor_subtitulo_modelo ?>;
    font-size: 13px;
}
.print-btn {
    margin-top: 10px;
    background: <?php echo $cor_primaria_modelo ?>;
    color: #fff;
    border: 0;
    border-radius: 6px;
    padding: 8px 14px;
    cursor: pointer;
}
.panel {
    max-width: 1180px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 10px;
    overflow: hidden;
}
table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}
th, td {
    border: 1px solid var(--line);
    padding: 7px;
    font-size: 11px;
    text-align: left;
}
th {
    background: var(--head);
    font-weight: 700;
}
.money { color: #b42318; }
.status-paid { color: #0b7a33; font-weight: 700; }
.status-open { color: #b42318; font-weight: 700; }
.totals {
    max-width: 1180px;
    margin: 10px auto 0 auto;
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 10px;
    padding: 10px 12px;
    display: flex;
    gap: 16px;
    justify-content: flex-end;
    font-size: 12px;
}
.marca {
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 45%;
    opacity: 0.05;
    pointer-events: none;
}
.empty {
    padding: 16px;
    text-align: center;
    color: #667085;
}
@media print {
    body { padding: 0; background: #fff; }
    .report-header { border: 0; border-radius: 0; padding: 0; margin-bottom: 8px; }
    .panel, .totals { border: 0; border-radius: 0; }
    .print-btn { display: none; }
    @page { margin: 12mm 8mm 12mm 8mm; }
}
/* FIM CSS DO RELATORIO */
</style>
</head>
<body>
<!-- INICIO CABECALHO DO RELATORIO -->
<div class="report-header">
    <div class="brand-row">
        <?php if($mostrar_logo_cabecalho_modelo){ ?><img src="<?php echo $logo_cabecalho_modelo ?>" alt="logo"><?php } ?>
        <div>
            <div class="brand-title"><?php echo mb_strtoupper($nome_sistema_cabecalho_modelo) ?></div>
            <div class="subtitle"><b>DETALHAMENTO DE CONSUMO - QUARTO <?php echo $numero_quarto ?></b> - <?php echo mb_strtoupper($texto_filtro) ?></div>
        </div>
    </div>
    <?php if($mostrar_botao_imprimir_modelo){ ?><button class="print-btn" type="button" onclick="window.print()">Imprimir</button><?php } ?>
</div>
<!-- FIM CABECALHO DO RELATORIO -->

<?php if($exibir_marca_dagua_modelo){ ?><img class="marca" src="<?php echo $logo_cabecalho_modelo ?>" alt="marca"> <?php } ?>

<div class="panel">
    <!-- INICIO TABELA DE ITENS DE CONSUMO -->
    <table>
        <thead>
            <tr>
                <th style="width:24%">DESCRICAO</th>
                <th style="width:10%">VALOR</th>
                <th style="width:9%">DATA</th>
                <th style="width:8%">HORA</th>
                <th style="width:18%">HOSPEDE</th>
                <th style="width:18%">FUNCIONARIO</th>
                <th style="width:13%">STATUS</th>
            </tr>
        </thead>
        <tbody>
        <?php if(@count($lancamentos) == 0){ ?>
            <tr><td colspan="7" class="empty">Sem lancamentos de consumo para esta reserva.</td></tr>
        <?php } ?>

        <?php for($i=0; $i<@count($lancamentos); $i++){
            $l = $lancamentos[$i];
            $valor = (float)$l['valor'];
            $data_lancF = implode('/', array_reverse(explode('-', $l['data_lanc'])));
            $horaF = date('H:i', strtotime($l['hora']));
            $nome_hospede_l = nomePorIdCons($pdo, 'hospedes', $l['hospede']);
            $nome_usuario_l = nomePorIdCons($pdo, 'usuarios', $l['usuario_lanc']);
            $status = ($l['pago'] == 'Sim') ? 'PAGO' : 'PENDENTE';
            if($l['referencia'] == 'Venda') $total_consumo += $valor; else $total_servicos += $valor;
        ?>
            <tr>
                <td><?php echo $l['descricao'] ?></td>
                <td class="money">R$ <?php echo @number_format($valor, 2, ',', '.') ?></td>
                <td><?php echo $data_lancF ?></td>
                <td><?php echo $horaF ?></td>
                <td><?php echo $nome_hospede_l ?></td>
                <td><?php echo $nome_usuario_l ?></td>
                <td class="<?php echo $status == 'PAGO' ? 'status-paid' : 'status-open' ?>"><?php echo $status ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <!-- FIM TABELA DE ITENS DE CONSUMO -->
</div>

<?php
$total_geral = $total_consumo + $total_servicos;
$total_consumoF = @number_format($total_consumo, 2, ',', '.');
$total_servicosF = @number_format($total_servicos, 2, ',', '.');
$total_geralF = @number_format($total_geral, 2, ',', '.');
?>

<!-- INICIO TOTAIS DE CONSUMO -->
<div class="totals">
    <div><b>Total Servicos:</b> R$ <?php echo $total_servicosF ?></div>
    <div><b>Total Consumo:</b> R$ <?php echo $total_consumoF ?></div>
    <div><b>Total Geral:</b> <span class="money"><b>R$ <?php echo $total_geralF ?></b></span></div>
</div>
<!-- FIM TOTAIS DE CONSUMO -->
</body>
</html>
