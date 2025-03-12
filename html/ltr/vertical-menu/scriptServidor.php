<?php
session_start();

require '../../../../vendor/autoload.php';

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

// Caminho para o arquivo Excel
$excelFile = 'servidores.xlsx';

// Carregar o arquivo Excel
$spreadsheet = IOFactory::load($excelFile);

// Obtenha a primeira folha
$sheet = $spreadsheet->getActiveSheet();

// Prepara a consulta SQL para inserção
$sql = "INSERT INTO lista (n_ingresso, nome, sobrenome, n_pedido, email, telefone, cpf, secretaria) VALUES (:n_ingresso, :nome, :sobrenome, :n_pedido, :email, :telefone, :cpf, :secretaria)";
$stmt = $conn->prepare($sql);

$primeiraLinha = true;
// Loop através das linhas do Excel
foreach ($sheet->getRowIterator() as $row) {
    if($primeiraLinha) {
        $primeiraLinha = false;
        continue;
    }
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Itera sobre todas as células, mesmo as vazias

    // Cria um array para armazenar os dados da linha
    $data = [];

    foreach ($cellIterator as $cell) {
        $data[] = $cell->getValue(); // Armazena o valor da célula no array
    }

    // Verifique se a linha contém dados (você pode adicionar mais condições conforme necessário)
    if (!empty($data[1])) { // Exemplo: verifica se n_ingresso não está vazio
        // Mapeia os valores para variáveis
        $n_ingresso = $data[1]; // n_ingresso
        $nome = $data[2]; // nome
        $sobrenome = $data[3]; // sobrenome
        $n_pedido = $data[7]; // n_pedido
        $email = $data[8]; // email
        $telefone = $data[21]; // telefone
        $cpf = $data[22]; // cpf
        $secretaria = $data[23]; // secretaria

        // Vincula os parâmetros e executa a consulta
        $stmt->bindParam(':n_ingresso', $n_ingresso);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':sobrenome', $sobrenome);
        $stmt->bindParam(':n_pedido', $n_pedido);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':secretaria', $secretaria);

        // Executa a inserção
        $stmt->execute();
    }
}

echo "Dados importados com sucesso!";