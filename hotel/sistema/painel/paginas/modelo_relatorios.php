<?php

$pag = 'modelo_relatorios';

$configPadrao = array(
    'nome_modelo' => 'Padrao',
    'nome_sistema_cabecalho' => 'Sátiro Hotel',
    'cor_primaria' => '#1e88e5',
    'cor_fundo_cabecalho' => '#ffffff',
    'cor_subtitulo' => '#555555',
    'fonte_base' => 'Arial, Helvetica, sans-serif',
    'tamanho_base' => 14,
    'mostrar_logo_cabecalho' => 'Sim',
    'logo_cabecalho_arquivo' => '',
    'mostrar_botao_imprimir' => 'Sim',
    'mostrar_marca_dagua' => 'Padrao'
);

$arquivoConfig = __DIR__ . '/../rel/modelo_html_config.json';
$config = $configPadrao;

if (is_file($arquivoConfig)) {
    $json = @json_decode(@file_get_contents($arquivoConfig), true);
    if (is_array($json)) {
        $config = array_merge($configPadrao, $json);
    }
}

$logoPreview = '';
if ($config['logo_cabecalho_arquivo'] != '') {
    $logoPreview = 'img/' . $config['logo_cabecalho_arquivo'];
}

?>

<div class="bs-example widget-shadow" style="padding:15px; margin-top: 15px;">
    <h4 style="margin-bottom: 14px;"><i class="fa fa-paint-brush"></i> Modelo de Relatorios HTML</h4>
    <p style="margin-bottom: 16px; color:#666;">Personalize o visual dos relatorios HTML usados na pagina Filtrar Reservas.</p>

    <form id="form_modelo_html">
        <div class="row">
            <div class="col-md-4">
                <label>Nome do Modelo</label>
                <input type="text" class="form-control" name="nome_modelo" maxlength="40" value="<?php echo htmlspecialchars($config['nome_modelo']) ?>">
            </div>

            <div class="col-md-4">
                <label>Nome do Sistema no Cabeçalho</label>
                <input type="text" class="form-control" name="nome_sistema_cabecalho" maxlength="60" value="<?php echo htmlspecialchars($config['nome_sistema_cabecalho']) ?>">
            </div>

            <div class="col-md-2">
                <label>Cor Primaria</label>
                <input type="color" class="form-control" name="cor_primaria" value="<?php echo htmlspecialchars($config['cor_primaria']) ?>">
            </div>

            <div class="col-md-2">
                <label>Fundo Cabecalho</label>
                <input type="color" class="form-control" name="cor_fundo_cabecalho" value="<?php echo htmlspecialchars($config['cor_fundo_cabecalho']) ?>">
            </div>

            <div class="col-md-2">
                <label>Cor Subtitulo</label>
                <input type="color" class="form-control" name="cor_subtitulo" value="<?php echo htmlspecialchars($config['cor_subtitulo']) ?>">
            </div>

            <div class="col-md-2">
                <label>Tamanho Base</label>
                <input type="number" class="form-control" min="11" max="18" name="tamanho_base" value="<?php echo (int)$config['tamanho_base'] ?>">
            </div>

            <div class="col-md-2">
                <label>Logo no Cabeçalho</label>
                <select class="form-control" name="mostrar_logo_cabecalho">
                    <option value="Sim" <?php if($config['mostrar_logo_cabecalho'] == 'Sim'){ ?>selected<?php } ?>>Mostrar</option>
                    <option value="Nao" <?php if($config['mostrar_logo_cabecalho'] == 'Nao'){ ?>selected<?php } ?>>Ocultar</option>
                </select>
            </div>
        </div>

        <div class="row" style="margin-top: 12px;">
            <div class="col-md-4">
                <label>Fonte Base</label>
                <select class="form-control" name="fonte_base">
                    <option value="Arial, Helvetica, sans-serif" <?php if($config['fonte_base'] == 'Arial, Helvetica, sans-serif'){ ?>selected<?php } ?>>Arial</option>
                    <option value="Verdana, Geneva, sans-serif" <?php if($config['fonte_base'] == 'Verdana, Geneva, sans-serif'){ ?>selected<?php } ?>>Verdana</option>
                    <option value="Tahoma, Geneva, sans-serif" <?php if($config['fonte_base'] == 'Tahoma, Geneva, sans-serif'){ ?>selected<?php } ?>>Tahoma</option>
                    <option value="Georgia, serif" <?php if($config['fonte_base'] == 'Georgia, serif'){ ?>selected<?php } ?>>Georgia</option>
                    <option value="Times New Roman, Times, serif" <?php if($config['fonte_base'] == 'Times New Roman, Times, serif'){ ?>selected<?php } ?>>Times New Roman</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Botao Imprimir</label>
                <select class="form-control" name="mostrar_botao_imprimir">
                    <option value="Sim" <?php if($config['mostrar_botao_imprimir'] == 'Sim'){ ?>selected<?php } ?>>Mostrar</option>
                    <option value="Nao" <?php if($config['mostrar_botao_imprimir'] == 'Nao'){ ?>selected<?php } ?>>Ocultar</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Marca Dagua</label>
                <select class="form-control" name="mostrar_marca_dagua">
                    <option value="Padrao" <?php if($config['mostrar_marca_dagua'] == 'Padrao'){ ?>selected<?php } ?>>Seguir configuracao do sistema</option>
                    <option value="Sim" <?php if($config['mostrar_marca_dagua'] == 'Sim'){ ?>selected<?php } ?>>Sempre mostrar</option>
                    <option value="Nao" <?php if($config['mostrar_marca_dagua'] == 'Nao'){ ?>selected<?php } ?>>Sempre ocultar</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Logo Pequena (PNG/JPG/WEBP)</label>
                <input type="file" class="form-control" name="logo_cabecalho" accept=".png,.jpg,.jpeg,.webp">
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Salvar Modelo</button>
            </div>
        </div>

        <?php if($logoPreview != ''){ ?>
        <div class="row" style="margin-top: 10px;">
            <div class="col-md-4">
                <small>Logo atual:</small><br>
                <img src="<?php echo $logoPreview ?>" style="max-height:42px; max-width:200px; border:1px solid #ddd; padding:4px; background:#fff;">
            </div>
        </div>
        <?php } ?>

        <small><div id="mensagem_modelo" align="center" style="margin-top: 10px;"></div></small>
    </form>
</div>

<script type="text/javascript">var pag = "<?=$pag?>"</script>

<script>
    $("#form_modelo_html").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: 'paginas/' + pag + '/salvar.php',
            type: 'POST',
            data: formData,
            success: function (mensagem) {
                $('#mensagem_modelo').removeClass();
                if (mensagem.trim() == 'Salvo com Sucesso') {
                    $('#mensagem_modelo').addClass('text-success');
                    $('#mensagem_modelo').text(mensagem);
                } else {
                    $('#mensagem_modelo').addClass('text-danger');
                    $('#mensagem_modelo').text(mensagem);
                }
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>
