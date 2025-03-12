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

// Variável de conexão ao banco de dados
$conn = conexao_pdo();

$sqlSecretarias = "SELECT DISTINCT secretaria FROM lista";
$resultadoSecretarias = $conn->prepare($sqlSecretarias);
$resultadoSecretarias->execute();
$secretarias = $resultadoSecretarias->fetchALL(PDO::FETCH_ASSOC);

shuffle($secretarias);

foreach ($secretarias as $secretaria) {
    $sql = "SELECT * FROM lista WHERE secretaria = :secretaria ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':secretaria', $secretaria['secretaria']);
    $stmt->execute();
    $resultado[] = $stmt->fetch(PDO::FETCH_ASSOC);
}

foreach ($resultado as $pessoa) {
    $sql = "INSERT INTO sorteados (n_ingresso, nome, sobrenome, cpf, telefone, secretaria, sorteado) 
            VALUES (:n_ingresso, :nome, :sobrenome, :cpf, :telefone, :secretaria, :sorteado)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':n_ingresso', $pessoa["n_ingresso"]);
    $stmt->bindValue(':nome', $pessoa["nome"]);
    $stmt->bindValue(':sobrenome', $pessoa["sobrenome"]);
    $stmt->bindValue(':cpf', $pessoa["cpf"]);
    $stmt->bindValue(':telefone', $pessoa["telefone"]);
    $stmt->bindValue(':secretaria', $pessoa["secretaria"]);
    $stmt->bindValue(":sorteado", 1);
    $stmt->execute();

    // Atualiza o campo 'sorteado' na tabela 'lista' para evitar sorteio futuro
    $sqlUpdate = "UPDATE lista SET sorteado = 1 WHERE n_ingresso = :n_ingresso";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bindValue(':n_ingresso', $pessoa["n_ingresso"]);
    $stmtUpdate->execute();
}

// var_dump($sorteado);
// die();



header('location: sorteados.php');
exit();


// // Verifica se a secretaria foi selecionada
// if (isset($_POST['secretaria']) && $_POST['secretaria'] != 'todas') {
//     $secretariaEscolhida = $_POST['secretaria'];
// } elseif ($_POST['secretaria'] == 'todas') {
//     $secretariaEscolhida = null;
// }

// // Verifica se a quantidade foi fornecida
// if (isset($_POST['quantidade'])) {
//     $quantidade = $_POST['quantidade'];
// } else {
//     $quantidade = 0;
// }

// // Contar o número de participantes que ainda não foram sorteados
// if (!$secretariaEscolhida) {
//     $sql = "SELECT COUNT(*) AS total FROM lista WHERE sorteado = 0"; // Adiciona o filtro sorteado
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $resultado = $stmt->fetch(PDO::FETCH_OBJ);
//     $numParticipantes = $resultado->total;
// } else {
//     $sql = "SELECT COUNT(*) AS total FROM lista WHERE secretaria = :secretaria AND sorteado = 0"; // Adiciona o filtro sorteado
//     $stmt = $conn->prepare($sql);
//     $stmt->bindParam(':secretaria', $secretariaEscolhida);
//     $stmt->execute();
//     $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
//     $numParticipantes = $resultado['total'];
// }

// // Selecionar os participantes que ainda não foram sorteados
// if (!$secretariaEscolhida) {
//     $sql2 = "SELECT n_ingresso, nome, sobrenome, cpf, telefone, secretaria FROM lista WHERE sorteado = 0"; // Adiciona o filtro sorteado
//     $stmt2 = $conn->prepare($sql2);
//     $stmt2->execute();
//     $participantes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
// } else {
//     $sql2 = "SELECT n_ingresso, nome, sobrenome, cpf, telefone, secretaria FROM lista WHERE secretaria = :secretaria AND sorteado = 0"; // Adiciona o filtro sorteado
//     $stmt2 = $conn->prepare($sql2);
//     $stmt2->bindParam(':secretaria', $secretariaEscolhida);
//     $stmt2->execute();
//     $participantes = $stmt2->fetchAll(PDO::FETCH_ASSOC);
// }

// // Sortear os participantes
// $sorteado = [];
// for ($i = 0; $i < $quantidade && !empty($participantes); $i++) {
//     $indexSorteado = rand(0, sizeof($participantes) - 1);
//     $sorteado[$i] = $participantes[$indexSorteado];
//     array_splice($participantes, $indexSorteado, 1);
// }

// foreach ($sorteado as $pessoa) {
//     $sql = "INSERT INTO sorteados (n_ingresso, nome, sobrenome, cpf, telefone, secretaria, sorteado) 
//             VALUES (:n_ingresso, :nome, :sobrenome, :cpf, :telefone, :secretaria, :sorteado)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bindValue(':n_ingresso', $pessoa["n_ingresso"]);
//     $stmt->bindValue(':nome', $pessoa["nome"]);
//     $stmt->bindValue(':sobrenome', $pessoa["sobrenome"]);
//     $stmt->bindValue(':cpf', $pessoa["cpf"]);
//     $stmt->bindValue(':telefone', $pessoa["telefone"]);
//     $stmt->bindValue(':secretaria', $pessoa["secretaria"]);
//     $stmt->bindValue(":sorteado", 1);
//     $stmt->execute();

//     // Atualiza o campo 'sorteado' na tabela 'lista' para evitar sorteio futuro
//     $sqlUpdate = "UPDATE lista SET sorteado = 1 WHERE n_ingresso = :n_ingresso";
//     $stmtUpdate = $conn->prepare($sqlUpdate);
//     $stmtUpdate->bindValue(':n_ingresso', $pessoa["n_ingresso"]);
//     $stmtUpdate->execute();
// }

// //Analisa se o sorteio já foi realizado; 
// $cmd = ('SELECT COUNT(*) AS total FROM sorteados');
// $stmt = $conn->prepare( $cmd );
// $stmt->execute();   
// $result = $stmt->fetch( PDO::FETCH_OBJ);


// if($result->total > 0) {
//     header('location: sorteados.php');
//     exit();
// }



// // Verifica o total de ingressos que irão participar do sorteio 
// $sql = ("SELECT COUNT(*) AS total FROM lista");
// $stm = $conn->prepare($sql);
// $stm->execute();
// $resultado = $stm->fetch(PDO::FETCH_OBJ);
// $numParticipantes = $resultado->total;


// //obtem as informações para o sorteio 
// $sql2 = ("SELECT n_ingresso, nome, sobrenome, cpf, telefone FROM lista");
// $stm2 = $conn->prepare($sql2);
// $stm2->execute();
// $participantes = $stm2->fetchALL(PDO::FETCH_ASSOC);



// // Sorteando
// # Primeiro ganhador
// $sorteado[1] = $participantes[rand(0,$numParticipantes - 1)];

// # Segundo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[2] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[2] == $sorteado[1]) {
//         --$i;
//     }
// }
// # Terceiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[3] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[3] == $sorteado[1] || $sorteado[3] == $sorteado[2]) {
//         --$i;
//     }
// }
// # Quarto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[4] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[4] == $sorteado[1] || $sorteado[4] == $sorteado[2] || $sorteado[4] == $sorteado[3]) {
//         --$i;
//     }
// }
// # Quinto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[5] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[5] == $sorteado[1] || $sorteado[5] == $sorteado[2] || $sorteado[5] == $sorteado[3] || $sorteado[5] == $sorteado[4]) {
//         --$i;
//     }
// }
// # Sexto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[6] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[6] == $sorteado[1] || $sorteado[6] == $sorteado[2] || $sorteado[6] == $sorteado[3] || $sorteado[6] == $sorteado[4] || $sorteado[6] == $sorteado[5]) {
//         --$i;
//     }
// }
// # Setimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[7] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[7] == $sorteado[1] || $sorteado[7] == $sorteado[2] || $sorteado[7] == $sorteado[3] || $sorteado[7] == $sorteado[4] || $sorteado[7] == $sorteado[5] || $sorteado[7] == $sorteado[6]) {
//         --$i;
//     }
// }
// # Oitavo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[8] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[8] == $sorteado[1] || $sorteado[8] == $sorteado[2] || $sorteado[8] == $sorteado[3] || $sorteado[8] == $sorteado[4] || $sorteado[8] == $sorteado[5] || $sorteado[8] == $sorteado[6] || $sorteado[8] == $sorteado[7] ) {
//         --$i;
//     }
// }
// # Nono ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[9] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[9] == $sorteado[1] || $sorteado[9] == $sorteado[2] || $sorteado[9] == $sorteado[3] || $sorteado[9] == $sorteado[4] || $sorteado[9] == $sorteado[5] || $sorteado[9] == $sorteado[6] || $sorteado[9] == $sorteado[7] || $sorteado[9] == $sorteado[8] ) {
//         --$i;
//     }
// }
// # Decimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[10] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[10] == $sorteado[1] || $sorteado[10] == $sorteado[2] || $sorteado[10] == $sorteado[3] || $sorteado[10] == $sorteado[4] || $sorteado[10] == $sorteado[5] || $sorteado[10] == $sorteado[6] || $sorteado[10] == $sorteado[7] || $sorteado[10] == $sorteado[8] || $sorteado[10] == $sorteado[9] ) {
//         --$i;
//     }
// }
// # Decimo primeiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[11] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[11] == $sorteado[1] || $sorteado[11] == $sorteado[2] || $sorteado[11] == $sorteado[3] || $sorteado[11] == $sorteado[4] || $sorteado[11] == $sorteado[5] || $sorteado[11] == $sorteado[6] || $sorteado[11] == $sorteado[7] || $sorteado[11] == $sorteado[8] || $sorteado[11] == $sorteado[9] || $sorteado[11] == $sorteado[10] ) {
//         --$i;
//     }
// }
// # Decimo segundo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[12] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[12] == $sorteado[1] || $sorteado[12] == $sorteado[2] || $sorteado[12] == $sorteado[3] || $sorteado[12] == $sorteado[4] || $sorteado[12] == $sorteado[5] || $sorteado[12] == $sorteado[6] || $sorteado[12] == $sorteado[7] || $sorteado[12] == $sorteado[8] || $sorteado[12] == $sorteado[9] || $sorteado[12] == $sorteado[10] || $sorteado[12] == $sorteado[11] ) {
//         --$i;
//     }
// }
// # Decimo terceiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[13] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[13] == $sorteado[1] || $sorteado[13] == $sorteado[2] || $sorteado[13] == $sorteado[3] || $sorteado[13] == $sorteado[4] || $sorteado[13] == $sorteado[5] || $sorteado[13] == $sorteado[6] || $sorteado[13] == $sorteado[7] || $sorteado[13] == $sorteado[8] || $sorteado[13] == $sorteado[9] || $sorteado[13] == $sorteado[10] || $sorteado[13] == $sorteado[11] || $sorteado[13] == $sorteado[12] ) {
//         --$i;
//     }
// }
// # Decimo quarto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[14] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[14] == $sorteado[1] || $sorteado[14] == $sorteado[2] || $sorteado[14] == $sorteado[3] || $sorteado[14] == $sorteado[4] || $sorteado[14] == $sorteado[5] || $sorteado[14] == $sorteado[6] || $sorteado[14] == $sorteado[7] || $sorteado[14] == $sorteado[8] || $sorteado[14] == $sorteado[9] || $sorteado[14] == $sorteado[10] || $sorteado[14] == $sorteado[11] || $sorteado[14] == $sorteado[12] || $sorteado[14] == $sorteado[13] ) {
//         --$i;
//     }
// }
// # Decimo quinto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[15] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[15] == $sorteado[1] || $sorteado[15] == $sorteado[2] || $sorteado[15] == $sorteado[3] || $sorteado[15] == $sorteado[4] || $sorteado[15] == $sorteado[5] || $sorteado[15] == $sorteado[6] || $sorteado[15] == $sorteado[7] || $sorteado[15] == $sorteado[8] || $sorteado[15] == $sorteado[9] || $sorteado[15] == $sorteado[10] || $sorteado[15] == $sorteado[11] || $sorteado[15] == $sorteado[12] || $sorteado[15] == $sorteado[13] || $sorteado[15] == $sorteado[14] ) {
//         --$i;
//     }
// }
// # Decimo sexto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[16] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[16] == $sorteado[1] || $sorteado[16] == $sorteado[2] || $sorteado[16] == $sorteado[3] || $sorteado[16] == $sorteado[4] || $sorteado[16] == $sorteado[5] || $sorteado[16] == $sorteado[6] || $sorteado[16] == $sorteado[7] || $sorteado[16] == $sorteado[8] || $sorteado[16] == $sorteado[9] || $sorteado[16] == $sorteado[10] || $sorteado[16] == $sorteado[11] || $sorteado[16] == $sorteado[12] || $sorteado[16] == $sorteado[13] || $sorteado[16] == $sorteado[14] || $sorteado[16] == $sorteado[15] ) {
//         --$i;
//     }
// }
// # Decimo sexto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[17] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[17] == $sorteado[1] || $sorteado[17] == $sorteado[2] || $sorteado[17] == $sorteado[3] || $sorteado[17] == $sorteado[4] || $sorteado[17] == $sorteado[5] || $sorteado[17] == $sorteado[6] || $sorteado[17] == $sorteado[7] || $sorteado[17] == $sorteado[8] || $sorteado[17] == $sorteado[9] || $sorteado[17] == $sorteado[10] || $sorteado[17] == $sorteado[11] || $sorteado[17] == $sorteado[12] || $sorteado[17] == $sorteado[13] || $sorteado[17] == $sorteado[14] || $sorteado[17] == $sorteado[15] || $sorteado[17] == $sorteado[16] ) {
//         --$i;
//     }
// }
// # Decimo oitavo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[18] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[18] == $sorteado[1] || $sorteado[18] == $sorteado[2] || $sorteado[18] == $sorteado[3] || $sorteado[18] == $sorteado[4] || $sorteado[18] == $sorteado[5] || $sorteado[18] == $sorteado[6] || $sorteado[18] == $sorteado[7] || $sorteado[18] == $sorteado[8] || $sorteado[18] == $sorteado[9] || $sorteado[18] == $sorteado[10] || $sorteado[18] == $sorteado[11] || $sorteado[18] == $sorteado[12] || $sorteado[18] == $sorteado[13] || $sorteado[18] == $sorteado[14] || $sorteado[18] == $sorteado[15] || $sorteado[18] == $sorteado[16] || $sorteado[18] == $sorteado[17]) {
//         --$i;
//     }
// }
// # Decimo nono ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[19] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[19] == $sorteado[1] || $sorteado[19] == $sorteado[2] || $sorteado[19] == $sorteado[3] || $sorteado[19] == $sorteado[4] || $sorteado[19] == $sorteado[5] || $sorteado[19] == $sorteado[6] || $sorteado[19] == $sorteado[7] || $sorteado[19] == $sorteado[8] || $sorteado[19] == $sorteado[9] || $sorteado[19] == $sorteado[10] || $sorteado[19] == $sorteado[11] || $sorteado[19] == $sorteado[12] || $sorteado[19] == $sorteado[13] || $sorteado[19] == $sorteado[14] || $sorteado[19] == $sorteado[15] || $sorteado[19] == $sorteado[16] || $sorteado[19] == $sorteado[17] || $sorteado[19] == $sorteado[18]) {
//         --$i;
//     }
// }
// # Vigésimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[20] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[20] == $sorteado[1] || $sorteado[20] == $sorteado[2] || $sorteado[20] == $sorteado[3] || $sorteado[20] == $sorteado[4] || $sorteado[20] == $sorteado[5] || $sorteado[20] == $sorteado[6] || $sorteado[20] == $sorteado[7] || $sorteado[20] == $sorteado[8] || $sorteado[20] == $sorteado[9] || $sorteado[20] == $sorteado[10] || $sorteado[20] == $sorteado[11] || $sorteado[20] == $sorteado[12] || $sorteado[20] == $sorteado[13] || $sorteado[20] == $sorteado[14] || $sorteado[20] == $sorteado[15] || $sorteado[20] == $sorteado[16] || $sorteado[20] == $sorteado[17] || $sorteado[20] == $sorteado[18] || $sorteado[20] == $sorteado[19]) {
//         --$i;
//     }
// }
// # Vigésimo primeiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[21] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[21] == $sorteado[1] || $sorteado[21] == $sorteado[2] || $sorteado[21] == $sorteado[3] || $sorteado[21] == $sorteado[4] || $sorteado[21] == $sorteado[5] || $sorteado[21] == $sorteado[6] || $sorteado[21] == $sorteado[7] || $sorteado[21] == $sorteado[8] || $sorteado[21] == $sorteado[9] || $sorteado[21] == $sorteado[10] || $sorteado[21] == $sorteado[11] || $sorteado[21] == $sorteado[12] || $sorteado[21] == $sorteado[13] || $sorteado[21] == $sorteado[14] || $sorteado[21] == $sorteado[15] || $sorteado[21] == $sorteado[16] || $sorteado[21] == $sorteado[17] || $sorteado[21] == $sorteado[18] || $sorteado[21] == $sorteado[19] || $sorteado[21] == $sorteado[20]) {
//         --$i;
//     }
// }
// # Vigésimo segundo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[22] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[22] == $sorteado[1] || $sorteado[22] == $sorteado[2] || $sorteado[22] == $sorteado[3] || $sorteado[22] == $sorteado[4] || $sorteado[22] == $sorteado[5] || $sorteado[22] == $sorteado[6] || $sorteado[22] == $sorteado[7] || $sorteado[22] == $sorteado[8] || $sorteado[22] == $sorteado[9] || $sorteado[22] == $sorteado[10] || $sorteado[22] == $sorteado[11] || $sorteado[22] == $sorteado[12] || $sorteado[22] == $sorteado[13] || $sorteado[22] == $sorteado[14] || $sorteado[22] == $sorteado[15] || $sorteado[22] == $sorteado[16] || $sorteado[22] == $sorteado[17] || $sorteado[22] == $sorteado[18] || $sorteado[22] == $sorteado[19] || $sorteado[22] == $sorteado[20] || $sorteado[22] == $sorteado[21]) {
//         --$i;
//     }
// }
// # Vigésimo terceiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[23] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[23] == $sorteado[1] || $sorteado[23] == $sorteado[2] || $sorteado[23] == $sorteado[3] || $sorteado[23] == $sorteado[4] || $sorteado[23] == $sorteado[5] || $sorteado[23] == $sorteado[6] || $sorteado[23] == $sorteado[7] || $sorteado[23] == $sorteado[8] || $sorteado[23] == $sorteado[9] || $sorteado[23] == $sorteado[10] || $sorteado[23] == $sorteado[11] || $sorteado[23] == $sorteado[12] || $sorteado[23] == $sorteado[13] || $sorteado[23] == $sorteado[14] || $sorteado[23] == $sorteado[15] || $sorteado[23] == $sorteado[16] || $sorteado[23] == $sorteado[17] || $sorteado[23] == $sorteado[18] || $sorteado[23] == $sorteado[19] || $sorteado[23] == $sorteado[20] || $sorteado[23] == $sorteado[21] || $sorteado[23] == $sorteado[22]) {
//         --$i;
//     }
// }
// # Vigésimo quarto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[24] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[24] == $sorteado[1] || $sorteado[24] == $sorteado[2] || $sorteado[24] == $sorteado[3] || $sorteado[24] == $sorteado[4] || $sorteado[24] == $sorteado[5] || $sorteado[24] == $sorteado[6] || $sorteado[24] == $sorteado[7] || $sorteado[24] == $sorteado[8] || $sorteado[24] == $sorteado[9] || $sorteado[24] == $sorteado[10] || $sorteado[24] == $sorteado[11] || $sorteado[24] == $sorteado[12] || $sorteado[24] == $sorteado[13] || $sorteado[24] == $sorteado[14] || $sorteado[24] == $sorteado[15] || $sorteado[24] == $sorteado[16] || $sorteado[24] == $sorteado[17] || $sorteado[24] == $sorteado[18] || $sorteado[24] == $sorteado[19] || $sorteado[24] == $sorteado[20] || $sorteado[24] == $sorteado[21] || $sorteado[24] == $sorteado[22] || $sorteado[24] == $sorteado[23]) {
//         --$i;
//     }
// }
// # Vigésimo quinto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[25] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[25] == $sorteado[1] || $sorteado[25] == $sorteado[2] || $sorteado[25] == $sorteado[3] || $sorteado[25] == $sorteado[4] || $sorteado[25] == $sorteado[5] || $sorteado[25] == $sorteado[6] || $sorteado[25] == $sorteado[7] || $sorteado[25] == $sorteado[8] || $sorteado[25] == $sorteado[9] || $sorteado[25] == $sorteado[10] || $sorteado[25] == $sorteado[11] || $sorteado[25] == $sorteado[12] || $sorteado[25] == $sorteado[13] || $sorteado[25] == $sorteado[14] || $sorteado[25] == $sorteado[15] || $sorteado[25] == $sorteado[16] || $sorteado[25] == $sorteado[17] || $sorteado[25] == $sorteado[18] || $sorteado[25] == $sorteado[19] || $sorteado[25] == $sorteado[20] || $sorteado[25] == $sorteado[21] || $sorteado[25] == $sorteado[22] || $sorteado[25] == $sorteado[23] || $sorteado[25] == $sorteado[24]) {
//         --$i;
//     }
// }

// # Vigésimo sexto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[26] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[26] == $sorteado[1] || $sorteado[26] == $sorteado[2] || $sorteado[26] == $sorteado[3] || $sorteado[26] == $sorteado[4] || $sorteado[26] == $sorteado[5] || $sorteado[26] == $sorteado[6] || $sorteado[26] == $sorteado[7] || $sorteado[26] == $sorteado[8] || $sorteado[26] == $sorteado[9] || $sorteado[26] == $sorteado[10] || $sorteado[26] == $sorteado[11] || $sorteado[26] == $sorteado[12] || $sorteado[26] == $sorteado[13] || $sorteado[26] == $sorteado[14] || $sorteado[26] == $sorteado[15] || $sorteado[26] == $sorteado[16] || $sorteado[26] == $sorteado[17] || $sorteado[26] == $sorteado[18] || $sorteado[26] == $sorteado[19] || $sorteado[26] == $sorteado[20] || $sorteado[26] == $sorteado[21] || $sorteado[26] == $sorteado[22] || $sorteado[26] == $sorteado[23] || $sorteado[26] == $sorteado[24] || $sorteado[26] == $sorteado[25]) {
//         --$i;
//     }
// }

// # Vigésimo setimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[27] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[27] == $sorteado[1] || $sorteado[27] == $sorteado[2] || $sorteado[27] == $sorteado[3] || $sorteado[27] == $sorteado[4] || $sorteado[27] == $sorteado[5] || $sorteado[27] == $sorteado[6] || $sorteado[27] == $sorteado[7] || $sorteado[27] == $sorteado[8] || $sorteado[27] == $sorteado[9] || $sorteado[27] == $sorteado[10] || $sorteado[27] == $sorteado[11] || $sorteado[27] == $sorteado[12] || $sorteado[27] == $sorteado[13] || $sorteado[27] == $sorteado[14] || $sorteado[27] == $sorteado[15] || $sorteado[27] == $sorteado[16] || $sorteado[27] == $sorteado[17] || $sorteado[27] == $sorteado[18] || $sorteado[27] == $sorteado[19] || $sorteado[27] == $sorteado[20] || $sorteado[27] == $sorteado[21] || $sorteado[27] == $sorteado[22] || $sorteado[27] == $sorteado[23] || $sorteado[27] == $sorteado[24] || $sorteado[27] == $sorteado[25] || $sorteado[27] == $sorteado[26]) {
//         --$i;
//     }
// }

// # Vigésimo oitavo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[28] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[28] == $sorteado[1] || $sorteado[28] == $sorteado[2] || $sorteado[28] == $sorteado[3] || $sorteado[28] == $sorteado[4] || $sorteado[28] == $sorteado[5] || $sorteado[28] == $sorteado[6] || $sorteado[28] == $sorteado[7] || $sorteado[28] == $sorteado[8] || $sorteado[28] == $sorteado[9] || $sorteado[28] == $sorteado[10] || $sorteado[28] == $sorteado[11] || $sorteado[28] == $sorteado[12] || $sorteado[28] == $sorteado[13] || $sorteado[28] == $sorteado[14] || $sorteado[28] == $sorteado[15] || $sorteado[28] == $sorteado[16] || $sorteado[28] == $sorteado[17] || $sorteado[28] == $sorteado[18] || $sorteado[28] == $sorteado[19] || $sorteado[28] == $sorteado[20] || $sorteado[28] == $sorteado[21] || $sorteado[28] == $sorteado[22] || $sorteado[28] == $sorteado[23] || $sorteado[28] == $sorteado[24] || $sorteado[28] == $sorteado[25] || $sorteado[28] == $sorteado[26] || $sorteado[28] == $sorteado[27]) {
//         --$i;
//     }
// }

// # Vigésimo nono ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[29] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[29] == $sorteado[1] || $sorteado[29] == $sorteado[2] || $sorteado[29] == $sorteado[3] || $sorteado[29] == $sorteado[4] || $sorteado[29] == $sorteado[5] || $sorteado[29] == $sorteado[6] || $sorteado[29] == $sorteado[7] || $sorteado[29] == $sorteado[8] || $sorteado[29] == $sorteado[9] || $sorteado[29] == $sorteado[10] || $sorteado[29] == $sorteado[11] || $sorteado[29] == $sorteado[12] || $sorteado[29] == $sorteado[13] || $sorteado[29] == $sorteado[14] || $sorteado[29] == $sorteado[15] || $sorteado[29] == $sorteado[16] || $sorteado[29] == $sorteado[17] || $sorteado[29] == $sorteado[18] || $sorteado[29] == $sorteado[19] || $sorteado[29] == $sorteado[20] || $sorteado[29] == $sorteado[21] || $sorteado[29] == $sorteado[22] || $sorteado[29] == $sorteado[23] || $sorteado[29] == $sorteado[24] || $sorteado[29] == $sorteado[25] || $sorteado[29] == $sorteado[26] || $sorteado[29] == $sorteado[27] || $sorteado[29] == $sorteado[28]) {
//         --$i;
//     }
// }

// # Trigésimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[30] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[30] == $sorteado[1] || $sorteado[30] == $sorteado[2] || $sorteado[30] == $sorteado[3] || $sorteado[30] == $sorteado[4] || $sorteado[30] == $sorteado[5] || $sorteado[30] == $sorteado[6] || $sorteado[30] == $sorteado[7] || $sorteado[30] == $sorteado[8] || $sorteado[30] == $sorteado[9] || $sorteado[30] == $sorteado[10] || $sorteado[30] == $sorteado[11] || $sorteado[30] == $sorteado[12] || $sorteado[30] == $sorteado[13] || $sorteado[30] == $sorteado[14] || $sorteado[30] == $sorteado[15] || $sorteado[30] == $sorteado[16] || $sorteado[30] == $sorteado[17] || $sorteado[30] == $sorteado[18] || $sorteado[30] == $sorteado[19] || $sorteado[30] == $sorteado[20] || $sorteado[30] == $sorteado[21] || $sorteado[30] == $sorteado[22] || $sorteado[30] == $sorteado[23] || $sorteado[30] == $sorteado[24] || $sorteado[30] == $sorteado[25] || $sorteado[30] == $sorteado[26] || $sorteado[30] == $sorteado[27] || $sorteado[30] == $sorteado[28] || $sorteado[30] == $sorteado[29]) {
//         --$i;
//     }
// }

// # Trigésimo primeiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[31] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[31] == $sorteado[1] || $sorteado[31] == $sorteado[2] || $sorteado[31] == $sorteado[3] || $sorteado[31] == $sorteado[4] || $sorteado[31] == $sorteado[5] || $sorteado[31] == $sorteado[6] || $sorteado[31] == $sorteado[7] || $sorteado[31] == $sorteado[8] || $sorteado[31] == $sorteado[9] || $sorteado[31] == $sorteado[10] || $sorteado[31] == $sorteado[11] || $sorteado[31] == $sorteado[12] || $sorteado[31] == $sorteado[13] || $sorteado[31] == $sorteado[14] || $sorteado[31] == $sorteado[15] || $sorteado[31] == $sorteado[16] || $sorteado[31] == $sorteado[17] || $sorteado[31] == $sorteado[18] || $sorteado[31] == $sorteado[19] || $sorteado[31] == $sorteado[20] || $sorteado[31] == $sorteado[21] || $sorteado[31] == $sorteado[22] || $sorteado[31] == $sorteado[23] || $sorteado[31] == $sorteado[24] || $sorteado[31] == $sorteado[25] || $sorteado[31] == $sorteado[26] || $sorteado[31] == $sorteado[27] || $sorteado[31] == $sorteado[28] || $sorteado[31] == $sorteado[29] || $sorteado[31] == $sorteado[30]) {
//         --$i;
//     }
// }

// # Trigésimo segundo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[32] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[32] == $sorteado[1] || $sorteado[32] == $sorteado[2] || $sorteado[32] == $sorteado[3] || $sorteado[32] == $sorteado[4] || $sorteado[32] == $sorteado[5] || $sorteado[32] == $sorteado[6] || $sorteado[32] == $sorteado[7] || $sorteado[32] == $sorteado[8] || $sorteado[32] == $sorteado[9] || $sorteado[32] == $sorteado[10] || $sorteado[32] == $sorteado[11] || $sorteado[32] == $sorteado[12] || $sorteado[32] == $sorteado[13] || $sorteado[32] == $sorteado[14] || $sorteado[32] == $sorteado[15] || $sorteado[32] == $sorteado[16] || $sorteado[32] == $sorteado[17] || $sorteado[32] == $sorteado[18] || $sorteado[32] == $sorteado[19] || $sorteado[32] == $sorteado[20] || $sorteado[32] == $sorteado[21] || $sorteado[32] == $sorteado[22] || $sorteado[32] == $sorteado[23] || $sorteado[32] == $sorteado[24] || $sorteado[32] == $sorteado[25] || $sorteado[32] == $sorteado[26] || $sorteado[32] == $sorteado[27] || $sorteado[32] == $sorteado[28] || $sorteado[32] == $sorteado[29] || $sorteado[32] == $sorteado[30] || $sorteado[32] == $sorteado[31]) {
//         --$i;
//     }
// }

// # Trigésimo terceiro ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[33] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[33] == $sorteado[1] || $sorteado[33] == $sorteado[2] || $sorteado[33] == $sorteado[3] || $sorteado[33] == $sorteado[4] || $sorteado[33] == $sorteado[5] || $sorteado[33] == $sorteado[6] || $sorteado[33] == $sorteado[7] || $sorteado[33] == $sorteado[8] || $sorteado[33] == $sorteado[9] || $sorteado[33] == $sorteado[10] || $sorteado[33] == $sorteado[11] || $sorteado[33] == $sorteado[12] || $sorteado[33] == $sorteado[13] || $sorteado[33] == $sorteado[14] || $sorteado[33] == $sorteado[15] || $sorteado[33] == $sorteado[16] || $sorteado[33] == $sorteado[17] || $sorteado[33] == $sorteado[18] || $sorteado[33] == $sorteado[19] || $sorteado[33] == $sorteado[20] || $sorteado[33] == $sorteado[21] || $sorteado[33] == $sorteado[22] || $sorteado[33] == $sorteado[23] || $sorteado[33] == $sorteado[24] || $sorteado[33] == $sorteado[25] || $sorteado[33] == $sorteado[26] || $sorteado[33] == $sorteado[27] || $sorteado[33] == $sorteado[28] || $sorteado[33] == $sorteado[29] || $sorteado[33] == $sorteado[30] || $sorteado[33] == $sorteado[31] || $sorteado[33] == $sorteado[32]) {
//         --$i;
//     }
// }

// # Trigésimo quarto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[34] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[34] == $sorteado[1] || $sorteado[34] == $sorteado[2] || $sorteado[34] == $sorteado[3] || $sorteado[34] == $sorteado[4] || $sorteado[34] == $sorteado[5] || $sorteado[34] == $sorteado[6] || $sorteado[34] == $sorteado[7] || $sorteado[34] == $sorteado[8] || $sorteado[34] == $sorteado[9] || $sorteado[34] == $sorteado[10] || $sorteado[34] == $sorteado[11] || $sorteado[34] == $sorteado[12] || $sorteado[34] == $sorteado[13] || $sorteado[34] == $sorteado[14] || $sorteado[34] == $sorteado[15] || $sorteado[34] == $sorteado[16] || $sorteado[34] == $sorteado[17] || $sorteado[34] == $sorteado[18] || $sorteado[34] == $sorteado[19] || $sorteado[34] == $sorteado[20] || $sorteado[34] == $sorteado[21] || $sorteado[34] == $sorteado[22] || $sorteado[34] == $sorteado[23] || $sorteado[34] == $sorteado[24] || $sorteado[34] == $sorteado[25] || $sorteado[34] == $sorteado[26] || $sorteado[34] == $sorteado[27] || $sorteado[34] == $sorteado[28] || $sorteado[34] == $sorteado[29] || $sorteado[34] == $sorteado[30] || $sorteado[34] == $sorteado[31] || $sorteado[34] == $sorteado[32] || $sorteado[34] == $sorteado[33]) {
//         --$i;
//     }
// }

// # Trigésimo quinto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[35] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[35] == $sorteado[1] || $sorteado[35] == $sorteado[2] || $sorteado[35] == $sorteado[3] || $sorteado[35] == $sorteado[4] || $sorteado[35] == $sorteado[5] || $sorteado[35] == $sorteado[6] || $sorteado[35] == $sorteado[7] || $sorteado[35] == $sorteado[8] || $sorteado[35] == $sorteado[9] || $sorteado[35] == $sorteado[10] || $sorteado[35] == $sorteado[11] || $sorteado[35] == $sorteado[12] || $sorteado[35] == $sorteado[13] || $sorteado[35] == $sorteado[14] || $sorteado[35] == $sorteado[15] || $sorteado[35] == $sorteado[16] || $sorteado[35] == $sorteado[17] || $sorteado[35] == $sorteado[18] || $sorteado[35] == $sorteado[19] || $sorteado[35] == $sorteado[20] || $sorteado[35] == $sorteado[21] || $sorteado[35] == $sorteado[22] || $sorteado[35] == $sorteado[23] || $sorteado[35] == $sorteado[24] || $sorteado[35] == $sorteado[25] || $sorteado[35] == $sorteado[26] || $sorteado[35] == $sorteado[27] || $sorteado[35] == $sorteado[28] || $sorteado[35] == $sorteado[29] || $sorteado[35] == $sorteado[30] || $sorteado[35] == $sorteado[31] || $sorteado[35] == $sorteado[32] || $sorteado[35] == $sorteado[33] || $sorteado[35] == $sorteado[34]) {
//         --$i;
//     }
// }

// # Trigésimo sexto ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[36] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[36] == $sorteado[1] || $sorteado[36] == $sorteado[2] || $sorteado[36] == $sorteado[3] || $sorteado[36] == $sorteado[4] || $sorteado[36] == $sorteado[5] || $sorteado[36] == $sorteado[6] || $sorteado[36] == $sorteado[7] || $sorteado[36] == $sorteado[8] || $sorteado[36] == $sorteado[9] || $sorteado[36] == $sorteado[10] || $sorteado[36] == $sorteado[11] || $sorteado[36] == $sorteado[12] || $sorteado[36] == $sorteado[13] || $sorteado[36] == $sorteado[14] || $sorteado[36] == $sorteado[15] || $sorteado[36] == $sorteado[16] || $sorteado[36] == $sorteado[17] || $sorteado[36] == $sorteado[18] || $sorteado[36] == $sorteado[19] || $sorteado[36] == $sorteado[20] || $sorteado[36] == $sorteado[21] || $sorteado[36] == $sorteado[22] || $sorteado[36] == $sorteado[23] || $sorteado[36] == $sorteado[24] || $sorteado[36] == $sorteado[25] || $sorteado[36] == $sorteado[26] || $sorteado[36] == $sorteado[27] || $sorteado[36] == $sorteado[28] || $sorteado[36] == $sorteado[29] || $sorteado[36] == $sorteado[30] || $sorteado[36] == $sorteado[31] || $sorteado[36] == $sorteado[32] || $sorteado[36] == $sorteado[33] || $sorteado[36] == $sorteado[34] || $sorteado[36] == $sorteado[35]) {
//         --$i;
//     }
// }

// # Trigésimo sétimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[37] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[37] == $sorteado[1] || $sorteado[37] == $sorteado[2] || $sorteado[37] == $sorteado[3] || $sorteado[37] == $sorteado[4] || $sorteado[37] == $sorteado[5] || $sorteado[37] == $sorteado[6] || $sorteado[37] == $sorteado[7] || $sorteado[37] == $sorteado[8] || $sorteado[37] == $sorteado[9] || $sorteado[37] == $sorteado[10] || $sorteado[37] == $sorteado[11] || $sorteado[37] == $sorteado[12] || $sorteado[37] == $sorteado[13] || $sorteado[37] == $sorteado[14] || $sorteado[37] == $sorteado[15] || $sorteado[37] == $sorteado[16] || $sorteado[37] == $sorteado[17] || $sorteado[37] == $sorteado[18] || $sorteado[37] == $sorteado[19] || $sorteado[37] == $sorteado[20] || $sorteado[37] == $sorteado[21] || $sorteado[37] == $sorteado[22] || $sorteado[37] == $sorteado[23] || $sorteado[37] == $sorteado[24] || $sorteado[37] == $sorteado[25] || $sorteado[37] == $sorteado[26] || $sorteado[37] == $sorteado[27] || $sorteado[37] == $sorteado[28] || $sorteado[37] == $sorteado[29] || $sorteado[37] == $sorteado[30] || $sorteado[37] == $sorteado[31] || $sorteado[37] == $sorteado[32] || $sorteado[37] == $sorteado[33] || $sorteado[37] == $sorteado[34] || $sorteado[37] == $sorteado[35] || $sorteado[37] == $sorteado[36]) {
//         --$i;
//     }
// }

// # Trigésimo oitavo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[38] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[38] == $sorteado[1] || $sorteado[38] == $sorteado[2] || $sorteado[38] == $sorteado[3] || $sorteado[38] == $sorteado[4] || $sorteado[38] == $sorteado[5] || $sorteado[38] == $sorteado[6] || $sorteado[38] == $sorteado[7] || $sorteado[38] == $sorteado[8] || $sorteado[38] == $sorteado[9] || $sorteado[38] == $sorteado[10] || $sorteado[38] == $sorteado[11] || $sorteado[38] == $sorteado[12] || $sorteado[38] == $sorteado[13] || $sorteado[38] == $sorteado[14] || $sorteado[38] == $sorteado[15] || $sorteado[38] == $sorteado[16] || $sorteado[38] == $sorteado[17] || $sorteado[38] == $sorteado[18] || $sorteado[38] == $sorteado[19] || $sorteado[38] == $sorteado[20] || $sorteado[38] == $sorteado[21] || $sorteado[38] == $sorteado[22] || $sorteado[38] == $sorteado[23] || $sorteado[38] == $sorteado[24] || $sorteado[38] == $sorteado[25] || $sorteado[38] == $sorteado[26] || $sorteado[38] == $sorteado[27] || $sorteado[38] == $sorteado[28] || $sorteado[38] == $sorteado[29] || $sorteado[38] == $sorteado[30] || $sorteado[38] == $sorteado[31] || $sorteado[38] == $sorteado[32] || $sorteado[38] == $sorteado[33] || $sorteado[38] == $sorteado[34] || $sorteado[38] == $sorteado[35] || $sorteado[38] == $sorteado[36] || $sorteado[38] == $sorteado[37]) {
//         --$i;
//     }
// }

// # Trigésimo nono ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[39] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[39] == $sorteado[1] || $sorteado[39] == $sorteado[2] || $sorteado[39] == $sorteado[3] || $sorteado[39] == $sorteado[4] || $sorteado[39] == $sorteado[5] || $sorteado[39] == $sorteado[6] || $sorteado[39] == $sorteado[7] || $sorteado[39] == $sorteado[8] || $sorteado[39] == $sorteado[9] || $sorteado[39] == $sorteado[10] || $sorteado[39] == $sorteado[11] || $sorteado[39] == $sorteado[12] || $sorteado[39] == $sorteado[13] || $sorteado[39] == $sorteado[14] || $sorteado[39] == $sorteado[15] || $sorteado[39] == $sorteado[16] || $sorteado[39] == $sorteado[17] || $sorteado[39] == $sorteado[18] || $sorteado[39] == $sorteado[19] || $sorteado[39] == $sorteado[20] || $sorteado[39] == $sorteado[21] || $sorteado[39] == $sorteado[22] || $sorteado[39] == $sorteado[23] || $sorteado[39] == $sorteado[24] || $sorteado[39] == $sorteado[25] || $sorteado[39] == $sorteado[26] || $sorteado[39] == $sorteado[27] || $sorteado[39] == $sorteado[28] || $sorteado[39] == $sorteado[29] || $sorteado[39] == $sorteado[30] || $sorteado[39] == $sorteado[31] || $sorteado[39] == $sorteado[32] || $sorteado[39] == $sorteado[33] || $sorteado[39] == $sorteado[34] || $sorteado[39] == $sorteado[35] || $sorteado[39] == $sorteado[36] || $sorteado[39] == $sorteado[37] || $sorteado[39] == $sorteado[38]) {
//         --$i;
//     }
// }

// # Quadragésimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[40] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[40] == $sorteado[1] || $sorteado[40] == $sorteado[2] || $sorteado[40] == $sorteado[3] || $sorteado[40] == $sorteado[4] || $sorteado[40] == $sorteado[5] || $sorteado[40] == $sorteado[6] || $sorteado[40] == $sorteado[7] || $sorteado[40] == $sorteado[8] || $sorteado[40] == $sorteado[9] || $sorteado[40] == $sorteado[10] || $sorteado[40] == $sorteado[11] || $sorteado[40] == $sorteado[12] || $sorteado[40] == $sorteado[13] || $sorteado[40] == $sorteado[14] || $sorteado[40] == $sorteado[15] || $sorteado[40] == $sorteado[16] || $sorteado[40] == $sorteado[17] || $sorteado[40] == $sorteado[18] || $sorteado[40] == $sorteado[19] || $sorteado[40] == $sorteado[20] || $sorteado[40] == $sorteado[21] || $sorteado[40] == $sorteado[22] || $sorteado[40] == $sorteado[23] || $sorteado[40] == $sorteado[24] || $sorteado[40] == $sorteado[25] || $sorteado[40] == $sorteado[26] || $sorteado[40] == $sorteado[27] || $sorteado[40] == $sorteado[28] || $sorteado[40] == $sorteado[29] || $sorteado[40] == $sorteado[30] || $sorteado[40] == $sorteado[31] || $sorteado[40] == $sorteado[32] || $sorteado[40] == $sorteado[33] || $sorteado[40] == $sorteado[34] || $sorteado[40] == $sorteado[35] || $sorteado[40] == $sorteado[36] || $sorteado[40] == $sorteado[37] || $sorteado[40] == $sorteado[38] || $sorteado[40] == $sorteado[39]) {
//         --$i;
//     }
// }

// # Quadragésimo ganhador
// for ($i = 1; $i < 2; $i++) {
//     $sorteado[41] = $participantes[rand(0,$numParticipantes - 1)];
//     // Caso o ganhador já tenha saido, sorteia novamente.
//     if ($sorteado[41] == $sorteado[1] || $sorteado[41] == $sorteado[2] || $sorteado[41] == $sorteado[3] || $sorteado[41] == $sorteado[4] || $sorteado[41] == $sorteado[5] || $sorteado[41] == $sorteado[6] || $sorteado[41] == $sorteado[7] || $sorteado[41] == $sorteado[8] || $sorteado[41] == $sorteado[9] || $sorteado[41] == $sorteado[10] || $sorteado[41] == $sorteado[11] || $sorteado[41] == $sorteado[12] || $sorteado[41] == $sorteado[13] || $sorteado[41] == $sorteado[14] || $sorteado[41] == $sorteado[15] || $sorteado[41] == $sorteado[16] || $sorteado[41] == $sorteado[17] || $sorteado[41] == $sorteado[18] || $sorteado[41] == $sorteado[19] || $sorteado[41] == $sorteado[20] || $sorteado[41] == $sorteado[21] || $sorteado[41] == $sorteado[22] || $sorteado[41] == $sorteado[23] || $sorteado[41] == $sorteado[24] || $sorteado[41] == $sorteado[25] || $sorteado[41] == $sorteado[26] || $sorteado[41] == $sorteado[27] || $sorteado[41] == $sorteado[28] || $sorteado[41] == $sorteado[29] || $sorteado[41] == $sorteado[30] || $sorteado[41] == $sorteado[31] || $sorteado[41] == $sorteado[32] || $sorteado[41] == $sorteado[33] || $sorteado[41] == $sorteado[34] || $sorteado[41] == $sorteado[35] || $sorteado[41] == $sorteado[36] || $sorteado[41] == $sorteado[37] || $sorteado[41] == $sorteado[38] || $sorteado[41] == $sorteado[39] || $sorteado[41] == $sorteado[40]) {
//         --$i;
//     }
// }

// //Cadastra os sorteados no banco de dados
// foreach($sorteado as $pessoa) {


//     $cmd = "INSERT INTO sorteados (n_ingresso, nome, sobrenome, cpf, telefone) VALUES (:n_ingresso, :nome, :sobrenome, :cpf, :telefone)";
//     $stmt = $conn->prepare($cmd);
//     $stmt->bindValue(':n_ingresso', $pessoa["n_ingresso"]);
//     $stmt->bindValue(':nome', $pessoa["nome"]);
//     $stmt->bindValue(':sobrenome', $pessoa["sobrenome"]);
//     $stmt->bindValue(':cpf', $pessoa["cpf"]);
//     $stmt->bindValue(':telefone', $pessoa["telefone"]);
//     $stmt->execute();

// }

// $sql3 = "SELECT DISTINCT secretaria FROM lista";
// $stmt3 = $conn->prepare($sql3);
// $stmt3->execute();
// $secretariaSorteio = $stmt3->fetchALL(PDO::FETCH_ASSOC);
