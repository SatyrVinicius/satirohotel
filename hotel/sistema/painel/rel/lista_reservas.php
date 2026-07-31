<?php
if(!isset($pdo)) require_once("../../conexao.php");
include('data_formatada.php');
require_once(__DIR__ . '/modelo_html_helper.php');

if(!isset($token_rel)) $token_rel = @$_GET['token_rel'];
if ($token_rel != 'FKLUY7852') {
    echo '<script>window.location="../../"</script>';
    exit();
}

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'check_in';
$dataInicial = isset($_GET['dataInicial']) ? $_GET['dataInicial'] : date('Y-m-d');
$dataFinal = isset($_GET['dataFinal']) ? $_GET['dataFinal'] : date('Y-m-d');
$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';

if(!in_array($tipo, array('check_in', 'check_out', 'data'), true)){
    $tipo = 'check_in';
}
if($dataInicial == '') $dataInicial = date('Y-m-d');
if($dataFinal == '') $dataFinal = date('Y-m-d');

$dataInicialF = implode('/', array_reverse(explode('-', $dataInicial)));
$dataFinalF = implode('/', array_reverse(explode('-', $dataFinal)));

$nome_tipo = 'CHECK-IN';
if($tipo == 'check_out') $nome_tipo = 'CHECK-OUT';
if($tipo == 'data') $nome_tipo = 'DATA DA VENDA RESERVA';

$datas = ($dataInicial == $dataFinal) ? $dataInicialF : ($dataInicialF . ' a ' . $dataFinalF);
$texto_filtro = 'PERIODO DA APURACAO: ' . $datas;

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

if($codigo != ''){
    $stmt = $pdo->prepare("SELECT * FROM reservas WHERE id = :id ORDER BY $tipo ASC");
    $stmt->bindValue(':id', (int)$codigo, PDO::PARAM_INT);
}else{
    $stmt = $pdo->prepare("SELECT * FROM reservas WHERE $tipo >= :di AND $tipo <= :df ORDER BY $tipo ASC");
    $stmt->bindValue(':di', $dataInicial);
    $stmt->bindValue(':df', $dataFinal);
}
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);

function nomePorId($pdo, $tabela, $id, $campo = 'nome'){
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
    .panel { border: 0; border-radius: 0; }
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
            <div class="subtitle"><b>RELATORIO DE <?php echo $nome_tipo ?></b> - <?php echo mb_strtoupper($texto_filtro) ?> - <?php echo mb_strtoupper($data_hoje) ?></div>
        </div>
    </div>
    <?php if($mostrar_botao_imprimir_modelo){ ?><button class="print-btn" type="button" onclick="window.print()">Imprimir</button><?php } ?>
</div>
<!-- FIM CABECALHO DO RELATORIO -->

<?php if($exibir_marca_dagua_modelo){ ?><img class="marca" src="<?php echo $logo_cabecalho_modelo ?>" alt="marca"> <?php } ?>

<div class="panel">
    <!-- INICIO CABECALHO DA TABELA DE RESERVAS -->
    <table>
        <thead>
            <tr>
                <th style="width:17%">HOSPEDE</th>
                <th style="width:14%">TIPO QUARTO</th>
                <th style="width:7%">QUARTO</th>
                <th style="width:9%">CHECK-IN</th>
                <th style="width:9%">CHECK-OUT</th>
                <th style="width:9%">DATA RESERVA</th>
                <th style="width:9%">VALOR</th>
                <th style="width:11%">NO-SHOW</th>
                <th style="width:15%">FUNCIONARIO</th>
            </tr>
        </thead>
        <tbody>
        <!-- FIM CABECALHO DA TABELA DE RESERVAS -->
        <!-- INICIO LINHAS DE RESERVAS -->
        <?php if(@count($reservas) == 0){ ?>
            <tr><td colspan="9" class="empty">Nenhuma reserva encontrada para este filtro.</td></tr>
        <?php } ?>

        <?php for($i=0; $i<@count($reservas); $i++){
            $row = $reservas[$i];
            $nome_hospede = nomePorId($pdo, 'hospedes', $row['hospede']);
            $nome_tipo = nomePorId($pdo, 'categorias_quartos', $row['tipo_quarto']);
            $numero_quarto = nomePorId($pdo, 'quartos', $row['quarto'], 'numero');
            $nome_func = nomePorId($pdo, 'usuarios', $row['funcionario']);
            $check_inF = implode('/', array_reverse(explode('-', $row['check_in'])));
            $check_outF = implode('/', array_reverse(explode('-', $row['check_out'])));
            $dataF = implode('/', array_reverse(explode('-', $row['data'])));
            $valorF = @number_format($row['valor'], 2, ',', '.');
            $no_showF = @number_format($row['no_show'], 2, ',', '.');
        ?>
            <tr>
                <td><?php echo $nome_hospede ?></td>
                <td><?php echo $nome_tipo ?></td>
                <td><?php echo $numero_quarto ?></td>
                <td><?php echo $check_inF ?></td>
                <td><?php echo $check_outF ?></td>
                <td><?php echo $dataF ?></td>
                <td class="money">R$ <?php echo $valorF ?></td>
                <td>R$ <?php echo $no_showF ?></td>
                <td><?php echo $nome_func ?></td>
            </tr>
        <?php } ?>
        <!-- FIM LINHAS DE RESERVAS -->
        </tbody>
    </table>
</div>
</body>
</html>
