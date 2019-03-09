<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');
if (isset($_POST['gravar'])) {
    if ($_POST[senha] == $_POST[confirmasenha]) {
        $senha = md5($_POST[senha]);
        $insert = $_PS['link']->prepare("INSERT INTO `funcionario` VALUES ('', '$_POST[nome]', '$_POST[cpf]', '$_POST[nascimento]',  '$_POST[telefone]', '$_POST[email]', '$senha')");
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
    } else {
        $msg = array(
            msg => "Senhas não confere tente novamente.",
            tipo => "warning");
    }
}
require_once 'header.php';

if ($_GET['modal'] == 'novo') {
    ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="./index.php">Início</a></li>
            <li><a href="./funcionario.php?modal=listar">Listar</a></li>
            <li class="active">Cadastro de funcionário</li>
        </ol>
        <h2 class="text-center page-header">Cadastro de Funcionário</h2>
        <div class="col-md-10 col-md-offset-1">
            <form method="post">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Nome <small>* </small></span>
                            <input type="text" class="form-control" name="nome" required="required"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">CPF <small>* </small></span>
                            <input type="text" class="form-control cpf" name="cpf" placeholder="000.000.000-00" required="required"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Nascimento <small>* </small></span>
                            <input type="text" class="form-control data" name="nascimento" placeholder="11/02/1991" required="required"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Telefone <small>* </small></span>
                            <input type="text" class="form-control celular" name="telefone" required="required"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Email <small>* </small></span>
                            <input type="email" class="form-control" name="email" required="required"/>
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Senha <small>* </small></span>
                            <input type="password" class="form-control" name="senha" required="required" />
                        </div>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon">Confirme a senha <small>* </small></span>
                            <input type="password" class="form-control" name="confirmasenha" required="required"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button onclick="location = './funcionario.php?modal=novo'" class="btn btn-primary btn-lg">Novo</button>
                    <button type="submit" class="btn btn-success btn-lg" name="gravar">Gravar</button>
                </div>
            </form>
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
        <h2 class="text-center page-header">Lista Funcionários</h2>
        <div class="row">
            <button onclick="location = './funcionario.php?modal=novo'" class="btn btn-success btn-lg">Novo</button>
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
                    $result = $_PS['link']->prepare("SELECT * FROM `funcionario` ORDER BY `nome`");
                    $result->execute();
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        echo '<tr>';
//                        echo "<td>" . (($_SESSION['funcionarioID'] == 1) ? "<a href='pontos.php?funcionario=$linha[idfuncionario]'>Pontos</a>" : '') . "</td>";
                        echo "<td>$linha[idfuncionario]</td>";
                        echo "<td>$linha[nome]</td>";
                        echo "<td>$linha[email]" . ((!empty($linha[email]) & !empty($linha[telefone])) ? ' - ' : '') . "$linha[telefone]</td>";
                        echo "<td><a href='pontos.php?funcionario=$linha[idfuncionario]' data-toggle='tooltip' data-placement='top' title='Pontos'><i class='fa fa-book'></i></a></td>";
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
