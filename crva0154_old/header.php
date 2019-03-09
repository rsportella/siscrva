<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRVA Admin - Sistema de controle</title>

        <!-- CSS -->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/dataTables-awesome.css">
        <link rel="stylesheet" href="css/awesome.css">
        <link rel="stylesheet" href="css/tableTools.css">
        <link rel="stylesheet" href="css/bootstrap-dataTables.css">
        <link rel="stylesheet" href="css/style.css">

        <!-- JS -->
        <script src="js/jquery.js"></script>
        <script src="js/validate.js"></script>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.js"></script>
        <script src="js/extra.js"></script>
    </head>
    <body>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="./index.php"><img height="20" alt="portAzul" src="img/logoCrva.png"></a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="./index.php">Frente de Caixa</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Gestor <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="./cliente.php?modal=listar">Cliente</a></li>
                                <li><a href="./funcionario.php?modal=listar">Funcionários</a></li>
                                <li><a href="./protocolo.php?modal=listar">Protocolo</a></li>
                                <li role="separator" class="divider"></li>
                                <li class="dropdown-header">Relatório</li>
                                <li><a href="./notas.php">Nota</a></li>
                                <li><a href="./relatorios.php">Serviço</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manutenção <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="./servicos.php?modal=listar">Serviços</a></li>
                                <li><a href="./tipoprotocolo.php?modal=listar">Tipo de protocolo</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Favoritos <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="https://www.sefaz.rs.gov.br/nfe/nfe-com.aspx" target="_blank">Sefaz RS </a></li>
                                <li><a href="http://www.detran.rs.gov.br/guia-veiculos" target="_blank">Detran RS - Guia Veiculos</a></li>
                                <li><a href="http://www.denatran.gov.br/" target="_blank">Denatran </a></li>
                                <li><a href="http://www.receita.fazenda.gov.br/pessoajuridica/cnpj/cnpjreva/cnpjreva_solicitacao.asp" target="_blank">Consulta CNPJ </a></li>
                                <li><a href="http://www.receita.fazenda.gov.br/aplicacoes/atcta/cpf/ConsultaPublica.asp" target="_blank">Consulta CPF </a></li>
                                <li><a href="http://www.nfe.fazenda.gov.br/portal/consulta.aspx?tipoConsulta=completa&tipoConteudo=XbSeqxE8pl8=" target="_blank">Consulta NF </a></li>
                                <li><a href="http://www.fipe.org.br/web/index.asp?aspx=/web/indices/veiculos/introducao.aspx" target="_blank">FIPE </a></li>
                                <li><a href="http://www.receita.fazenda.gov.br/Aplicacoes/ATSPO/Certidao/CndConjuntaInter/EmiteCertidaoInternet.asp?ni=91767145000178&passagens=1&tipo=1" target="_blank">RF - Certidão de Débitos</a></li>
                                <li><a href="http://www.detran.rs.gov.br/portarias" target="_blank">Detran RS - Portaria</a></li>
                                <li><a href="http://www.agtransrecorre.com/?gclid=CN_1ldK6pcMCFRQQ7AodJzoAAQ#!tabela/c1cf6" target="_blank"> Tabela de infrações</a></li>
                                <li><a href="http://www.tjrs.jus.br/site/servicos/verificacao_da_autenticidade_de_documentos/" target="_blank">TJ RS </a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown active">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['funcionarioNome']; ?> <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li ><a target="_blank" href="./baterponto.php">Bate Ponto</a></li>
                                <li><a href="./protocolo.php?modal=listar">Protocolo</a></li>
                            </ul>
                        </li>
                        <li class="active"><a href="index.php?logout">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <?php if (!empty($msg)) { ?>
                <div class="alert alert-<?php echo $msg['tipo'] ?> text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Aviso!</strong> <?php echo $msg['msg'] ?>
                </div>
            <?php } ?>
        </div>