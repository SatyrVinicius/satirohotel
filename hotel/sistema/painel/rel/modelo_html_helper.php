<?php

if (!function_exists('obter_modelo_relatorio_html')) {
    function obter_modelo_relatorio_html() {
        $padrao = array(
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

        $arquivo = __DIR__ . '/modelo_html_config.json';
        if (!is_file($arquivo)) {
            return $padrao;
        }

        $conteudo = @file_get_contents($arquivo);
        if ($conteudo === false || trim($conteudo) === '') {
            return $padrao;
        }

        $json = json_decode($conteudo, true);
        if (!is_array($json)) {
            return $padrao;
        }

        $cfg = array_merge($padrao, $json);

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cfg['cor_primaria'])) {
            $cfg['cor_primaria'] = $padrao['cor_primaria'];
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cfg['cor_fundo_cabecalho'])) {
            $cfg['cor_fundo_cabecalho'] = $padrao['cor_fundo_cabecalho'];
        }

        if (!preg_match('/^#[0-9a-fA-F]{6}$/', $cfg['cor_subtitulo'])) {
            $cfg['cor_subtitulo'] = $padrao['cor_subtitulo'];
        }

        $fontesPermitidas = array(
            'Arial, Helvetica, sans-serif',
            'Verdana, Geneva, sans-serif',
            'Tahoma, Geneva, sans-serif',
            'Georgia, serif',
            'Times New Roman, Times, serif'
        );

        if (!in_array($cfg['fonte_base'], $fontesPermitidas, true)) {
            $cfg['fonte_base'] = $padrao['fonte_base'];
        }

        $cfg['tamanho_base'] = (int)$cfg['tamanho_base'];
        if ($cfg['tamanho_base'] < 11 || $cfg['tamanho_base'] > 18) {
            $cfg['tamanho_base'] = $padrao['tamanho_base'];
        }

        if (!in_array($cfg['mostrar_logo_cabecalho'], array('Sim', 'Nao'), true)) {
            $cfg['mostrar_logo_cabecalho'] = $padrao['mostrar_logo_cabecalho'];
        }

        if (!is_string($cfg['logo_cabecalho_arquivo'])) {
            $cfg['logo_cabecalho_arquivo'] = '';
        }

        if (!preg_match('/^[a-zA-Z0-9_.-]*$/', $cfg['logo_cabecalho_arquivo'])) {
            $cfg['logo_cabecalho_arquivo'] = '';
        }

        $cfg['nome_sistema_cabecalho'] = trim((string)$cfg['nome_sistema_cabecalho']);
        if ($cfg['nome_sistema_cabecalho'] === '') {
            $cfg['nome_sistema_cabecalho'] = $padrao['nome_sistema_cabecalho'];
        }

        if (!in_array($cfg['mostrar_botao_imprimir'], array('Sim', 'Nao'), true)) {
            $cfg['mostrar_botao_imprimir'] = $padrao['mostrar_botao_imprimir'];
        }

        if (!in_array($cfg['mostrar_marca_dagua'], array('Padrao', 'Sim', 'Nao'), true)) {
            $cfg['mostrar_marca_dagua'] = $padrao['mostrar_marca_dagua'];
        }

        return $cfg;
    }
}
