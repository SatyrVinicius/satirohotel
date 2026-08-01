<?php

function wkhtmltopdf_caminho_binario(){
    $candidatos = array();

    $envBin = getenv('WKHTMLTOPDF_BIN');
    if ($envBin) {
        $candidatos[] = $envBin;
    }

    if (DIRECTORY_SEPARATOR === '\\') {
        $candidatos[] = 'C:\\Program Files\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
        $candidatos[] = 'C:\\Program Files (x86)\\wkhtmltopdf\\bin\\wkhtmltopdf.exe';
        $candidatos[] = 'wkhtmltopdf';
    } else {
        $candidatos[] = '/usr/local/bin/wkhtmltopdf';
        $candidatos[] = '/usr/bin/wkhtmltopdf';
        $candidatos[] = '/bin/wkhtmltopdf';
        $candidatos[] = 'wkhtmltopdf';
    }

    foreach ($candidatos as $bin) {
        if ($bin === 'wkhtmltopdf') {
            $out = array();
            $ret = 1;
            if (DIRECTORY_SEPARATOR === '\\') {
                @exec('where wkhtmltopdf 2>NUL', $out, $ret);
            } else {
                @exec('which wkhtmltopdf 2>/dev/null', $out, $ret);
            }
            if ($ret === 0 && !empty($out[0])) {
                return trim($out[0]);
            }
            continue;
        }

        if (is_file($bin) && is_executable($bin)) {
            return $bin;
        }
    }

    return null;
}

function gerar_pdf_por_html($html, $arquivoSaida, $orientacao = 'portrait'){
    $dirSaida = dirname($arquivoSaida);
    if (!is_dir($dirSaida)) {
        @mkdir($dirSaida, 0777, true);
    }

    $tmpHtml = tempnam(sys_get_temp_dir(), 'rel_');
    if ($tmpHtml === false) {
        return array('ok' => false, 'engine' => 'none', 'erro' => 'Nao foi possivel criar arquivo temporario');
    }

    $tmpHtmlPath = $tmpHtml . '.html';
    @rename($tmpHtml, $tmpHtmlPath);
    file_put_contents($tmpHtmlPath, $html);

    $wkhtmltopdf = wkhtmltopdf_caminho_binario();
    if ($wkhtmltopdf) {
        $orientacaoTxt = strtolower($orientacao) === 'landscape' ? 'Landscape' : 'Portrait';
        $cmd = escapeshellarg($wkhtmltopdf)
            . ' --quiet --enable-local-file-access --page-size A4 --orientation ' . $orientacaoTxt
            . ' --margin-top 10mm --margin-right 8mm --margin-bottom 10mm --margin-left 8mm '
            . escapeshellarg($tmpHtmlPath) . ' ' . escapeshellarg($arquivoSaida);

        $saida = array();
        $ret = 1;
        @exec($cmd . ' 2>&1', $saida, $ret);

        @unlink($tmpHtmlPath);

        if ($ret === 0 && is_file($arquivoSaida) && filesize($arquivoSaida) > 0) {
            return array('ok' => true, 'engine' => 'wkhtmltopdf');
        }
    } else {
        @unlink($tmpHtmlPath);
    }

    require_once __DIR__ . '/../dompdf/autoload.inc.php';

    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', false);
    $options->set('isHtml5ParserEnabled', false);
    $options->set('dpi', 72);

    $pdf = new \Dompdf\Dompdf($options);
    $pdf->set_paper('A4', strtolower($orientacao) === 'landscape' ? 'landscape' : 'portrait');
    $pdf->load_html($html);
    $pdf->render();

    file_put_contents($arquivoSaida, $pdf->output());

    if (is_file($arquivoSaida) && filesize($arquivoSaida) > 0) {
        return array('ok' => true, 'engine' => 'dompdf');
    }

    return array('ok' => false, 'engine' => 'none', 'erro' => 'Falha ao gerar PDF');
}
