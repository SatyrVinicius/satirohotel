<?php
if(!isset($pdo)) require_once(__DIR__ . '/../../conexao.php');
require_once(__DIR__ . '/data_formatada.php');
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

$modelo_html = obter_modelo_relatorio_html();
$cor_primaria_modelo = $modelo_html['cor_primaria'];
$cor_fundo_cabecalho_modelo = $modelo_html['cor_fundo_cabecalho'];
$cor_subtitulo_modelo = $modelo_html['cor_subtitulo'];
$fonte_base_modelo = $modelo_html['fonte_base'];
$tamanho_base_modelo = (int)$modelo_html['tamanho_base'];
$mostrar_botao_imprimir_modelo = ($modelo_html['mostrar_botao_imprimir'] == 'Sim');
$mostrar_logo_cabecalho_modelo = ($modelo_html['mostrar_logo_cabecalho'] == 'Sim');
$nome_sistema_cabecalho_modelo = trim($modelo_html['nome_sistema_cabecalho']) != '' ? $modelo_html['nome_sistema_cabecalho'] : $nome_sistema;

$logo_relatorio = $url_sistema . 'img/logo.jpg';
if (!empty($modelo_html['logo_cabecalho_arquivo'])) {
    $logo_relatorio = '../img/' . $modelo_html['logo_cabecalho_arquivo'];
}

$exibir_marca_dagua_modelo = ($marca_dagua == 'Sim');
if($modelo_html['mostrar_marca_dagua'] == 'Sim'){
    $exibir_marca_dagua_modelo = true;
}else if($modelo_html['mostrar_marca_dagua'] == 'Nao'){
    $exibir_marca_dagua_modelo = false;
}

// Evita duplicidade visual: quando a logo de cabeçalho estiver ativa, a marca d'água fica oculta.
if($mostrar_logo_cabecalho_modelo){
    $exibir_marca_dagua_modelo = false;
}

$query = $pdo->query("SELECT * FROM reservas WHERE id = '$id'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
    echo 'Reserva nao encontrada!';
    exit();
}

$r = $res[0];
$hospede = $r['hospede'];
$tipo_quarto = $r['tipo_quarto'];
$quarto = $r['quarto'];
$funcionario = $r['funcionario'];
$check_in = $r['check_in'];
$check_out = $r['check_out'];
$valor = (float)$r['valor'];
$no_show = (float)$r['no_show'];
$hospedes = $r['hospedes'];
$obs = $r['obs'];
$data = $r['data'];
$forma_pgto = $r['forma_pgto'];

$hora_checkin = $r['hora_checkin'];
$hora_checkout = $r['hora_checkout'];
$funcionario_checkin = $r['funcionario_checkin'];
$funcionario_checkout = $r['funcionario_checkout'];
$tipo_pgto_checkin = $r['tipo_pgto_checkin'];
$tipo_pgto_checkout = $r['tipo_pgto_checkout'];
$valor_checkout = (float)$r['valor_checkout'];
$valor_checkin = (float)$r['valor_checkin'];
$indicacao = $r['indicacao'];
$descricao_taxa = $r['descricao_taxa'];
$valor_taxa = (float)$r['valor_taxa'];

$query2 = $pdo->query("SELECT * FROM categorias_quartos WHERE id = '$tipo_quarto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_tipo = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM quartos WHERE id = '$quarto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$numero_quarto = @$res2[0]['numero'];

$query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$funcionario'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_func = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM hospedes WHERE id = '$hospede'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_hospede = @$res2[0]['nome'];
$cpf = @$res2[0]['cpf'];
$telefone_hospede = @$res2[0]['telefone'];

$query2 = $pdo->query("SELECT * FROM formas_pgto WHERE id = '$forma_pgto'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$forma_pgto_nome = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM formas_pgto WHERE id = '$tipo_pgto_checkin'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$forma_pgto_checkin = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM formas_pgto WHERE id = '$tipo_pgto_checkout'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$forma_pgto_checkout = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$funcionario_checkin'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_func_checkin = @$res2[0]['nome'];

$query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '$funcionario_checkout'");
$res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
$nome_func_checkout = @$res2[0]['nome'];

$check_inF = implode('/', array_reverse(@explode('-', $check_in)));
$check_outF = implode('/', array_reverse(@explode('-', $check_out)));
$dataF = implode('/', array_reverse(@explode('-', $data)));

$valor_reservaF = @number_format($valor, 2, ',', '.');
$no_showF = @number_format($no_show, 2, ',', '.');
$valor_restanteF = @number_format($valor - $no_show, 2, ',', '.');
$valor_checkinF = @number_format($valor_checkin, 2, ',', '.');
$valor_checkoutF = @number_format($valor_checkout, 2, ',', '.');
$valor_taxaF = @number_format($valor_taxa, 2, ',', '.');

$total_servicos = 0;
$total_consumo = 0;
$linhas_consumo_html = '';

$queryCons = $pdo->query("SELECT * FROM receber WHERE (referencia = 'Venda' OR referencia = 'Serviço' OR referencia = 'Servico') AND id_ref = '$id' ORDER BY data_lanc ASC, hora ASC");
$resCons = $queryCons->fetchAll(PDO::FETCH_ASSOC);
$linhasCons = @count($resCons);

if($linhasCons > 0){
    for($i=0; $i<$linhasCons; $i++){
        $c = $resCons[$i];
        $descricao = $c['descricao'];
        $valorC = (float)$c['valor'];
        $data_lancF = implode('/', array_reverse(@explode('-', $c['data_lanc'])));
        $horaF = date("H:i", strtotime($c['hora']));
        $referencia = $c['referencia'];
        $pago = $c['pago'];

        $query2 = $pdo->query("SELECT * FROM usuarios WHERE id = '" . $c['usuario_lanc'] . "'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_usu_lanc = @count($res2) > 0 ? $res2[0]['nome'] : 'Sem Usuário';

        $query2 = $pdo->query("SELECT * FROM hospedes WHERE id = '" . $c['hospede'] . "'");
        $res2 = $query2->fetchAll(PDO::FETCH_ASSOC);
        $nome_hospede_linha = @count($res2) > 0 ? $res2[0]['nome'] : '';

        if($referencia == 'Venda'){
            $total_consumo += $valorC;
        }else{
            $total_servicos += $valorC;
        }

        $status_pgto = ($pago == 'Sim') ? 'PAGO' : 'PENDENTE';
        $valorCF = @number_format($valorC, 2, ',', '.');

        $linhas_consumo_html .= '<tr>';
        $linhas_consumo_html .= '<td style="width:24%">' . $descricao . '</td>';
        $linhas_consumo_html .= '<td style="width:10%; color:red">R$ ' . $valorCF . '</td>';
        $linhas_consumo_html .= '<td style="width:9%">' . $data_lancF . '</td>';
        $linhas_consumo_html .= '<td style="width:8%">' . $horaF . '</td>';
        $linhas_consumo_html .= '<td style="width:18%">' . $nome_hospede_linha . '</td>';
        $linhas_consumo_html .= '<td style="width:18%">' . $nome_usu_lanc . '</td>';
        $linhas_consumo_html .= '<td style="width:13%; color:' . ($pago == 'Sim' ? 'green' : 'red') . '"><b>' . $status_pgto . '</b></td>';
        $linhas_consumo_html .= '</tr>';
    }
}else{
    $linhas_consumo_html = '<tr><td colspan="7" style="padding:8px;">Sem lançamentos de consumo para esta reserva.</td></tr>';
}

$total_final = $total_consumo + $total_servicos;
$total_consumoF = @number_format($total_consumo, 2, ',', '.');
$total_servicosF = @number_format($total_servicos, 2, ',', '.');
$total_finalF = @number_format($total_final, 2, ',', '.');
?>
<!DOCTYPE html>
<html>
<head>
<!-- INICIO CSS DO RELATORIO -->
<style>
@media screen {
    body { margin:0; padding:20px; background:#f6f7fb; }
    .screen-report-header { display:block; max-width:1200px; margin:0 auto 18px auto; padding:20px 24px; background:<?php echo $cor_fundo_cabecalho_modelo ?>; border-radius:10px; box-shadow:0 4px 18px rgba(0,0,0,0.06); }
    .screen-brand-row { display:flex; align-items:center; gap:10px; margin-bottom:8px; }
    .screen-brand-row img { width:100px; height:100px; object-fit:contain; }
    .screen-report-header .titulo { font-size:22px; font-weight:bold; margin-bottom:8px; }
    .screen-report-header .subtitulo { font-size:13px; color:<?php echo $cor_subtitulo_modelo ?>; }
    .screen-print-action { margin-top:12px; }
    .screen-print-action button { background:<?php echo $cor_primaria_modelo ?>; color:#fff; border:0; padding:8px 14px; border-radius:6px; font-size:13px; cursor:pointer; }
    .box { max-width:1200px; margin:0 auto 12px auto; background:#fff; padding:12px 15px; border-radius:8px; box-shadow:0 4px 18px rgba(0,0,0,0.06); }
    table { width:100%; border-collapse:collapse; table-layout:fixed; }
    th, td { border:1px solid #ddd; padding:6px; font-size:11px; }
    th { background:#f1f1f1; text-align:left; }
    .marca { position:fixed; left:50%; top:50%; transform:translate(-50%, -50%); width:45%; opacity:6%; }
}
@media print {
    body { margin:0; padding:0; }
    .screen-print-action { display:none; }
    .box, .screen-report-header { box-shadow:none; border-radius:0; }
    @page { margin: 12mm 10mm 12mm 10mm; }
}
body { font-family: <?php echo $fonte_base_modelo ?>; font-size: <?php echo $tamanho_base_modelo ?>px; }
</style>
<!-- FIM CSS DO RELATORIO -->
</head>
<body>
<!-- INICIO CABECALHO DO RELATORIO -->
<div class="screen-report-header">
    <div class="screen-brand-row">
        <?php if($mostrar_logo_cabecalho_modelo){ ?><img src="<?php echo $logo_relatorio ?>" style="width:100px; height:100px; object-fit:contain;"><?php } ?>
        <div class="titulo"><?php echo mb_strtoupper($nome_sistema_cabecalho_modelo) ?></div>
    </div>
    <div class="subtitulo"><b>DETALHAMENTO DE RESERVA - QUARTO <?php echo $numero_quarto ?></b> - HÓSPEDE RESPONSÁVEL: <?php echo $nome_hospede ?></div>
    <?php if($mostrar_botao_imprimir_modelo){ ?><div class="screen-print-action"><button type="button" onclick="window.print()">Imprimir</button></div><?php } ?>
</div>
<!-- FIM CABECALHO DO RELATORIO -->

<?php if($exibir_marca_dagua_modelo){ ?>
<img class="marca" src="<?php echo $logo_relatorio ?>">
<?php } ?>

<!-- INICIO BLOCOS PRINCIPAIS DA RESERVA -->
<div class="box">
    <table>
        <tr>
            <th>Nº RESERVA</th><th>CHECK-IN</th><th>CHECK-OUT</th><th>HÓSPEDES</th><th>RESERVADO EM</th>
        </tr>
        <tr>
            <td><?php echo $id ?></td><td><?php echo $check_inF ?></td><td><?php echo $check_outF ?></td><td><?php echo $hospedes ?></td><td><?php echo $dataF ?></td>
        </tr>
    </table>
</div>
<!-- FIM BLOCOS PRINCIPAIS DA RESERVA -->

<!-- INICIO DETALHAMENTO DE CONSUMO -->
<div class="box">
    <table>
        <tr><th>HÓSPEDE</th><th>TELEFONE</th><th>CPF</th></tr>
        <tr><td><?php echo $nome_hospede ?></td><td><?php echo $telefone_hospede ?></td><td><?php echo $cpf ?></td></tr>
    </table>
</div>

<div class="box">
    <table>
        <tr><th>FUNCIONÁRIO RESERVA</th><th>TIPO DE QUARTO</th><th>NÚMERO DO QUARTO</th><th>INDICAÇÃO</th></tr>
        <tr><td><?php echo $nome_func ?></td><td><?php echo $nome_tipo ?></td><td><?php echo $numero_quarto ?></td><td><?php echo $indicacao ?></td></tr>
    </table>
</div>

<div class="box">
    <table>
        <tr><th>VALOR RESERVA</th><th>VALOR ENTRADA</th><th>VALOR RESTANTE</th><th>FORMA PGTO</th></tr>
        <tr><td><?php echo $valor_reservaF ?></td><td><?php echo $no_showF ?></td><td><?php echo $valor_restanteF ?></td><td><?php echo $forma_pgto_nome ?></td></tr>
    </table>
</div>

<?php if($obs != ''){ ?>
<div class="box"><b>Observações:</b> <?php echo $obs ?></div>
<?php } ?>

<?php if($hora_checkin != ''){ ?>
<div class="box">
    <div style="margin-bottom:8px;"><b>DETALHAMENTO DO CHECK-IN</b></div>
    <table>
        <tr><th>VALOR CHECKIN</th><th>HORA</th><th>FUNCIONÁRIO</th><th>FORMA PGTO</th></tr>
        <tr><td><?php echo $valor_checkinF ?></td><td><?php echo $hora_checkin ?></td><td><?php echo $nome_func_checkin ?></td><td><?php echo $forma_pgto_checkin ?></td></tr>
    </table>
</div>
<?php } ?>

<?php if($hora_checkout != ''){ ?>
<div class="box">
    <div style="margin-bottom:8px;"><b>DETALHAMENTO DO CHECK-OUT</b><?php if($valor_taxa > 0){ ?> <small>(Taxa: <?php echo $descricao_taxa ?> R$ <span style="color:red"><?php echo $valor_taxaF ?></span>)</small><?php } ?></div>
    <table>
        <tr><th>VALOR CHECKOUT</th><th>HORA</th><th>FUNCIONÁRIO</th><th>FORMA PGTO</th></tr>
        <tr><td><?php echo $valor_checkoutF ?></td><td><?php echo $hora_checkout ?></td><td><?php echo $nome_func_checkout ?></td><td><?php echo $forma_pgto_checkout ?></td></tr>
    </table>
</div>
<?php } ?>

<div class="box">
    <div style="margin-bottom:8px;"><b>DETALHAMENTO DE CONSUMO</b></div>
    <table style="margin-bottom:10px;">
        <tr><th>VALOR PRODUTOS</th><th>VALOR SERVIÇOS</th><th>TOTAL CONSUMIDO</th></tr>
        <tr><td><?php echo $total_consumoF ?></td><td><?php echo $total_servicosF ?></td><td><?php echo $total_finalF ?></td></tr>
    </table>
    <table>
        <tr>
            <th style="width:24%">DESCRIÇÃO</th>
            <th style="width:10%">VALOR</th>
            <th style="width:9%">DATA</th>
            <th style="width:8%">HORA</th>
            <th style="width:18%">HÓSPEDE</th>
            <th style="width:18%">FUNCIONÁRIO</th>
            <th style="width:13%">STATUS</th>
        </tr>
        <?php echo $linhas_consumo_html; ?>
    </table>
</div>
<!-- FIM DETALHAMENTO DE CONSUMO -->
</body>
</html>
