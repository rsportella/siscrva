<?php
include("seguranca.php");
protegePagina();
require_once('./funcoes.php');
$result = $_PS['link']->prepare("SELECT * FROM `protocolo`, `cliente`, `tipoprotocolo` WHERE `tipoprotocolo` = `idtipoprotocolo` AND `cliente` = `idcliente` AND `idprotocolo` >= '$_GET[inicio]' AND `idprotocolo` <= '$_GET[fim]'");
$result->execute();
?>

<html lang="pt-BR">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.css">
        <style>
            .protocolo{
                border: solid 1px black;
                min-height: 11.5cm;
                padding: 0 20px;
            }
            img{
                margin-top: 20px;
                width: 250px
            }
            .quebrapagina{
                page-break-after: always;
            }
            p{
                margin-bottom: 0px;
            }
            #title{
                font-size: 30px;
                font-weight: 800;
            }
            .titulo{
                font-size: 12px;
                font-weight: normal
            }
            .informacao{
                font-size: 15px;
            }
            .destaque{
                font-size: 20px;
                font-weight: 800;
                text-decoration: underline
            }
            hr{
                border: dashed 1px #bbb
            }
            @media print {
                img{width: 300px}
            }
        </style>
    </head>
    <body onload="self.print();">
        <?php while ($linha = $result->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="protocolo">
                <div class="row">
                    <div class="col-xs-3">
                        <img src="img/rslogo.jpg">
                    </div>
                    <div class="col-xs-6 text-center">
                        <?php
                        $ponteiro = fopen("./crva", "r");
                        while (!feof($ponteiro)) {
                            $row = fgets($ponteiro, 4096);
                            echo $row;
                        }
                        fclose($ponteiro);
                        ?>
                    </div>
                    <div class="col-xs-3 text-right">
                        <img src="img/detran-rs.jpg">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-4">
                                <label class="titulo">Data abertura</label><br>
                                <label class="informacao"><?php echo Sodata($linha['datainicio']) ?></label>
                            </div>
                            <div class="col-xs-4">
                                <label class="titulo">Data fechamento</label><br>
                                <label class="informacao"><?php echo ($linha['datafechamento'] == "0000-00-00") ? "" : Sodata($linha['datafechamento']) ?></label>
                            </div>
                            <div class="col-xs-4">
                                <label class="titulo">Processo</label><br>
                                <label class="informacao"><?php echo $linha['processo'] ?></label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-4">
                                <label class="titulo">Protocolo</label><br>
                                <label class="informacao destaque"><?php echo $linha['idprotocolo'] ?></label>
                            </div>
                            <div class="col-xs-4">
                                <label class="titulo">Placa</label><br>
                                <label class="informacao destaque"><?php echo $linha['placa'] ?></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <label class="titulo">Pendências</label><br>
                        <label class="informacao"><?php echo $linha['pendencia'] ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label class="titulo">Pessoa</label><br>
                        <label class="informacao destaque"><?php echo $linha['nome'] . " - " . cpfcnpj($linha['cpf']) ?></label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label class="titulo">Tipo Protocolo</label><br>
                        <label class="informacao"><?php echo $linha['titulo'] ?></label>
                    </div>
                </div>
                <br><div class="row text-center footer">
                    <div class="col-xs-12">
                        <label style="font-weight: normal;">IServices - 51 3762.2096 - infosulst.com.br - Teutônia/RS</label>
                    </div>
                </div>
            </div>
            <?php
            $tmp++;
            echo ($tmp % 2 == 0) ? '<div class="quebrapagina"></div>' : '<hr>'
            ?>
        <?php } ?>
    </body>
</html>