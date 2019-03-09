<?php
include("seguranca.php");
protegePagina();
global $_PS;
include './funcoes.php';

if (isset($_GET['parametros'])) {
    $procura = $_PS['link']->prepare("SELECT `idnota`, `cliente`.`nome` AS 'cliente', `cliente`.`cpf` AS 'cpf', `funcionario`.`nome` AS 'funcionario', `valortotal` FROM `nota`, `cliente`, `funcionario` WHERE `cliente` = `idcliente` AND `funcionario` = `idfuncionario` AND `idnota` = $_GET[parametros] LIMIT 1");
    $procura->execute();
    $nota = $procura->fetch(PDO::FETCH_ASSOC);
} else {
    
}
?>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <style>
            *{
                margin: 0;
                padding: 0;
            }
            body{
                font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            }
            #cupom{
                width: 370px;
                text-align: center;
            }
            p{
                margin: 4px;
            }
            table{
                width: 100%;
                text-align: left;
            }
            table tfoot{
                text-align: right;
                font-size: 150%;
                font-weight: bold;
            }
            table tfoot label{
                font-size: 70%;
            }
            p#title{
                font-size: 200%;
            }
            hr{
                margin: 10px 0;
                border: solid 1px black;
            }
            #detalhes{
                text-align: left;
                font-size: 80%;
            }

        </style>
    </head>
    <body onload="self.print();">
        <div id="cupom">
            <?php
            $ponteiro = fopen("./crva", "r");
            while (!feof($ponteiro)) {
                $linha = fgets($ponteiro, 4096);
                echo $linha;
            }
            fclose($ponteiro);
            ?>
            <hr>
            <p><?php echo $cidade. ' ' . date('d') . ' de ' . $meses[date('n')] . ' de ' . date('Y') . ' às ' . date('H:i:s') . '.' ?></p>
            <hr>
            <p>CUPOM NÃO FISCAL Nº <?php echo $nota['idnota'] ?></p>
            <hr>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>COD.</th>
                            <th>QNT.</th>
                            <th>ITEM</th>
                            <th>UNT.</th>
                            <th>TL.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $buscaServico = $_PS['link']->prepare("SELECT `servicos`, `titulo`, `qnt`, `notatemservicos`.`valor` AS 'valor', `placa`, `cpfcnpj` FROM `servicos`, `notatemservicos` WHERE `servicos` = `idservicos` AND `nota` = $nota[idnota]");
                        $buscaServico->execute();
                        while ($servico = $buscaServico->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>
                                    <td style='vertical-align: top'>$servico[servicos]</td>
                                    <td>$servico[qnt]</td>
                                    <td style='width: 67%'>$servico[titulo]<br>" . '<p style="font-size:12px">';
                            echo (!empty($servico[placa])) ? $servico[placa] : '';
                            echo (!empty($servico[cpfcnpj])) ? ' | ' . $servico[cpfcnpj] : '';
                            echo "</p></td><td>" . number_format($servico['valor'], 2, ',', '.') . "</td>";
                            if ($servico[servicos] == 31 && $servico[qnt] > 9) {
                                $valorServico = $valorDigitalizacao;
                            } else {
                                $valorServico = $servico['valor'] * $servico[qnt];
                            }
                            echo "</td><td>" . number_format($valorServico, 2, ',', '.') . "</td>
                                </tr>";
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"><label>VALOR TOTAL </label> R$ <?php echo number_format($nota['valortotal'], 2, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <hr>
            <div id="detalhes">
                <?php
                $ponteiro = fopen("./observacao", "r");
                while (!feof($ponteiro)) {
                    $linha = fgets($ponteiro, 4096);
                    echo $linha;
                }
                fclose($ponteiro);
                ?>
                <p>Cliente: <?php
                    echo $nota['cliente'];
                    echo (!empty($nota[cpf])) ? ' - CPF ' . $nota[cpf] : '';
                    ?></p>
                <p>Operador: <?php echo $nota['funcionario'] ?></p>
                <hr>
                <p style="text-align:center">IServices - 51 3762.2096 - infosulst.com.br - Teutônia/RS</p>
            </div>
        </div>
    </body>
</html>