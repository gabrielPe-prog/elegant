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

$conn = conexao_pdo();
$sql = ("SELECT * FROM sorteados");
$stm = $conn->prepare($sql);
$stm->execute();
$resultado = $stm->fetchAll(PDO::FETCH_OBJ);

$sqlBrinde = "SELECT * FROM brindes";
$stmBrinde = $conn->prepare($sqlBrinde);
$stmBrinde->execute();
$brindes = $stmBrinde->fetchAll(PDO::FETCH_OBJ);

// var_dump(count($brindes));
// die();
?>
<!DOCTYPE html>
<html class="loading" lang="pt-BR" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Sistema de sorteio dos ingressos aprovados para a Festa do Servidor">
    <meta name="keywords" content="Festa do servidor, prefeitura de caruaru, Caruaru, Festa do Servidor 2023">
    <meta name="author" content="Sorteio Festa do Servidor 2024">
    <title>SORTEIO - Festa do Servidor 2024</title>
    <link rel="apple-touch-icon" href="../../../app-assets/images/ico/apple-icon-120.png">
    <link rel="shortcut icon" type="image/x-icon" href="../../../app-assets/images/ico/favicon.ico">
    <link
        href="https://fonts.googleapis.com/css?family=Muli:300,300i,400,400i,600,600i,700,700i|Comfortaa:300,400,500,700"
        rel="stylesheet">
    <!-- BEGIN VENDOR CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/vendors.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/chartist.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/charts/chartist-plugin-tooltip.css">
    <!-- END VENDOR CSS-->
    <!-- BEGIN MODERN CSS-->
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/app.css">
    <!-- END MODERN CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- BEGIN Page Level CSS-->
    <link rel="stylesheet" type="text/css"
        href="../../../app-assets/css/core/menu/menu-types/vertical-compact-menu.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/vendors/css/cryptocoins/cryptocoins.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/timeline.css">
    <link rel="stylesheet" type="text/css" href="../../../app-assets/css/pages/dashboard-ico.css">
    <!-- END Page Level CSS-->
    <!-- BEGIN Custom CSS-->
    <link rel="stylesheet" type="text/css" href="../../../assets/css/style.css">
    <!-- END Custom CSS-->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <style>
        .spinner-wrapper {
            background-color: #296e144f;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.2s;

        }

        .spinner-border {
            height: 60px;
            width: 60px;

        }
    </style>
</head>

<body class="vertical-layout vertical-compact-menu content-detached-right-sidebar   menu-expanded fixed-navbar"
    data-open="click" data-menu="vertical-compact-menu" data-col="content-detached-right-sidebar">

    <!-- preloader -->
    <div class="spinner-wrapper">
        <div class="spinner-border text-success" role="status">
            <span class="visually-hidden">Carregando...</span>

        </div>
    </div>

    <!-- fixed-top-->
    <?php include_once 'nav.php'; ?>

    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <?php include_once 'main-menu.php'; ?>

    <div class="app-content content">
        <div class="content-wrapper">

            <div class="content-detached content-left">
                <div class="content-body">

                    <h3 class="mt-4">Ganhadores do Sorteio</h3>
                    <p>Informações dos ganhadores:</p>

                    <?php foreach ($resultado as $row): ?>
                        <section class="card pull-up">
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-3 col-xl-2 col-12 d-none d-md-block">
                                                <div class="crypto-circle rounded-circle">
                                                    <strong><?php echo $row->id; ?></strong>
                                                    <form action="script2.php" method="POST">
                                                        <input value="<?php echo $row->secretaria; ?>" hidden name="secretaria">
                                                        <input value="<?php echo $row->id; ?>" hidden name="id">
                                                        <input value="<?php echo $row->n_ingresso; ?>" hidden name="n_ingresso">
                                                        <button type="submit"
                                                        class="mt-2 btn btn-outline-success">Refazer este
                                                        sorteio</button></a>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-xl-7 col-12">
                                                <p><strong>Número do Ingresso:</strong></p>
                                                <h4 class="mb-2"><?php echo $row->n_ingresso; ?></h4>
                                                <p><strong>Nome Completo:</strong></p>
                                                <h4 class="mb-2"><?php echo $row->nome . ' ' . $row->sobrenome; ?></h4>
                                                <p><strong>Secretaria:</strong></p>
                                                <h4 class="mb-2"><?php echo $row->secretaria; ?></h4>
                                                <p><strong>Brinde:</strong></p>
                                                <h4 class="mb-2"><?php echo $brindes[$row->id - 1]->descricao; ?></h4>
                                            </div>
                                            <div class="col-md-4 col-xl-3 col-12 d-none d-md-block">
                                                <img width="200" class="img-fluid"
                                                    src="../../../app-assets/images/pages/presente.jpg" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <?php include_once 'footer.php'; ?>

    <!-- BEGIN VENDOR JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="../../../app-assets/js/core/app-menu.js" type="text/javascript"></script>
    <script src="../../../app-assets/js/core/app.js" type="text/javascript"></script>
    <!-- END MODERN JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <!-- END PAGE LEVEL JS-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>


    <script>

        const spinnerWrapperEl = document.querySelector('.spinner-wrapper');

        window.addEventListener('load', () => {
            spinnerWrapperEl.style.opacity = '0';
        });

        setTimeout(() => {
            spinnerWrapperEl.style.display = 'none';
        }, 200);

    </script>
</body>

</html>