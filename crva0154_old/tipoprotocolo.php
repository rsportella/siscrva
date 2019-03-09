<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');

if (isset($_POST['gravar'])) {
    if (!empty($_POST['idtipoprotocolo'])) {
        $valor = str_replace(',', '.', $_POST['valor']);
        $update = $_PS['link']->prepare("UPDATE `tipoprotocolo` SET `titulo` = '$_POST[titulo]' WHERE `idtipoprotocolo` = $_POST[idtipoprotocolo]");
        $update->execute();

        if ($update->rowCount() != 0) {
            $msg = array(
                msg => "Serviço atualizado com sucesso.",
                tipo => "success");
        } elseif ($update->rowCount() == 0) {
            $msg = array(
                msg => "Nada para ser atualizado.",
                tipo => "warning");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    } elseif (empty($_POST['idtipoprotocolo'])) {
        $insert = $_PS['link']->prepare("INSERT INTO `tipoprotocolo` VALUES ('', '$_POST[titulo]')");
        $insert->execute();

        if ($insert->rowCount() != 0) {
            $msg = array(
                msg => "Serviço cadastrado com sucesso.",
                tipo => "success");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    }
}
require_once 'header.php';

if ($_GET['modal'] == 'novo' || $_GET['modal'] == 'editar') {
    ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="./index.php">Início</a></li>
            <li><a href="./tipoprotocolo.php?modal=listar">Lista</a></li>
            <li class="active"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> tipo protocolo</li>
        </ol>
        <h2 class="text-center page-header"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> tipo protocolo</h2><br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form method="post">
                    <?php
                    $result = $_PS['link']->prepare("SELECT * FROM `tipoprotocolo` WHERE `idtipoprotocolo` = $_GET[idtipoprotocolo] LIMIT 1");
                    $result->execute();
                    $servico = $result->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Título <small>* </small></span>
                        <input type="hidden" class="form-control" name="idtipoprotocolo" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $_GET['idtipoprotocolo'] ?>" />
                        <input type="text" class="form-control" name="titulo" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $servico['titulo'] ?>" required="required" />
                    </div>

                    <?php if ($_GET['modal'] == 'novo') { ?>
                        <button onclick="location = './tipoprotocolo.php'" class="btn btn-primary btn-lg">Novo</button>
                    <?php } ?>
                    <button type="submit" class="btn btn-success btn-lg" name="gravar">Gravar</button>
                </form>
            </div>
        </div>
    </div>
    <?php
} elseif ($_GET['modal'] == 'listar') {
    ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="index.php">Início</a></li>
            <li class="active">Lista</li>
        </ol>
        <h2 class="text-center page-header">Lista tipo protocolos</h2><br>
        <div class="row">
            <button onclick="location = './tipoprotocolo.php?modal=novo'" class="btn btn-success btn-lg">Novo</button><br><br>
            <table class="table dataTable table-striped table-bordered no-footer">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $_PS['link']->prepare("SELECT * FROM `tipoprotocolo` ORDER BY `titulo`");
                    $result->execute();
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo "<td>$linha[titulo]</td>";
                        echo "<td><a href='?modal=editar&idtipoprotocolo=$linha[idtipoprotocolo]'  data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil'></i></a></td>";
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
