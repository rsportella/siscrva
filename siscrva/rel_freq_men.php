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
                <th><h1>Relatório de Relógio Ponto <br><small>em sistema</small></h1></th>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
                <th style="text-align: center"><img src="<?php echo './img/ctpa/' . $funcionario[cpf] . '.jpg'; ?>" alt="<?php echo $funcionario[nome]; ?>" width="100"/></th>
                <th><?php echo $funcionario[nome]; ?><br>
                    <?php echo Sodata($funcionario[nascimento]); ?><br>
                    <?php echo $funcionario[telefone]; ?><br>
                    <?php echo $funcionario[email]; ?></th>
            </tr>
        </table>
        <br><br>
        <p style="font-size: 150%; margin: 2%">Período de <?php echo Sodata($_GET[inicio]) . " à " . Sodata($_GET[termino]) ?></p>
        <table style="text-align: center">
            <thead>
                <tr>
                    <th rowspan="2">DATA</th>
                    <th colspan="2">TURNO 1</th>
                    <th colspan="2">TURNO 2</th>
                    <th rowspan="2">TOTAL DIA</th>
                </tr>
                <tr>
                    <th>ENTRADA</th>
                    <th>SAÍDA</th>
                    <th>ENTRADA</th>
                    <th>SAÍDA</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($linha = $results->fetch(PDO::FETCH_ASSOC)) {
                    if ($dataTemp != $linha[data]) {
                        $horas = 0;
                        $count = 0;
                        echo"<tr>";
                        echo"<td>" . Sodata($linha[data]) . "</td>";
                        echo"<td>$linha[hora]</td>";
                    } else {
                        echo"<td>$linha[hora]</td>";
                    }

                    if ($count == 0) {
                        $hora1 = $linha[hora];
                    } else if ($count == 1) {
                        $hora2 = $linha[hora];
                    } else if ($count == 2) {
                        $hora3 = $linha[hora];
                    } else if ($count == 3) {
                        $hora4 = $linha[hora];
                        echo"<td>" . somaTempo(calculaTempo($hora1, $hora2), calculaTempo($hora3, $hora4)) . "</td>";

                        echo"</tr>";
                    }
                    $count++;
                    $dataTemp = $linha[data];
                    ?>
                <?php } ?>
            </tbody>
        </table>
        <br><br><br><br><br>
        <p>___________________________________<br>Assinatura do funcionário <?php echo $funcionario[nome]; ?></p>
        <p>Teutônia, <?php echo date('d') ?> de <?php echo $meses[date('d')] ?> de <?php echo date('Y') ?> <?php echo date('H:i:s') ?></p>
    </body>
</html>