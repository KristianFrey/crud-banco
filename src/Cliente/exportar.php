<?php
// C:\laragon\www\crud-banco\src\Cliente\exportar.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

while (ob_get_level()) {
    ob_end_clean();
}

require_once __DIR__ . '/../../Infraestrutura/conexaoBanco.php';
require_once __DIR__ . '/../../Infraestrutura/Repository/PdoClienteRepository.php';
require_once __DIR__ . '/Cliente.php';

// ====================================================
// IMPLEMENTAÇÃO COMPLETA DAS DEPENDÊNCIAS FALTANTES
// ====================================================

// 1. Implementação COMPLETA do Composer\Pcre\Preg
if (!class_exists('Composer\Pcre\Preg')) {
    class ComposerPcrePreg
    {
        public static function isMatch($pattern, $subject, $flags = 0)
        {
            $result = @preg_match($pattern, $subject);
            return $result === 1;
        }

        public static function match($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
        {
            return preg_match($pattern, $subject, $matches, $flags, $offset);
        }

        public static function matchAll($pattern, $subject, &$matches = null, $flags = 0, $offset = 0)
        {
            return preg_match_all($pattern, $subject, $matches, $flags, $offset);
        }

        public static function replace($pattern, $replacement, $subject, $limit = -1, &$count = null)
        {
            return preg_replace($pattern, $replacement, $subject, $limit, $count);
        }

        public static function replaceCallback($pattern, $callback, $subject, $limit = -1, &$count = null)
        {
            return preg_replace_callback($pattern, $callback, $subject, $limit, $count);
        }

        public static function split($pattern, $subject, $limit = -1, $flags = 0)
        {
            return preg_split($pattern, $subject, $limit, $flags);
        }

        public static function grep($pattern, $input, $flags = 0)
        {
            return preg_grep($pattern, $input, $flags);
        }
    }

    class_alias('ComposerPcrePreg', 'Composer\Pcre\Preg');

    // Também cria a classe Regex se necessário
    if (!class_exists('Composer\Pcre\Regex')) {
        class ComposerPcreRegex
        {
            public static function isMatch($pattern, $subject, $flags = 0)
            {
                return ComposerPcrePreg::isMatch($pattern, $subject, $flags);
            }

            public static function match($pattern, $subject, $flags = 0, $offset = 0)
            {
                $matches = [];
                $result = ComposerPcrePreg::match($pattern, $subject, $matches, $flags, $offset);
                return $result === 1 ? $matches : null;
            }
        }
        class_alias('ComposerPcreRegex', 'Composer\Pcre\Regex');
    }
}

// 2. Implementação do PSR CacheInterface
if (!interface_exists('Psr\SimpleCache\CacheInterface')) {
    interface PsrSimpleCacheCacheInterface
    {
        public function get($key, $default = null);
        public function set($key, $value, $ttl = null);
        public function delete($key);
        public function clear();
        public function getMultiple($keys, $default = null);
        public function setMultiple($values, $ttl = null);
        public function deleteMultiple($keys);
        public function has($key);
    }
    class_alias('PsrSimpleCacheCacheInterface', 'Psr\SimpleCache\CacheInterface');
}

// 3. Implementação básica do Matrix se necessário
if (!class_exists('Matrix\Matrix')) {
    class MatrixMatrix
    {
        // Implementação básica apenas para evitar erros
        public function __construct(array $matrix = []) {}
    }
    class_alias('MatrixMatrix', 'Matrix\Matrix');
}

// 4. Implementação básica do Complex se necessário
if (!class_exists('Complex\Complex')) {
    class ComplexComplex
    {
        // Implementação básica apenas para evitar erros
        public function __construct($real = 0.0, $imaginary = 0.0) {}
    }
    class_alias('ComplexComplex', 'Complex\Complex');
}

// 5. Função para carregar classes do PhpSpreadsheet
function carregarClassePhpSpreadsheet($className)
{
    $basePath = __DIR__ . '/../../lib/PhpSpreadsheet-master/PhpSpreadsheet-master/src/PhpSpreadsheet/';

    if (strpos($className, 'PhpOffice\\PhpSpreadsheet\\') === 0) {
        $relativePath = str_replace('PhpOffice\\PhpSpreadsheet\\', '', $className);
        $filePath = $basePath . str_replace('\\', '/', $relativePath) . '.php';

        if (file_exists($filePath)) {
            require_once $filePath;
            return true;
        }
    }
    return false;
}

// 6. Registra o autoloader
spl_autoload_register('carregarClassePhpSpreadsheet');

// 7. Carrega as classes principais EM ORDEM CORRETA
$classesNecessarias = [
    // Classes CORE primeiro
    'PhpOffice\PhpSpreadsheet\Cell\Coordinate',
    'PhpOffice\PhpSpreadsheet\Cell\Cell',
    'PhpOffice\PhpSpreadsheet\Style\Supervisor',
    'PhpOffice\PhpSpreadsheet\Style\Style',
    'PhpOffice\PhpSpreadsheet\Worksheet\Worksheet',
    'PhpOffice\PhpSpreadsheet\Spreadsheet',
    'PhpOffice\PhpSpreadsheet\Writer\Xlsx',
];

foreach ($classesNecessarias as $classe) {
    if (!class_exists($classe) && !interface_exists($classe)) {
        carregarClassePhpSpreadsheet($classe);
    }
}

// 8. Namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// ====================================================
// LÓGICA PRINCIPAL - SIMPLIFICADA
// ====================================================

try {
    // Conecta e busca dados
    $pdo = ConexaoBanco::conectarBanco();
    $repository = new PdoClienteRepository();
    $modelCliente = new Cliente($repository);
    $dados = $modelCliente->repository->buscaTodosClientes();

    if (empty($dados)) {
        die("Nenhum dado encontrado para exportar.");
    }

    // Cria a planilha - método SIMPLES para evitar problemas
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Método ALTERNATIVO: Preenche dados diretamente no array
    $excelData = [
        ['ID', 'NOME', 'TELEFONE', 'EMAIL']
    ];

    foreach ($dados as $cliente) {
        $excelData[] = [
            $cliente['id'] ?? '',
            $cliente['nome'] ?? '',
            $cliente['telefone'] ?? '',
            $cliente['email'] ?? ''
        ];
    }

    // Preenche a planilha de forma mais simples
    $linha = 1;
    foreach ($excelData as $rowData) {
        $coluna = 'A';
        foreach ($rowData as $cellValue) {
            // Usa método direto sem validações complexas
            $sheet->getCell($coluna . $linha)->setValue($cellValue);
            $coluna++;
        }
        $linha++;
    }

    // Headers para download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="clientes.xlsx"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    // Salva o arquivo
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
} catch (Exception $e) {
    die("Erro ao exportar para Excel: " . $e->getMessage());
}
