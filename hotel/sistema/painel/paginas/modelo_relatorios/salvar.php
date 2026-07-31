<?php

$nome_modelo = isset($_POST['nome_modelo']) ? trim($_POST['nome_modelo']) : 'Padrao';
$nome_sistema_cabecalho = isset($_POST['nome_sistema_cabecalho']) ? trim($_POST['nome_sistema_cabecalho']) : 'Sátiro Hotel';
$cor_primaria = isset($_POST['cor_primaria']) ? trim($_POST['cor_primaria']) : '#1e88e5';
$cor_fundo_cabecalho = isset($_POST['cor_fundo_cabecalho']) ? trim($_POST['cor_fundo_cabecalho']) : '#ffffff';
$cor_subtitulo = isset($_POST['cor_subtitulo']) ? trim($_POST['cor_subtitulo']) : '#555555';
$fonte_base = isset($_POST['fonte_base']) ? trim($_POST['fonte_base']) : 'Arial, Helvetica, sans-serif';
$tamanho_base = isset($_POST['tamanho_base']) ? (int)$_POST['tamanho_base'] : 14;
$mostrar_logo_cabecalho = isset($_POST['mostrar_logo_cabecalho']) ? trim($_POST['mostrar_logo_cabecalho']) : 'Sim';
$mostrar_botao_imprimir = isset($_POST['mostrar_botao_imprimir']) ? trim($_POST['mostrar_botao_imprimir']) : 'Sim';
$mostrar_marca_dagua = isset($_POST['mostrar_marca_dagua']) ? trim($_POST['mostrar_marca_dagua']) : 'Padrao';

$arquivoConfig = __DIR__ . '/../../rel/modelo_html_config.json';
$configAtual = array();
if (is_file($arquivoConfig)) {
    $jsonAtual = @json_decode(@file_get_contents($arquivoConfig), true);
    if (is_array($jsonAtual)) {
        $configAtual = $jsonAtual;
    }
}

$logo_cabecalho_arquivo = isset($configAtual['logo_cabecalho_arquivo']) ? $configAtual['logo_cabecalho_arquivo'] : '';

if ($nome_modelo == '') {
    $nome_modelo = 'Padrao';
}

if ($nome_sistema_cabecalho == '') {
    $nome_sistema_cabecalho = 'Sátiro Hotel';
}

if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cor_primaria)) {
    echo 'Cor primaria invalida';
    exit();
}

if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cor_fundo_cabecalho)) {
    echo 'Cor de fundo invalida';
    exit();
}

if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cor_subtitulo)) {
    echo 'Cor de subtitulo invalida';
    exit();
}

$fontesPermitidas = array(
    'Arial, Helvetica, sans-serif',
    'Verdana, Geneva, sans-serif',
    'Tahoma, Geneva, sans-serif',
    'Georgia, serif',
    'Times New Roman, Times, serif'
);

if (!in_array($fonte_base, $fontesPermitidas, true)) {
    echo 'Fonte invalida';
    exit();
}

if ($tamanho_base < 11 || $tamanho_base > 18) {
    echo 'Tamanho base invalido';
    exit();
}

if (!in_array($mostrar_logo_cabecalho, array('Sim', 'Nao'), true)) {
    echo 'Opcao de logo invalida';
    exit();
}

if (!in_array($mostrar_botao_imprimir, array('Sim', 'Nao'), true)) {
    echo 'Opcao de botao invalida';
    exit();
}

if (!in_array($mostrar_marca_dagua, array('Padrao', 'Sim', 'Nao'), true)) {
    echo 'Opcao de marca d\'agua invalida';
    exit();
}

if (isset($_FILES['logo_cabecalho']) && isset($_FILES['logo_cabecalho']['name']) && $_FILES['logo_cabecalho']['name'] != '') {
    $nomeArquivo = $_FILES['logo_cabecalho']['name'];
    $tmpArquivo = $_FILES['logo_cabecalho']['tmp_name'];
    $ext = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));
    $permitidas = array('png', 'jpg', 'jpeg', 'webp');

    if (!in_array($ext, $permitidas, true)) {
        echo 'Extensao da logo invalida';
        exit();
    }

    $nomeFinal = 'modelo_relatorios_logo.' . $ext;
    $destino = __DIR__ . '/../../img/' . $nomeFinal;

    if (!@move_uploaded_file($tmpArquivo, $destino)) {
        echo 'Nao foi possivel salvar a logo';
        exit();
    }

    $logo_cabecalho_arquivo = $nomeFinal;
}

$config = array(
    'nome_modelo' => $nome_modelo,
    'nome_sistema_cabecalho' => $nome_sistema_cabecalho,
    'cor_primaria' => $cor_primaria,
    'cor_fundo_cabecalho' => $cor_fundo_cabecalho,
    'cor_subtitulo' => $cor_subtitulo,
    'fonte_base' => $fonte_base,
    'tamanho_base' => $tamanho_base,
    'mostrar_logo_cabecalho' => $mostrar_logo_cabecalho,
    'logo_cabecalho_arquivo' => $logo_cabecalho_arquivo,
    'mostrar_botao_imprimir' => $mostrar_botao_imprimir,
    'mostrar_marca_dagua' => $mostrar_marca_dagua
);

if (@file_put_contents($arquivoConfig, json_encode($config, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
    echo 'Nao foi possivel salvar o modelo';
    exit();
}

echo 'Salvo com Sucesso';
