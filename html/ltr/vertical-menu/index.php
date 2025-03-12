<?php
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

$sql = "SELECT COUNT(*) AS total FROM lista";
$stmt = $conn->prepare($sql);
$stmt->execute();
$resultado = $stmt->fetch(PDO::FETCH_OBJ);

$sql2 = ("SELECT COUNT(*) AS total FROM sorteados");
$stm2 = $conn->prepare($sql2);
$stm2->execute();
$resultado2 = $stm2->fetch(PDO::FETCH_OBJ);


?>
<!DOCTYPE html>
<html class="loading" lang="pt-BR" data-textdirection="ltr">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="Sistema de sorteio dos ingressos aprovados para a Festa do Servidor">
    <meta name="keywords" content="Festa do servidor, prefeitura de caruaru, Caruaru, Festa do Servidor 2023">
    <meta name="author" content="Sorteio Festa do Servidor 2023">
    <meta property="og:title" content="SORTEIO - Clube do Servidor">
    <meta property="og:url" content="https://clubedoservidor.caruaru.pe.gov.br/sorteio/html/ltr/vertical-menu/">
    <meta property="og:description" content="Sistema de Sorteio para a Festa do Servidor 2023">
    <meta property="og:image" content="https://clubedoservidor.caruaru.pe.gov.br/assets/img/favicon.png">
    <meta property="og:locale" content="pt_BR">
    <title>SORTEIO - Festa do Servidor 2023</title>
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .bg-progress {
            background-color: #1b570f;
            color: #1b570f;
        }
    </style>

    <script type="text/javascript">

        function modal() {

            swal({
                title: "Deseja realizar o Sorteio?",
                text: "",
                icon: "warning",
                dangerMode: true,
                closeOnEsc: false,
                closeOnClickOutside: false,
                buttons: {
                    cancelar: { // cria o botão para cancelar (q vc pode chamar de "NÂO"
                        text: "Não", // texto do botão
                        value: "cancelar", // valor pra gente testar la em baixo
                        className: "swal-button--success", // classe do botão css
                    },
                    confirmar: { // botao confirmar
                        text: "Sim", // texto do botão
                        value: "confirmar", // valor pra gente testar la em baixo
                        className: "swal-button--danger", // classe do botão css
                    },

                },
            })
                .then((value) => {
                    if (value == 'confirmar') {
                        $.ajax({
                            type: "POST",
                            url: "script.php",
                            success: function (ret) {

                                window.location.href = 'sorteados.php';
                            },
                            error: function () {
                                swal("Atenção", "Erro ao realizar o sorteio!", "error"); //erro HTTP
                            }
                        });
                    }
                });

        }
    </script>

</head>

<body class="vertical-layout vertical-compact-menu 2-columns   menu-expanded fixed-navbar" data-open="click"
    data-menu="vertical-compact-menu" data-col="2-columns">


    <!-- fixed-top-->

    <?php include_once 'nav.php'; ?>
    <!-- ////////////////////////////////////////////////////////////////////////////-->

    <?php include_once 'main-menu.php'; ?>


    <?php include_once 'main.php'; ?>
    <!-- ////////////////////////////////////////////////////////////////////////////-->


    <?php include_once 'footer.php'; ?>

    <!-- BEGIN VENDOR JS-->
    <script src="../../../app-assets/vendors/js/vendors.min.js" type="text/javascript"></script>
    <!-- BEGIN VENDOR JS-->
    <!-- BEGIN PAGE VENDOR JS-->
    <script src="../../../app-assets/vendors/js/charts/chartist.min.js" type="text/javascript"></script>
    <script src="../../../app-assets/vendors/js/charts/chartist-plugin-tooltip.min.js" type="text/javascript"></script>
    <script src="../../../app-assets/vendors/js/timeline/horizontal-timeline.js" type="text/javascript"></script>
    <!-- END PAGE VENDOR JS-->
    <!-- BEGIN MODERN JS-->
    <script src="../../../app-assets/js/core/app-menu.js" type="text/javascript"></script>
    <script src="../../../app-assets/js/core/app.js" type="text/javascript"></script>
    <!-- END MODERN JS-->
    <!-- BEGIN PAGE LEVEL JS-->
    <script src="../../../app-assets/js/scripts/pages/dashboard-ico.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>