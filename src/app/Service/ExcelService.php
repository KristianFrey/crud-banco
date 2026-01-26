<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExcelService
{
    public function gerar(array $dados, string $nomeArquivo): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalho
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Telefone');

        // Dados
        $linha = 2;
        foreach ($dados as $item) {
            $sheet->setCellValue('A' . $linha, $item['id']);
            $sheet->setCellValue('B' . $linha, $item['nome']);
            $sheet->setCellValue('C' . $linha, $item['email']);
            $sheet->setCellValue('D' . $linha, $item['telefone']);
            $linha++;
        }

        // Headers para download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$nomeArquivo\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
