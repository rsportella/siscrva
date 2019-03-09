<?php
include("seguranca.php");
protegePagina();
global $_PS;
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRVA Admin - Sistema de controle</title>

        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/style.css">

    </head>
    <?php
    $ultimoPonto = $_PS['link']->prepare("SELECT `data`, `hora` FROM `bateponto` WHERE `funcionario` = " . $_SESSION['usuario_codigo'] . " ORDER BY `idbatePonto` DESC LIMIT 1");
    $ultimoPonto->execute();
    $infoPonto = $ultimoPonto->fetch(PDO::FETCH_ASSOC);

//verifica o ultimo ponto do funcionario mais 15min é menor ou igual a data/hora no momento
    $dataHora = $infoPonto['data'] . " " . $infoPonto['hora'];
    if (date('Y-m-d H:i:s') >= date('Y-m-d H:i:s', strtotime('+15 minute', strtotime($dataHora)))) {
        $baterPonto = $_PS['link']->prepare("INSERT INTO `bateponto` (`funcionario`, `data`, `hora`) VALUES ('" . $_SESSION['usuario_codigo'] . "', '" . date('Y-m-d') . "', '" . date('H:i:s') . "')");
        $baterPonto->execute();
        if ($baterPonto->rowCount() != 0) {
            $msg = array(
                msg => "Ponto inserido com sucesso às <strong>" . date('d/m/Y H:i:s') . "</strong>.",
                tipo => "success");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    } else {
        $msg = array(
            msg => "<strong>$_SESSION[funcionarioNome]</strong> seu ponto já foi batido.</h3><br> Seu ultimo acesso foi a <strong>" . date('d/m/Y', strtotime($infoPonto['data'])) . ' - ' . $infoPonto['hora'] . "</strong> se algo de anormal está acontecendo entre em contato com o administrador.",
            tipo => "danger");
    }
    ?>
    <body class="alert alert-<?php echo $msg['tipo'] ?> text-center" role="alert">
        </br></br> <img src="img/ctpa/<?php echo $_SESSION['usuario_cpf'] ?>.jpg" class="img-thumbnail" width="200" /></br></br></br>
        <h3><strong>Aviso!</strong> <?php echo $msg['msg'] ?></h3>
    </body>
</html>


