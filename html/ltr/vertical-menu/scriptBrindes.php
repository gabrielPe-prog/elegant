<?php
session_start();

//require '../../../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function conexao_pdo()
{

    $user = 'pmc_clube';
    $pass = 'fRw1x5X31V92uw6r';


    try {
        $conexao = new PDO('mysql:host=localhost; dbname=participantes', $user, $pass);
        return $conexao;
    } catch (PDOException $e) {
        echo 'Erro ao conectar com o MySQL: ' . $e->getMessage();
    }

}

// Variável de conexão ao banco de dados
$conn = conexao_pdo();

// arquivo de brindes
$brindeFile = 'brindes.xlsx';

// Carregar o arquivo
$spreadsheet = IOFactory::load($brindeFile);

// Obtém a primeira folha
$sheet = $spreadsheet->getActiveSheet();

// Prepara SQL para inserção
$sql = "INSERT INTO brindes (descricao) VALUES (:descricao)";
$stmt = $conn->prepare($sql);

// Itera as linhas do excel
foreach ($sheet->getRowIterator() as $row) {

    $cell = $sheet->getCell('A' . $row->getRowIndex());
    $descricao = $cell->getValue();
    if ($descricao !== null && $descricao !== '') {
        $stmt->bindParam(':descricao', $descricao);
        $stmt->execute();
    } else {
        break;
    }
}

echo "Dados importados com sucesso!";