<?php
session_start();

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

$n_ingresso = $_POST['n_ingresso'];
$id = isset($_POST['id']) ? $_POST['id'] : '';
$secretaria = $_POST['secretaria'];

//Variavel de conexão ao banco de dados
$conn = conexao_pdo();

$secretariaSorteada = $_POST['secretaria'];
// Verifica o total de ingressos que irão participar do sorteio 
$sql = ("SELECT COUNT(*) AS total FROM lista WHERE secretaria = :secretaria AND sorteado = 0");
$stm = $conn->prepare($sql);
$stm->bindParam(':secretaria', $secretariaSorteada);
$stm->execute();
$resultado = $stm->fetch(PDO::FETCH_OBJ);
$numParticipantes = $resultado->total;


//obtem as informações para o sorteio 
$sql2 = ("SELECT * FROM lista WHERE secretaria = :secretaria AND sorteado = 0");
$stm2 = $conn->prepare($sql2);
$stm2->bindParam(':secretaria', $secretariaSorteada);
$stm2->execute();
$participantes = $stm2->fetchALL(PDO::FETCH_ASSOC);

// Refazendo o Sorteio

# Sorteio
for ($i = 1; $i < 2; $i++) {

    // GANHADOR NOVO
    $ganhadorNovo = $participantes[rand(0, $numParticipantes - 1)];
    
    // DADOS DO GANHADOR ANTIGO
    $sql3 = ("SELECT * FROM sorteados WHERE n_ingresso = :n_ingresso");
    $stm3 = $conn->prepare($sql3);
    $stm3->bindParam(':n_ingresso', $n_ingresso);
    $stm3->execute();
    $ganhadorAntigo = $stm3->fetch(PDO::FETCH_OBJ);

    if ($verificacao > 0) {
        --$i;
    }

}

//Atualiza o sorteado no banco de dados

$sql4 = "UPDATE lista SET sorteado = 1 WHERE n_ingresso = :n_ingresso";
$stmt4 = $conn->prepare($sql4);
$stmt4->bindParam(':n_ingresso', $ganhadorNovo['n_ingresso']);
$stmt4->execute();
$resultado4 = $stmt4->fetch(PDO::FETCH_ASSOC);


$cmd = "UPDATE sorteados SET secretaria = :secretaria, n_ingresso = :n_ingresso, nome = :nome, sobrenome = :sobrenome, cpf = :cpf, telefone = :telefone WHERE id = :id";
$stmt = $conn->prepare($cmd);
$stmt->bindValue(':secretaria', $ganhadorNovo["secretaria"]);
$stmt->bindValue(':n_ingresso', $ganhadorNovo["n_ingresso"]);
$stmt->bindValue(':nome', $ganhadorNovo["nome"]);
$stmt->bindValue(':sobrenome', $ganhadorNovo["sobrenome"]);
$stmt->bindValue(':cpf', $ganhadorNovo["cpf"]);
$stmt->bindValue(':telefone', $ganhadorNovo["telefone"]);
$stmt->bindParam(':id', $id);
$res = $stmt->execute();


if ($res) {
    header("location: sorteados.php");
} else {
    echo "Erro ao atualizar o sorteio";
    print_r($stm->errorInfo());
}
