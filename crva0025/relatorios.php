<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');
echo '<link rel="stylesheet" type="text/css" href="./css/print.css" media="print" />';
require_once 'header.php';

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
//serviço
if ($_POST[postServico]) {
    $servico = "AND `servicos`.`idservicos` = $_POST[postServico]";
} else {
    $servico = '';
}
?>

<div class="container">
    <ol class="breadcrumb">
        <li><a href="index.php">Início</a></li>
        <li class="active">Relatório</li>
        <li class="active">Serviços</li>
    </ol>
    <h2 class="text-center page-header">Serviços<br><small>Fluxo de caixa</small></h2>
    <div class="row">
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
                                <input type="date" name="dateDe" id="dateDe" class="form-control" value="<?php echo $dataDe ?>" autofocus="autofocus" />
                            </div>
                            <div class="col-md-6">
                                <label> Até </label>
                                <input type="date" name="datePara" id="datePara" class="form-control" value="<?php echo $dataPara ?>" />
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
        <table class="table  table-striped table-bordered no-footer">
            <thead>
                <tr>
                    <th>Serviço</th>
                    <th>Quantia</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sqlquery = "SELECT servicos.idservicos, servicos.titulo FROM servicos";
                $resultServicos = $_PS['link']->prepare($sqlquery);
                $resultServicos->execute();
                while ($servico = $resultServicos->fetch(PDO::FETCH_ASSOC)) {
                    $sqlquery = "SELECT 
                                        *
                                    FROM
                                        nota,
                                        notatemservicos
                                    WHERE
                                        nota.idnota = notatemservicos.nota
                                            AND notatemservicos.servicos = $servico[idservicos]
                                            AND `data` >= '$dataDe 00:00:00'
                                            AND `data` <= '$dataPara 23:59:59'";
                    $result = $_PS['link']->prepare($sqlquery);
                    $result->execute();
                    $valorItem = $countQnt = 0;
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        $achou++;
                        if ($servico['idservicos'] == 31 && $linha['qnt'] > 9) {
                            $valorServico = $valorDigitalizacao;
                            $valorItem += $valorServico;
                        } else {
                            $valorServico = $linha['valor'] * $linha['qnt'];
                            $valorItem += $valorServico;
                        }
                        $countQnt += $linha['qnt'];
                    }
                    if ($countQnt != 0) {
                        echo '<tr>';
                        echo "<td>$servico[titulo]<br>";
                        echo "<td>$countQnt</td>";
                        echo "<td>R$ " . number_format(($valorItem), 2, ',', '.') . "</td>";
                        $cont += $valorItem;
                        echo '</tr>';
                    }
                }
                if ($achou == 0) {
                    echo "<tr><td colspan='3'>Período sem movimentação no CRVA.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right">Valor total R$ <label class="total"><?php echo number_format($cont, 2, ',', '.') ?></label></td>
                </tr>
            </tfoot>
        </table>
        <a href="rel_finan_men.php?inicio=<?php echo $dataDe ?>&termino=<?php echo $dataPara ?>" class="btn btn-primary" target="_blank">Imprimir relatório </a>
    </div>
</div>
<?php require_once './footer.php'; ?>