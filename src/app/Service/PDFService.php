<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFService
{

    public function gerar($dados, $view, $nomeArquivo) //Nome arquivp = nome final
    {
        $options = new Options(); //objeto de configurações DOM
        $options->set('defaultFont', 'DejaVu Sans'); //Seta a fonte pq ela suporte acentos
        $options->set('isRemoteEnabled', true); //Ativa a função de poder pegar imagens remotas e colocar no pdf
        $domPdf = new DomPdf($options);

        ob_start(); //prepara ambiente para pegar em memória o Html
        extract($dados); //extrai os dados do array associativo e passa para variáveis
        require $view; //carrega a view
        $html = ob_get_clean(); //para a memoria para nao capturar mais o html, pq ele ja foi feito

        $domPdf->loadHtml($html, 'UTF-8'); //carrega o html capturado
        $domPdf->setPaper('A4', 'portrait'); //orientações de página no modo retrato
        $domPdf->render(); //converte o html para o pdf
        $domPdf->stream($nomeArquivo, [
            'Attachment' => false
        ]); //download, false = abre no navegador, true força a baixar
        exit;
    }
}
