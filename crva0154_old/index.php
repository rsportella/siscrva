<?php
include("seguranca.php");
protegePagina();
global $_PS;
require_once('./funcoes.php');
if (isset($_POST['gravar']) || isset($_POST['gravareimprimir']) || isset($_POST['gravareimprimireprotocolo']) && $_POST[idcliente] > 0) {

    $dataAtual = "'" . date('Y-m-d h:i:s') . "'";

    $insert = $_PS['link']->prepare("INSERT INTO `nota`(`cliente`, `funcionario`, `data`) VALUES ($_POST[idcliente], $_SESSION[funcionarioID], $dataAtual)");
    $insert->execute();

    $procura = $_PS['link']->prepare("SELECT `idnota` FROM `nota` WHERE `cliente` = $_POST[idcliente] AND `funcionario` = $_SESSION[funcionarioID] AND `data` = $dataAtual LIMIT 1");
    $procura->execute();
    $nota = $procura->fetch(PDO::FETCH_ASSOC);

    for ($i = 0; $i < count($_POST['servico']); $i++) {

        $servico = explode(':', $_POST['servico'][$i]);
        $servico['id'] = $servico[0];
        $servico['valor'] = $servico[1];

        $palca = strtoupper($_POST[placa][$i]);
        $cpfcnpj = limpa($_POST[cpfcnpj][$i]);
        $quantidade = $_POST[quantidade][$i];

        if ($servico['id'] == 31 && $quantidade > 9) {
            $valorServico = $valorDigitalizacao;
        } else {
            $valorServico = $servico['valor'] * $quantidade;
        }

        $tmp += $valorServico;

        $insert = $_PS['link']->prepare("INSERT INTO `notatemservicos`(`servicos`, `nota`, `placa`, `cpfcnpj`, `qnt`, `valor`) VALUES ($servico[id], $nota[idnota], '$palca', '$cpfcnpj', $quantidade, $servico[valor])");
        $insert->execute();
    }
    //atualizado 8/12/16
    $tmp = str_replace(",", ".", $tmp);
    //atualizado 8/12/16
    $update = $_PS['link']->prepare("UPDATE `nota` SET `valortotal` = $tmp WHERE `idnota` = $nota[idnota]");
    $update->execute();

    if ($insert->rowCount() != 0) {
        $msg = array(
            msg => "Entrada emitida com sucesso.",
            tipo => "success");
    } else {
        $msg = array(
            msg => "Houve algum erro nesta operação! Tente novamente mais tarde. Se persistir entre em contato com o desenvolvedor.",
            tipo => "danger");
    }
    if (isset($_POST['gravareimprimir'])) {
        echo "<script>window.open('cupom.php?parametros=" . $nota["idnota"] . "');</script>";
    }
    if (isset($_POST['gravareimprimireprotocolo'])) {
        echo "<script>window.open('cupom.php?parametros=" . $nota["idnota"] . "');</script>";
        echo "<script>window.open('protocolo.php?modal=novocupom&idcliente=" . $_POST[idcliente] . "');</script>";
    }
}
require_once 'header.php';
?>
<script type="text/javascript">
    $(function () {
        function removeCampo() {
            $(".removerCampo").unbind("click");
            $(".removerCampo").bind("click", function () {
                if ($(".linha").length > 1) {
                    $(this).parent().parent().remove();
                }
            });
        }

        var cloneCount = 1;
        $(".adicionarCampo").click(function () {
            novoCampo = $(".linha:first").clone(true, true);
            novoCampo.find("input, select").val("").removeClass("original").addClass('clone' + cloneCount++);
            novoCampo.find("input[name='placa[]']").removeClass("placa");
            novoCampo.find(".removerCampo").removeClass("hidden");
            novoCampo.find("input[name='placa[]']").mask("SSS0000");
            novoCampo.find("input[name='quantidade[]']").val('1');
            novoCampo.insertAfter(".linha:last");
            removeCampo();
        });
    });
</script>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="./index.php">Início</a></li>
        <li class="active">Frente de balcão</li>
    </ol>
    <h2 class="text-center page-header">Frente de balcão</h2><br>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form method="post">
                <div class="input-group input-group-lg">
                    <span class="input-group-addon">Data e Hora</span>
                    <input type="text" class="form-control" id="data" value="<?php echo date('d/m/Y - h:i a') ?>" disabled="true"/>
                </div>
                <div class="input-group input-group-lg">
                    <span class="input-group-addon">Clientes <small>* </small></span>
                    <input type="hidden" class="form-control" name="idcliente" />
                    <input type="text" class="form-control" name="cliente" required />
                </div>

                <div class="panel panel-default linha">
                    <div class="panel-heading">
                        <label>Serviço prestado</label><label class="removerCampo text-danger navbar-right hidden" style="margin-right: 10px"><i class="fa fa-2x fa-times-circle"></i></label>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Qnt. <small>* </small></span>
                                    <input type="number" class="form-control" name="quantidade[]" min="1" value="1"/>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Serviços <small>* </small></span>
                                    <select class="form-control" name="servico[]" required="required">
                                        <option value=""> - - - </option>
                                        <?php
                                        $result = $_PS['link']->prepare("SELECT * FROM `servicos` ORDER BY `titulo`");
                                        $result->execute();
                                        while ($servico = $result->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='$servico[idservicos]:$servico[valor]'>$servico[titulo] - R$ " . number_format($servico['valor'], 2, ',', '.') . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">Placa</span>
                                    <input type="text" class="form-control placa" name="placa[]" />
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon">CPF/CNPJ</span>
                                    <input type="text" class="form-control pessoa" name="cpfcnpj[]" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <button class="btn btn-primary btn-lg adicionarCampo" ><i class='fa fa-plus-circle'></i> Adicionar serviço</button><br><br>
                    <div class="btn-group" role="group" aria-label="...">
                        <button type="submit" class="btn btn-success btn-lg" name="gravar"><i class='fa fa-save'></i> Gravar</button>
                        <button type="submit" class="btn btn-success btn-lg" name="gravareimprimir"><i class='fa fa-print'></i>  Imprimir</button>
                        <button type="submit" class="btn btn-success btn-lg" name="gravareimprimireprotocolo"><i class='fa fa-book'></i> Protocolo</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<?php require_once './footer.php'; ?>