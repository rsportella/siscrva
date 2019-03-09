<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');

function calculaTempo($hora_inicial, $hora_final) {
    $i = 1;
    $tempo_total;

    $tempos = array($hora_final, $hora_inicial);

    foreach ($tempos as $tempo) {
        $segundos = 0;

        list($h, $m, $s) = explode(':', $tempo);

        $segundos += $h * 3600;
        $segundos += $m * 60;
        $segundos += $s;

        $tempo_total[$i] = $segundos;

        $i++;
    }
    $segundos = $tempo_total[1] - $tempo_total[2];

    $horas = floor($segundos / 3600);
    $segundos -= $horas * 3600;
    $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
    $segundos -= $minutos * 60;
    $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

    return "$horas:$minutos:$segundos";
}

function somaTempo($hora_inicial, $hora_final) {
    $i = 1;
    $tempo_total;

    $tempos = array($hora_final, $hora_inicial);

    foreach ($tempos as $tempo) {
        $segundos = 0;

        list($h, $m, $s) = explode(':', $tempo);

        $segundos += $h * 3600;
        $segundos += $m * 60;
        $segundos += $s;

        $tempo_total[$i] = $segundos;

        $i++;
    }
    $segundos = $tempo_total[1] + $tempo_total[2];

    $horas = floor($segundos / 3600);
    $segundos -= $horas * 3600;
    $minutos = str_pad((floor($segundos / 60)), 2, '0', STR_PAD_LEFT);
    $segundos -= $minutos * 60;
    $segundos = str_pad($segundos, 2, '0', STR_PAD_LEFT);

    return "$horas:$minutos:$segundos";
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
                font-size:12px;
            }
            body{
                font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            }
            p{
                margin: 4px;
                text-align: center;
            }
            table{
                width: 100%;
                text-align: left;
                border-spacing: 0;
                border-collapse: collapse;
            }
            table td{
                border-bottom: 1px solid black;
            }
            h1{
                font-size:200%;
                margin: 10px;
                text-align: center;
            }
            hr{
                margin: 10px 0;
                border: solid 1px black;
            }
            #detalhes{
                text-align: left;
            }

        </style>
    </head>
    <body onload="self.print();">
        <?php
        $result = $_PS['link']->prepare("SELECT `nome`, `nascimento`, `cpf`, `telefone`, `email` FROM `funcionario` WHERE `idfuncionario` = $_GET[funcionario] LIMIT 1");
        $result->execute();
        $funcionario = $result->fetch(PDO::FETCH_ASSOC);

        $results = $_PS['link']->prepare("SELECT * FROM `bateponto` WHERE `funcionario` = $_GET[funcionario] AND `data` >= '" . $_GET[inicio] . "' AND `data` <= '" . $_GET[termino] . "'");
        $results->execute();
        ?>
        <table>
            <tr>
                <th style="width: 40%">
                    <?php
                    $ponteiro = fopen("./crva", "r");
                    while (!feof($ponteiro)) {
                        $linha = fgets($ponteiro, 4096);
                        echo $linha;
                    }
                    fclose($ponteiro);
                    ?>
                <th><h1>Relatório Financeiro</h1></th>
            </tr>
        </table>
        <hr>
        <br><br>
        <p style="font-size: 150%; margin: 2%">Período de <?php echo Sodata($_GET[inicio]) . " à " . Sodata($_GET[termino]) ?></p>
        <table style="text-align: center">
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
                                            AND `data` >= '$_GET[inicio]'
                                            AND `data` <= '$_GET[termino] 23:59:59'";
                    $result = $_PS['link']->prepare($sqlquery);
                    $result->execute();
                    $valorItem = $countQnt = 0;
                    while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
                        if ($servico['idservicos'] == 31 && $linha['qnt'] > 9) {
                            $valorServico = 14.20;
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
                if ($countQnt == 0) {
                    echo "<tr><td colspan='3'>Período sem movimentação no CRVA.</td></tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: center; font-size: 160%">Valor total R$ <?php echo number_format($cont, 2, ',', '.') ?></td>
                </tr>
            </tfoot>
        </table>
        <br><br><br><br><br>
        <p>___________________________________<br>
            Assinatura do funcionário responsável financeiro</p>
        <p><?php echo $cidade?>, <?php echo date('d') ?> de <?php echo $meses[date('d')] ?> de <?php echo date('Y') ?> <?php echo date('H:i:s') ?></p>
    </body>
</html>