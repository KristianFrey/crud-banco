<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class criarExcel
{
    public function __construct()
    {
        // 1. CARGA DA INTERFACE PSR (Obrigatória para o PhpSpreadsheet funcionar)
        $psrPath = 'C:/laragon/www/crud-banco/lib/Psr/SimpleCache/CacheInterface.php';
        if (file_exists($psrPath)) {
            require_once $psrPath;
        }

        // 2. MAPA DE CAMINHOS - Ajustado para a sua estrutura real
        spl_autoload_register(function ($class) {
            $map = [
                // Caminho da lib principal
                'PhpOffice\\PhpSpreadsheet\\' => 'C:/laragon/www/crud-banco/lib/PhpSpreadsheet-master/PhpSpreadsheet-master/src/PhpSpreadsheet/',

                // Caminho que confirmamos agora (com /src/ no final)
                'Composer\\Pcre\\'           => 'C:/laragon/www/crud-banco/lib/pcre-main/src/',

                // Provável próxima dependência que o Excel vai pedir
                'Complex\\'                  => 'C:/laragon/www/crud-banco/lib/PHPComplex-master/src/',
            ];

            foreach ($map as $prefix => $base_dir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) continue;

                $relative_class = substr($class, $len);
                // Transforma \ em / para o Windows não se perder
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        });
    }
    // ... resto do seu código (formatarExcel, baixarArquivo, gerarExcel)

    public function formatarExcel(array $clientes)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Cabeçalhos
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'NOME');
        $sheet->setCellValue('C1', 'TELEFONE');
        $sheet->setCellValue('D1', 'EMAIL');

        $linha = 2;
        foreach ($clientes as $cliente) {
            $sheet->setCellValue('A' . $linha, $cliente['id']);
            $sheet->setCellValue('B' . $linha, $cliente['nome']);
            $sheet->setCellValue('C' . $linha, $cliente['telefone']);
            $sheet->setCellValue('D' . $linha, $cliente['email']);
            $linha++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    public function baixarArquivo(Spreadsheet $spreadsheet)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="clientes.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');

        exit;
    }

    public function gerarExcel($clientes)
    {
        $excel = $this->formatarExcel($clientes);
        $this->baixarArquivo($excel);
    }
}
