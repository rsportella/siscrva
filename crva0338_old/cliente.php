<?php
include("seguranca.php");
protegePagina();
global $_PS;
require_once('./funcoes.php');
if ($_GET['modal'] == 'buscacliente') {
    $result = $_PS['link']->prepare("SELECT `idcliente`, `nome`, `cpf` FROM `cliente` WHERE `cpf` LIKE '%$_REQUEST[valor]%' LIMIT 1");
    $result->execute();
    $clienteachado = $result->fetch(PDO::FETCH_ASSOC);

    if ($result->rowCount() != 0) {
        echo "$clienteachado[idcliente] - $clienteachado[nome]";
    } elseif ($result->rowCount() == 0) {
        echo "Not";
    }
    exit;
}
if (isset($_POST['gravar'])) {
    if (!empty($_POST['idcliente'])) {
        $sql = "UPDATE `cliente` "
                . " SET `nome` = '$_POST[nome]',`cpf` = '" . limpa($_POST[cpfcnpj]) . "', `telefone` = '$_POST[telefone]', `email` = '$_POST[email]', `nascimento` = '" . datausa($_POST[nascimento]) . "', `endereco` = '$_POST[rua]|$_POST[n]|$_POST[bairro]|$_POST[cep]', `cidade` = '$_POST[cidade]' "
                . " WHERE `idcliente` = $_POST[idcliente]";
        $update = $_PS['link']->prepare($sql);
        $update->execute();

        if ($update->rowCount() != 0) {
            $msg = array(
                msg => "Cliente atualizado com sucesso.",
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
    } elseif (empty($_POST['idcliente'])) {
        $sql = "INSERT INTO `cliente`(`nome`, `cpf`, `telefone`, `email`, `nascimento`, `endereco`, `cidade`) "
                . " VALUES ('$_POST[nome]', '" . limpa($_POST[cpfcnpj]) . "', '$_POST[telefone]', '$_POST[email]','" . datausa($_POST[nascimento]) . "', '$_POST[rua]|$_POST[n]|$_POST[bairro]', '$_POST[cidade]')";
        $insert = $_PS['link']->prepare($sql);
        $insert->execute();

        if ($insert->rowCount() != 0) {
            $msg = array(
                msg => "Entrada emitida com sucesso.",
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
            <li><a href="./cliente.php?modal=listar">Lista</a></li>
            <li class="active"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> cliente</li>
        </ol>
        <h2 class="text-center page-header"><?php echo ($_GET['modal'] == 'novo') ? 'Cadastrar' : 'Editar' ?> Cliente</h2>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form method="post">
                    <?php
                    $result = $_PS['link']->prepare("SELECT `idcliente`, `cliente`.`nome` AS 'cliente', `cidade`.`nome` AS 'cidade', `idestado`, `idcidade`, `estado`, `cpf`, `telefone`, `email`, `nascimento`, `endereco` FROM `cliente`,`estado`,`cidade` WHERE `cidade` = `idcidade` AND `estado` = `idestado` AND `idcliente` = $_GET[idcliente] LIMIT 1");
                    $result->execute();
                    $cliente = $result->fetch(PDO::FETCH_ASSOC);

                    $diretorio = "Vistoria\\Fotos\\";
                    if (file_exists($diretorio)) {
                        mkdir("nomedodiretorio", TRUE) or die("erro ao criar diretório");
                    }
                    ?>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Nome <small>* </small></span>
                                <input type="hidden" name="idcliente" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $_GET['idcliente'] ?>" />
                                <input type="text" class="form-control" name="nome" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $cliente['cliente'] ?>" required="required"/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <?php $cpfcnpj = explode(':', cpfcnpj($cliente['cpf'])); ?>
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">CPF/CNPJ</span>
                                <input type="text" class="form-control pessoa" name="cpfcnpj" />
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Nascimento </span>
                                <input type="text" class="form-control data" value="<?php echo ($_GET['modal'] == 'novo') ? '' : Sodata($cliente['nascimento']) ?>" name="nascimento" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Telefone <small>* </small></span>
                                <input type="text" class="form-control celular" name="telefone" value="<?php echo ($_GET['modal'] == 'novo') ? '' : limpa($cliente['telefone']) ?>" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Email </span>
                                <input type="email" class="form-control" value="<?php echo ($_GET['modal'] == 'novo') ? '' : $cliente['email'] ?>" name="email" />
                            </div>
                        </div>
                    </div>
                    <?php $endereco = explode('|', $cliente['endereco']) ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Rua </span>
                                <input type="text" class="form-control" value="<?php echo ($endereco[0]) ? $endereco[0] : '' ?>" name="rua" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Nº </span>
                                <input type="text" class="form-control" value="<?php echo ($endereco[1]) ? $endereco[1] : '' ?>" name="n" />
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Bairro </span>
                                <input type="text" class="form-control" value="<?php echo ($endereco[2]) ? $endereco[2] : '' ?>" name="bairro" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Estados</span>
                                <select class="form-control" name="estado" required="required">
                                    <option value=""> - - - </option>
                                    <?php
                                    $result = $_PS['link']->prepare("SELECT * FROM `estado` ORDER BY `nome`");
                                    $result->execute();
                                    while ($estado = $result->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='$estado[idestado]'";
                                        echo ($estado['idestado'] == $cliente['idestado']) ? 'selected' : ($estado['idestado'] == 21) ? 'selected' : '';
                                        echo ">$estado[nome]</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <?php if ($_GET['modal'] != 'novo') { ?>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Cidades</span>
                                    <label class="carregando" style="display:none;">Aguarde, carregando...</label>
                                    <select class="form-control" name="cidade" required="required">
                                        <option value=""> - - - </option>
                                        <?php
                                        $result = $_PS['link']->prepare("SELECT * FROM `cidade` ORDER BY `nome`");
                                        $result->execute();
                                        while ($cidade = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='$cidade[idcidade]'";
                                            echo ($cidade['idcidade'] == $cliente['idcidade']) ? 'selected' : '';
                                            echo ">$cidade[nome]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php } else { ?>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Cidades</span>
                                    <select class="form-control" name="cidade" required="required">
                                        <option value=""> - - - </option>
                                        <?php
                                        $result = $_PS['link']->prepare("SELECT * FROM `cidade` WHERE estado = 21 ORDER BY `nome`");
                                        $result->execute();
                                        while ($cidade = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='$cidade[idcidade]'";
                                            echo ($cidade['idcidade'] == $cidadeOrigemCrva) ? 'selected' : '';
                                            echo ">$cidade[nome]</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if ($_GET['modal'] == 'novo') { ?>
                        <button onclick="location = './cliente.php'" class="btn btn-primary btn-lg">Novo</button>
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
        <h2 class="text-center page-header">Lista Clientes</h2>
        <div class="row">
            <div class="col-md-6">
                <div class='panel panel-info'>
                    <div class='panel-body'>
                        <button onclick="location = './cliente.php?modal=novo'" class="btn btn-success btn-lg">Novo</button>
                        <!--<a href="./exPessoas.php" target="_blank" class="btn btn-primary btn-lg">Exportar</a>-->
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
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">Nome</span>
                                <input type="text" class="form-control original" name="nomeLocalizar" />
                            </div>
                            <div class="input-group input-group-lg">
                                <span class="input-group-addon">CPF/CNPJ</span>
                                <input type="text" class="form-control pessoa" name="cpfcnpjLocalizar" />
                            </div>
                        </div>

                        <div class='panel-footer text-right'>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <table class="table dataTable table-striped table-bordered no-footer">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Contato</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $_PS['link']->prepare("SELECT * FROM `cliente` WHERE `nome` LIKE '%$_POST[nomeLocalizar]%' AND `cpf` LIKE '%" . limpa($_POST[cpfcnpjLocalizar]) . "%' ORDER BY `idcliente` DESC LIMIT 50");
                    $result->execute();
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
                        echo "<td>$linha[idcliente]</td>";
                        echo "<td>$linha[nome]</td>";
                        echo "<td>$linha[email]" . ((!empty($linha[email]) & !empty($linha[telefone])) ? ' - ' : '') . "$linha[telefone]</td>";
                        echo "<td><a href='?modal=editar&idcliente=$linha[idcliente]' data-toggle='tooltip' data-placement='top' title='Editar'><i class='fa fa-pencil'></i></a></td>";
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

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
