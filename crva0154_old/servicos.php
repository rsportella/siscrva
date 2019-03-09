<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');

if (isset($_POST['gravar'])) {
    if (!empty($_POST['idservicos'])) {
        $valor = str_replace(',', '.', $_POST['valor']);
        $update = $_PS['link']->prepare("UPDATE `servicos` SET `titulo` = '$_POST[titulo]',`valor` = '$valor',`descricao` = '$_POST[descricao]' WHERE `idservicos` = $_POST[idservicos]");
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
    } elseif (empty($_POST['idservicos'])) {
        $insert = $_PS['link']->prepare("INSERT INTO `servicos` VALUES ('', '$_POST[titulo]', '$_POST[valor]', '$_POST[descricao]')");
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
            <li><a href="./servicos.php?modal=listar">Lista</a></li>
            <li class="active"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> serviço</li>
        </ol>
        <h2 class="text-center page-header"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> serviços</h2><br>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form method="post">
                    <?php
                    $result = $_PS['link']->prepare("SELECT * FROM `servicos` WHERE `idservicos` = $_GET[idservicos] LIMIT 1");
                    $result->execute();
                    $servico = $result->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Título <small>* </small></span>
                        <input type="hidden" class="form-control" name="idservicos" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $_GET['idservicos'] ?>" />
                        <input type="text" class="form-control" name="titulo" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $servico['titulo'] ?>" required="required" />
                    </div><br>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Valor <small> * (R$)</small></span>
                        <input type="text" class="form-control valor" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $servico['valor'] ?>" name="valor" required="required" />
                    </div><br>
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Descrição </span>
                        <textarea type="text" class="form-control" name="descricao" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $servico['descricao'] ?>" ></textarea>
                    </div><br>

                    <?php if ($_GET['modal'] == 'novo') { ?>
                        <button onclick="location = './servicos.php'" class="btn btn-primary btn-lg">Novo</button>
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
        <h2 class="text-center page-header">Lista servicos</h2><br>
        <div class="row">
            <button onclick="location = './servicos.php?modal=novo'" class="btn btn-success btn-lg">Novo</button><br><br>
            <table class="table dataTable table-striped table-bordered no-footer">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Valor</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $_PS['link']->prepare("SELECT * FROM `servicos` ORDER BY `titulo`");
                    $result->execute();
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo "<td>$linha[titulo]</td>";
                        echo "<td>R$ " . number_format($linha['valor'], 2, ',', '.') . "</td>";
                        echo "<td><a href='?modal=editar&idservicos=$linha[idservicos]' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil'></i></a></td>";
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
