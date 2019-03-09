<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');

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

if (!empty($_GET['action'])) {
    if ($_GET['action'] == 'novo') {
        $insert = $_PS['link']->prepare("INSERT INTO `bateponto`(`funcionario`, `data`, `hora`) "
                . "VALUES ($_GET[funcionario],'$_POST[data]','$_POST[hora]')");
        $insert->execute();

        if ($insert->rowCount() != 0) {
            $msg = array(
                msg => "Ponto inserido com sucesso.",
                tipo => "success");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    } elseif ($_GET['action'] == 'editar') {
        $update = $_PS['link']->prepare("UPDATE `bateponto` SET `data`='$_POST[data]',`hora`='$_POST[hora]' WHERE `idbateponto` = $_GET[idbateponto]");
        $update->execute();

        if ($update->rowCount() != 0) {
            $msg = array(
                msg => "Ponto atualizado com sucesso.",
                tipo => "success");
        } else {
            $msg = array(
                msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
                tipo => "danger");
        }
    }
}
if (isset($_GET['excluir']) && isset($_GET['funcionario'])) {
    $deletar = $_PS['link']->prepare("DELETE FROM `batePonto` WHERE `idbatePonto` = $_GET[excluir]");
    $deletar->execute();

    if ($deletar->rowCount() != 0) {
        $msg = array(
            msg => "Ponto excluído com sucesso.",
            tipo => "success");
    } elseif ($deletar->rowCount() == 0) {
        $msg = array(
            msg => "Ponto não encontrado no sistema.",
            tipo => "warning");
    } else {
        $msg = array(
            msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
            tipo => "danger");
    }
    header("Location: ?funcionario=$_GET[funcionario]");
}
require_once './header.php';
?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="./index.php">Início</a></li>
        <li><a href="./funcionario.php?modal=listar">Listar</a></li>
        <li class="active">Horários de ponto</li>
    </ol>
    <h2 class="text-center page-header">Horários de Funcionário</h2>
    <div class="row">
        <div class="col-md-5">
            <div class="row">
                <div class='panel panel-warning'>
                    <?php
                    $result = $_PS['link']->prepare("SELECT `nome`, `nascimento`, `cpf`, `telefone`, `email` FROM `funcionario` WHERE `idfuncionario` = $_GET[funcionario] LIMIT 1");
                    $result->execute();
                    $funcionario = $result->fetch(PDO::FETCH_ASSOC);

                    $results = $_PS['link']->prepare("SELECT * FROM `bateponto` WHERE `funcionario` = $_GET[funcionario] AND `data` >= '$dataDe' AND `data` <= '$dataPara'");
                    $results->execute();
                    ?>
                    <div class='panel-heading'>
                        <?php echo $funcionario[nome]; ?>
                    </div>
                    <div class='panel-body'>
                        <div class="col-md-6">
                            <img src="<?php echo './img/ctpa/' . $funcionario[cpf] . '.jpg'; ?>" alt="<?php echo $funcionario[nome]; ?>" width="100"/>
                        </div>
                        <div class="col-md-6">
                            <p><?php echo sodata($funcionario[nascimento]); ?></p>
                            <p><?php echo $funcionario[telefone]; ?></p>
                            <p><?php echo $funcionario[email]; ?></p>
                            <p><?php echo $total; ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <form action="?funcionario=<?php echo $_GET[funcionario] ?>" method="post">
                    <div class='panel panel-primary'>
                        <div class='panel-heading'>
                            Filtros
                        </div>
                        <div class='panel-body'>
                            <div class="row">
                                <div class="col-md-6">
                                    <label>De </label>
                                    <input type="date" name="dateDe" id="dateDe" class="form-control" value="<?php echo $dataDe ?>" autofocus="autofocus" />
                                </div>
                                <div class="col-md-6">
                                    <label> Até </label>
                                    <input type="date" name="datePara" id="datePara" class="form-control" value="<?php echo $dataPara ?>" />
                                </div>
                            </div>
                            <br/>
                        </div>
                        <div class='panel-footer text-right'>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        <div class="col-md-7">
            <?php
            if (isset($_GET['editar']) && isset($_GET['funcionario'])) {
                $procuras = $_PS['link']->prepare("SELECT * FROM `bateponto` WHERE `funcionario` = $_GET[funcionario] AND `idbateponto` = $_GET[editar]");
                $procuras->execute();
                $pontos = $procuras->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="col-md-9 col-md-offset-2">
                    <h2 class="text-center page-header">Editar</h2>
                    <form method="post" action="?funcionario=<?php echo $_GET[funcionario] ?>&action=editar&idbateponto=<?php echo $_GET[editar] ?>">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Data</span>
                            <input type="date" class="form-control" name="data" value="<?php echo $pontos['data'] ?>"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Hora</span>
                            <input type="time" class="form-control" name="hora" value="<?php echo $pontos['hora'] ?>"/>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg" name="gravar">Gravar</button>
                    </form>
                </div>

            <?php }if (isset($_GET['novo']) && isset($_GET['funcionario'])) {
                ?>
                <div class="col-md-9 col-md-offset-2">
                    <h2 class="text-center page-header">Novo</h2>
                    <form method="post" action="?funcionario=<?php echo $_GET[funcionario] ?>&action=novo">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Data</span>
                            <input type="date" class="form-control" name="data" value="<?php echo date('Y-m-d') ?>"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Hora</span>
                            <input type="time" class="form-control" name="hora" value="<?php echo date('H:i:s') ?>"/>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg">Gravar</button>
                    </form>
                </div>

                <?php
            }

            if (isset($_GET['funcionario']) && !isset($_GET['excluir']) && !isset($_GET['editar']) && !isset($_GET['novo'])) {
                ?>
                <a href="?funcionario=<?php echo $_GET[funcionario] ?>&novo" class="btn btn-lg btn-primary">Novo Ponto</a>
                <a href="rel_freq_men.php?funcionario=<?php echo $_GET[funcionario] ?>&inicio=<?php echo $dataDe ?>&termino=<?php echo $dataPara ?>" class="btn btn-lg btn-primary" target="_blank">Relatório mensal</a>
                <table class="table dataTable table-striped table-bordered no-footer">
                    <thead>
                        <tr>
                            <th>Ações</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Obs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($linha = $results->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr>';
                            echo "<td>"
                            . "<a href='?funcionario=$_GET[funcionario]&editar=$linha[idbateponto]'><i class='fa fa-pencil-square-o fa-lg'></i></a>"
                            . "<a href='?funcionario=$_GET[funcionario]&excluir=$linha[idbateponto]'><i class='fa fa-trash-o fa-lg'></i></a>"
                            . "</td>";
                            echo "<td>" . date('d/m/Y', strtotime($linha[data])) . "</td>";
                            echo "<td>$linha[hora]</td>";
                            echo "<td>$linha[observacao]</td>";
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            <?php } ?>
        </div>
    </div>
</div>



<?php
require_once './footer.php';
