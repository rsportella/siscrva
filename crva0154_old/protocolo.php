<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');
$modal = $_GET['modal'];

//dataDe
if ($_POST[dateDe]) {
    $dataDe = $_POST[dateDe];
} else {
    $dataDe = date('Y-m-01');
}
//dataPara
if ($_POST[datePara]) {
    $dataPara = $_POST[datePara];
} else {
    $dataPara = date('Y-m-t');
}
if (isset($_POST['protocoloprint'])) {
    echo "<script>window.open('protocoloPrint.php?inicio=" . $_POST["inicio"] . "&fim=" . $_POST["fim"] . "');</script>";
}
if ($modal == "excluir") {
    $deletar = $_PS['link']->prepare("DELETE FROM `protocolo` WHERE `idprotocolo` = $_GET[idprotocolo]");
    $deletar->execute();
    if ($deletar->rowCount() != 0) {
        $msg = array(
            msg => "Protocolo excluído com sucesso.",
            tipo => "success");
        $modal = "listar";
    } elseif ($deletar->rowCount() == 0) {
        $msg = array(
            msg => "Protocolo não encontrado no sistema.",
            tipo => "warning");
    } else {
        $msg = array(
            msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
            tipo => "danger");
    }
} elseif (isset($_POST['gravar'])) {
    if (!empty($_GET['idprotocolo'])) {
        $update = $_PS['link']->prepare("UPDATE `protocolo` SET `processo` = '$_POST[processo]', `tipoprotocolo` = '$_POST[tipoprotocolo]', `cliente` = '$_POST[idcliente]', `datainicio` = '" . datausa($_POST[datainicio]) . "', `datafechamento` = '" . datausa($_POST[datafechamento]) . "', `placa` = '$_POST[placa]', `pendencia` = '$_POST[pendencia]' WHERE `idprotocolo` = $_GET[idprotocolo]");
        $update->execute();

        if ($update->rowCount() != 0) {
            $msg = array(
                msg => "Protocolo atualizado com sucesso.",
                tipo => "success");
            $modal = "listar";
        } elseif ($update->rowCount() == 0) {
            $msg = array(
                msg => "Nada para ser atualizado.",
                tipo => "warning");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    } elseif (empty($_POST['idprotocolo'])) {
        $insert = $_PS['link']->prepare("INSERT INTO `protocolo`(`processo`, `tipoprotocolo`, `cliente`, `datainicio`, `datafechamento`, `placa`, `pendencia`, `funcionario`) VALUES "
                . "('$_POST[processo]', $_POST[tipoprotocolo],$_POST[idcliente],'" . datausa($_POST[datainicio]) . "','" . datausa($_POST['datafechamento']) . "','$_POST[placa]','$_POST[pendencia]',$_SESSION[funcionarioID])");
        $insert->execute();

        if ($insert->rowCount() != 0) {
            $msg = array(
                msg => "Protocolo cadastrado com sucesso.",
                tipo => "success");
            $modal = "listar";
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    }
}
require_once 'header.php';

if ($modal == 'novo' || $modal == 'editar' || $modal == 'novocupom') {
    ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="./index.php">Início</a></li>
            <li><a href="./protocolo.php?modal=listar">Lista</a></li>
            <li class="active"><?php echo ($modal == 'novo') ? 'Cadastrar' : 'Editar' ?> protocolo</li>
        </ol>
        <h2 class="text-center page-header"><?php echo ($modal == 'novo') ? 'Cadastrar' : 'Editar' ?> protocolo</h2><br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="post">
                    <?php
                    if ($modal == 'novocupom') {
                        $resultt = $_PS['link']->prepare("SELECT * FROM `cliente` WHERE `cliente`.`idcliente` = $_GET[idcliente] LIMIT 1");
                        $resultt->execute();
                        $cliente = $resultt->fetch(PDO::FETCH_ASSOC);
                    } elseif ($modal == 'editar') {
                        $result = $_PS['link']->prepare("SELECT * FROM `protocolo`, `cliente` WHERE `cliente` = `idcliente` AND `idprotocolo` = $_GET[idprotocolo] LIMIT 1");
                    }
                    if ($modal != 'editar') {
                        $result = $_PS['link']->prepare("SELECT `idprotocolo` FROM `protocolo` ORDER BY `idprotocolo` DESC LIMIT 1");
                    }
                    $result->execute();
                    $protocolo = $result->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Clientes <small>* </small></span>
                                <input type="hidden" class="form-control" name="idcliente" value="<?php echo ($modal != 'novo') ? $protocolo['idcliente'] . $cliente['idcliente'] : '' ?>" />
                                <input type="text" class="form-control" name="cliente" value="<?php echo ($modal != 'novo') ? $protocolo['nome'] . $cliente['nome'] : '' ?>" <?php echo ($modal != 'novo') ? 'disabled="true"' : '' ?> />
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Placa <small>* </small></span>
                                <input type="text" class="form-control placa" name="placa" value="<?php echo ($modal != 'novo') ? $protocolo['placa'] : '' ?>" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Protocolo <small>* </small></span>
                                <input type="text" class="form-control" name="protocolo" value="<?php echo ($modal == 'editar') ? $protocolo['idprotocolo'] : $protocolo['idprotocolo'] + 1 ?>" disabled="true" required="required" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Processo <small>* </small></span>
                                <input type="text" class="form-control" name="processo" value="<?php echo ($modal == 'editar') ? $protocolo['processo'] : "" ?>" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Tipo protocolo <small>* </small></span>
                                <select class="form-control" name="tipoprotocolo" required="required">
                                    <option value=""> - - - </option>
                                    <?php
                                    $result = $_PS['link']->prepare("SELECT * FROM `tipoprotocolo` ORDER BY `titulo` ASC");
                                    $result->execute();
                                    while ($tipoprotocolo = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='$tipoprotocolo[idtipoprotocolo]' ";
                                        echo ($tipoprotocolo[idtipoprotocolo] == $protocolo[tipoprotocolo]) ? 'selected' : '';
                                        echo">$tipoprotocolo[titulo]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Data inicio <small>* </small></span>
                                <input type="d" class="form-control data" name="datainicio" value="<?php echo ($modal == 'editar') ? Sodata($protocolo['datainicio']) : date('d/m/Y') ?>" required="required" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Data fechamento <small>* </small></span>
                                <input type="text" class="form-control data" name="datafechamento" value="<?php echo ($modal == 'editar') ? Sodata($protocolo['datafechamento']) : date('d/m/Y') ?>" required="required" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Pendências</span>
                                <textarea class="form-control" name="pendencia"><?php echo ($modal != 'novo') ? $protocolo['pendencia'] : "" ?></textarea>
                            </div>
                        </div>
                    </div>

                    <?php if ($modal == 'novo') { ?>
                        <button onclick="location = './protocolo.php'" class="btn btn-primary btn-lg">Novo</button>
                    <?php } ?>
                    <button type="submit" class="btn btn-success btn-lg" name="gravar">Gravar</button>
                </form>
            </div>
        </div>
    </div>
    <?php
} elseif ($modal == 'listar') {
    ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php">Início</a></li>
            <li class="active">Lista</li>
        </ol>
        <h2 class="text-center page-header">Lista protocolos</h2><br>
        <div class="row">
            <div class="col-md-6">
                <div class='panel panel-info'>
                    <div class='panel-body'>
                        <button class="btn btn-success btn-lg" onclick="location = '?modal=novo'">Novo</button><br><br><br>
                        <form method="post">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Imprimir</span>
                                <input type="text" class="form-control" name="inicio" />
                                <span class="input-group-addon">Até</span>
                                <input type="text" class="form-control" name="fim" />
                                <span class="input-group-btn">
                                    <button class="btn btn-success" name="protocoloprint"><i class="fa fa-print"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <form method="post">
                    <div class='panel panel-primary'>
                        <div class='panel-heading'>
                            Filtros
                        </div>
                        <div class='panel-body'>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>De </label>
                                    <input type="date" name="dateDe" class="form-control" value="<?php echo $dataDe ?>" autofocus="autofocus" />
                                </div>
                                <div class="col-md-6">
                                    <label> Até </label>
                                    <input type="date" name="datePara" class="form-control" value="<?php echo $dataPara ?>" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Processo </label>
                                    <input type="text" name="processo" class="form-control" value="<?php echo (isset($_POST['processo'])) ? $_POST['processo'] : '' ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label>Protocolo </label>
                                    <input type="text" name="protocolo" class="form-control" value="<?php echo (isset($_POST['protocolo'])) ? $_POST['protocolo'] : '' ?>" />
                                </div>
                                <div class="col-md-4">
                                    <label>Placa </label>
                                    <input type="text" name="placa" class="form-control" value="<?php echo (isset($_POST['placa'])) ? $_POST['placa'] : '' ?>" />
                                </div>
                            </div>
                        </div>
                        <div class='panel-footer text-right'>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <table class="table dataTable table-striped table-bordered no-footer">
            <thead>
                <tr>
                    <th>Protocolo</th>
                    <th>Processo</th>
                    <th>Placa</th>
                    <th>Data inicio</th>
                    <th>Data fechamento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $_PS['link']->prepare("SELECT `idprotocolo`, `processo`, `tipoprotocolo`, `cliente`, `datainicio`, `datafechamento`, `placa`, `pendencia`, `funcionario` FROM `protocolo` WHERE `datainicio` >= '$dataDe' AND `datainicio` <= '$dataPara' AND `placa` LIKE '%$_POST[placa]%' AND `processo` LIKE '%$_POST[processo]%' AND `idprotocolo` LIKE '%$_POST[protocolo]%' ORDER BY `idprotocolo` DESC");
                $result->execute();
                while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo "<td>$linha[idprotocolo]</td>";
                    echo "<td>$linha[processo]</td>";
                    echo "<td>$linha[placa]</td>";
                    echo "<td>" . Sodata($linha[datainicio]) . "</td>";
                    echo "<td>" . Sodata($linha[datafechamento]) . "</td>";
                    echo "<td><a href='?modal=editar&idprotocolo=$linha[idprotocolo]' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil'></i></a>"
                    . "<a href='?modal=excluir&idprotocolo=$linha[idprotocolo]' data-toggle='tooltip' data-placement='top' title='Excluir'><i class='fa fa-trash'></i></a></td>";
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <br>
    </div>
    <?php
} else {
    ?>
    <div class="alert alert-info container text-center" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <strong>Aviso!</strong> Você não selecionou nenhum modal para essa categoria.
    </div>
    <?php
}

require_once './footer.php';
