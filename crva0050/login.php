<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRVA Admin - Sistema de controle</title>

        <link href="./css/bootstrap.css" rel="stylesheet" id="bootstrap-css">
        <script src="./js/jquery.js"></script>
        <script src="./js/bootstrap.js"></script>
        <!------ Include the above in your HEAD tag ---------->

        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="./css/login.css">
        <script>
            $(function () {
                $('#login-form-link').click(function (e) {
                    $("#login-form").delay(100).fadeIn(100);
                    $("#register-form").fadeOut(100);
                    $('#register-form-link').removeClass('active');
                    $(this).addClass('active');
                    e.preventDefault();
                });
                $('#register-form-link').click(function (e) {
                    $("#register-form").delay(100).fadeIn(100);
                    $("#login-form").fadeOut(100);
                    $('#login-form-link').removeClass('active');
                    $(this).addClass('active');
                    e.preventDefault();
                });
                $(".alert").fadeIn(function () {
                    $(this).delay(2500).fadeOut(1500);
                });
            });
        </script>
    </head>
    <body>
        <?php
        if (!empty($_GET['stage'])) {
            if ($_GET['stage'] == "erro") {
                $msg = array(
                    msg => "Usuário ou senha incorreto!",
                    tipo => "danger");
            } elseif ($_GET['stage'] == "login") {
                $msg = array(
                    msg => "Deve efetuar login!",
                    tipo => "warning");
            } elseif ($_GET['stage'] == "finalizada") {
                $msg = array(
                    msg => "Sessão encerrada!",
                    tipo => "info");
            } elseif ($_GET['stage'] == "cadastro") {
                $msg = array(
                    msg => "Solicitação de cadastro efetuada, entraremos em contato!",
                    tipo => "info");
            } elseif ($_GET['stage'] == "cadastroerro") {
                $msg = array(
                    msg => "Entre em contato por <b>contato@infosulst.com.br</b> ou <b>51 3762.2096</b>.",
                    tipo => "danger");
            }
        }
        ?>


        <div class="container">
            <?php if (!empty($msg)) { ?>
                <div class="alert alert-<?php echo $msg['tipo'] ?> text-center" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Aviso!</strong> <?php echo $msg['msg'] ?>
                </div>
            <?php } ?>
        </div>

        <div class="container">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="panel panel-login">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <form id="login-form" action="valida.php" method="post" role="form" style="display: block;">
                                        <h2>LOGIN</h2>
                                        <div class="form-group">
                                            <input type="text" name="usuario" id="usuario" tabindex="1" class="form-control" placeholder="Username" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="senha" id="senha" tabindex="2" class="form-control" placeholder="Password">
                                        </div>
                                        <div class="col-xs-6 form-group pull-left checkbox">
                                            <input id="checkbox1" type="checkbox" name="remember">
                                            <label for="checkbox1">Lembrar-me</label>   
                                        </div>
                                        <div class="col-xs-6 form-group pull-right">     
                                            <input type="submit" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Entrar">
                                        </div>
                                    </form>
                                    <form id="register-form" action="enviaform.php" method="post" role="form" style="display: none;">
                                        <h2>NOVO CADASTRO</h2>
                                        <div class="form-group">
                                            <input type="text" name="registro" id="username" tabindex="1" class="form-control" placeholder="CRVA 0000" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="localidade" id="localidade" tabindex="2" class="form-control" placeholder="Cidade/UF" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="telefone" id="telefone" tabindex="3" class="form-control" placeholder="Telefone" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="email" name="email" id="email" tabindex="4" class="form-control" placeholder="Email" value="">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="titular" id="titular" tabindex="5" class="form-control" placeholder="Títular">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" name="coordenador" id="coordenador" tabindex="6" class="form-control" placeholder="Coordenador">
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-6 col-sm-offset-3">
                                                    <input type="submit" name="registrar" id="registrar" tabindex="7" class="form-control btn btn-register" value="Enviar Cadastro">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-6 tabs">
                                    <a href="#" class="active" id="login-form-link"><div class="login">LOGIN</div></a>
                                </div>
                                <div class="col-xs-6 tabs">
                                    <a href="#" id="register-form-link"><div class="register">REGISTRAR</div></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer>
            <div class="container">
                <div class="col-md-10 col-md-offset-1 text-center">
                    <h6 style="font-size:14px;font-weight:100;color: #fff;">SisCRVA <img src="img/logoIS.png" title="" style="width: 20px; margin: 0 10px" /> <a href="http://infosulst.com.br/" style="color: #fff;" target="_blank" title="IServices - Soluções & Tecnologias">IServices</a></h6>
                </div>   
            </div>
        </footer>
    </body>
</html>