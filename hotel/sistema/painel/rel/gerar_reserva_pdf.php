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

    require_once __DIR__ . '/../dompdf/autoload.inc.php';

    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);

    $pdf = new \Dompdf\Dompdf($options);
    $pdf->set_paper('A4', 'portrait');
    $pdf->load_html($html);
    $pdf->render();

    $output = $pdf->output();

    $dir = __DIR__ . '/../pdf/reservas';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $arquivo = $dir . '/reserva_' . $id . '.pdf';
    file_put_contents($arquivo, $output);

    return true;
}
