<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');
$modal = $_GET['modal'];

echo '<link rel="stylesheet" type="text/css" href="./css/print.css" media="print" />';

if ($_GET['modal'] == "excluir") {
    $deletar = $_PS['link']->prepare("DELETE FROM `notatemservicos` WHERE `nota` = $_GET[nota]");
    $deletar->execute();
    $deletar = $_PS['link']->prepare("DELETE FROM `nota` WHERE `idnota` = $_GET[nota]");
    $deletar->execute();

    if ($deletar->rowCount() != 0) {
        $msg = array(
            msg => "Nota excluida com sucesso.",
            tipo => "success");
    } elseif ($deletar->rowCount() == 0) {
        $msg = array(
            msg => "Nota não encontrada no sistema.",
            tipo => "warning");
    } else {
        $msg = array(
            msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
            tipo => "danger");
    }
}



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
//cliente
if (!empty($_POST[spessoa])) {
    $cliente = " AND cliente.cpf like '%" . limpa($_POST[spessoa]) . "%' ";
}
//funcionario

if ($_POST[sfuncionarios] != 0) {
    $sfuncionario = " AND funcionario.idfuncionario = $_POST[sfuncionarios] ";
}
//nota
if (!empty($_POST[snnota])) {
    $nota = " AND nota.idnota = $_POST[snnota] ";
}
require_once 'header.php';
?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="index.php">Início</a></li>
        <li class="active">Relatório</li>
        <li class="active">Notas</li>
    </ol>
    <h2 class="text-center page-header">Notas<br><small>Fluxo de caixa</small></h2>
    <div class="row">
        <div class="col-md-6">
            <form method="post">
                <div class='panel panel-primary'>
                    <div class='panel-heading'>
                        Filtros
                    </div>
                    <div class='panel-body'>
                        <div class="row">
                            <div class="col-md-4">
                                <label> Nº Nota </label>
                                <input type="text" name="snnota" id="nnota" class="form-control" value="" />
                            </div>
                            <div class="col-md-4">
                                <label>De </label>
                                <input type="date" name="dateDe" id="dateDe" class="form-control" value="<?php echo $dataDe ?>" autofocus="autofocus" />
                            </div>
                            <div class="col-md-4">
                                <label> Até </label>
                                <input type="date" name="datePara" id="datePara" class="form-control" value="<?php echo $dataPara ?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Cliente</label>
                                <input type="text" class="form-control pessoa" name="spessoa" />
                            </div>
                            <div class="col-md-6">
                                <label>Funcionarios</label>
                                <select class="form-control" name="sfuncionarios">
                                    <option value="0">Todos</option>
                                    <?php
                                    $funcionarios = $_PS['link']->prepare("SELECT * FROM `funcionario`");
                                    $funcionarios->execute();
                                    while ($funcionario = $funcionarios->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='$funcionario[idfuncionario]'>$funcionario[nome]</option>";
                                    }
                                    ?>
                                </select>
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
    <div class="row">
        <table class="table dataTable table-striped table-bordered no-footer">
            <thead>
                <tr>
                    <th>Nota</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Colaborador</th>
                    <th>Valor Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT cliente.nome AS cliente,funcionario.nome AS funcionario, nota.idnota,nota.data, nota.valortotal "
                        . "FROM nota, cliente, funcionario "
                        . "WHERE nota.cliente = cliente.idcliente "
                        . "AND nota.funcionario = funcionario.idfuncionario "
                        . "AND nota.data BETWEEN '$dataDe 00:00:00' AND '$dataPara 23:59:59' "
                        . "$nota "
                        . "$sfuncionario "
                        . "$cliente "
                        . "ORDER BY nota.idnota ASC";
                $result = $_PS['link']->prepare($sql);
                $result->execute();
                while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<tr>';
                    echo "<input id='nota' type='hidden' value='$linha[idnota]'>";
                    echo "<td>$linha[idnota]</td>";
                    echo "<td>" . DataHora($linha[data]) . "</td>";
                    echo "<td>$linha[cliente]</td>";
                    echo "<td>$linha[funcionario]</td>";
                    echo "<td>R$ $linha[valortotal]</td>";
                    echo "<td><a class='gerarnota' href='#' data-toggle='tooltip' data-placement='top' title='Reimprimir'><i class='fa fa-print'></i></a>"
                    . "<a class='excluir' href='?modal=excluir&nota=$linha[idnota]' data-toggle='tooltip' data-placement='top' title='Excluir'><i class='fa fa-trash'></i></a></td>";
                    echo '</tr>';
                    $cont += $linha[valortotal];
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right">Valor total R$ <?php echo number_format($cont, 2, ',', '.') ?></td></tr></tfoot>
        </table>

    </div>
</div>


<?php require_once './footer.php'; ?>