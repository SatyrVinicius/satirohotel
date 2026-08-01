<?php

function gerar_pdf_reserva($id, $pdo)
{
    $id = (int)$id;
    if ($id <= 0) {
        return false;
    }

    $token_rel = "FKLUY7852";

    ob_start();
    include(__DIR__ . "/reserva.php");
    $html = ob_get_clean();

    if (trim($html) == '' || stripos($html, 'Reserva não encontrada!') !== false) {
        return false;
    }

    $dir = __DIR__ . '/../pdf/reservas';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $arquivo = $dir . '/reserva_' . $id . '.pdf';

    $htmlFile = tempnam(sys_get_temp_dir(), 'reserva_html_');
    if ($htmlFile !== false) {
        $htmlFilePath = $htmlFile . '.html';
        @rename($htmlFile, $htmlFilePath);
        file_put_contents($htmlFilePath, $html);
    }

    $wkhtmltopdf = null;
    $candidatos = array('/usr/local/bin/wkhtmltopdf','/usr/bin/wkhtmltopdf','/bin/wkhtmltopdf','wkhtmltopdf');
    foreach ($candidatos as $bin) {
        if ($bin === 'wkhtmltopdf') {
            $out = array();
            $ret = 1;
            @exec('which wkhtmltopdf 2>/dev/null', $out, $ret);
            if ($ret === 0 && !empty($out[0])) {
                $wkhtmltopdf = trim($out[0]);
                break;
            }
        } elseif (is_file($bin) && is_executable($bin)) {
            $wkhtmltopdf = $bin;
            break;
        }
    }

    if ($wkhtmltopdf) {
        $cmd = escapeshellarg($wkhtmltopdf)
            . ' --quiet --enable-local-file-access --page-size A4 --orientation Portrait --margin-top 10mm --margin-right 8mm --margin-bottom 10mm --margin-left 8mm '
            . escapeshellarg($htmlFilePath) . ' ' . escapeshellarg($arquivo);
        $saida = array();
        $ret = 1;
        @exec($cmd . ' 2>&1', $saida, $ret);
        if (isset($htmlFilePath) && file_exists($htmlFilePath)) {
            @unlink($htmlFilePath);
        }
        if ($ret === 0 && file_exists($arquivo) && filesize($arquivo) > 0) {
            return true;
        }
    }

    if (isset($htmlFilePath) && file_exists($htmlFilePath)) {
        @unlink($htmlFilePath);
    }

    require_once __DIR__ . '/../dompdf/autoload.inc.php';

    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);

    $pdf = new \Dompdf\Dompdf($options);
    $pdf->set_paper('A4', 'portrait');
    $pdf->load_html($html);
    $pdf->render();

    $output = $pdf->output();
    file_put_contents($arquivo, $output);

    return file_exists($arquivo) && filesize($arquivo) > 0;
}
