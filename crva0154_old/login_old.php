<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="pt-br" >
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CRVA Admin - Sistema de controle</title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.css" rel="stylesheet">
        <link rel="stylesheet" href="css/awesome.css">
        <link rel="stylesheet" href="css/tela_login.css">

    </head>
    <body>
        <div class="row">
            <div id="login" class="painelLogo">
                <div id="boasvindas" class="col-lg-4">
                    <a href="?"><img src="img/logoCrva.png"/></a>
                </div>
                <div id="form-login" class="col-lg-8">
                    <?php
                    if (isset($_GET['alert'])) {
                        if ($_GET['alert'] == "erroSenha") {
                            printMSG("Confira nome de usuário e senha. Alguma coisa ocorreu fora do normal.", erro);
                        } elseif ($_GET['alert'] == "recuperaSenha") {
                            
                        }
                    }
                    if (empty($_GET["stage"])) {
                        ?>
                        <div class="row titulo">
                            <span class="acesso">Acesso restrito</span>
                        </div>
                        <form method="post" action="valida.php">
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <div class="meio"></div>
                                    <input type="email" class="form-control" placeholder="Email do usuário" name="usuario" value="<?php echo $_POST['usuario']; ?>" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <div class="meio"></div>
                                    <input type="password" class="form-control" placeholder="Senha" name="senha" value="<?php echo $_POST['senha']; ?>" required  />
                                </div>
                            </div>
                            <div class="row conectado">
                                <input type="checkbox" />
                                <span class="">Manter-me conectado.</span>
                            </div>
                            <div class="row entrada">
                                <button type="submit" class="btn btn-primary btn-lg" name="logar">
                                    Entrar
                                </button>
                                <a href="?stage=esqueceu"><span class="esqueceu">Esqueceu sua senha?</span></a>
                            </div>
                        </form>
                    </div>
                    <?php
                } elseif (!empty($_GET["stage"]) && $_GET["stage"] == "esqueceu") {
                    ?>
                    <div class="row titulo">
                        <span class="acesso">Recuperação de usuário</span>
                    </div>
                    <form action="" name="" class="" >
                        <div class="row">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-at"></i></span>
                                <div class="meio"></div>
                                <input type="email" class="form-control" placeholder="Informe email cadastrado" />
                            </div>
                        </div>
                        <div class="row entrada">
                            <button type="button" class="btn btn-primary btn-lg" name="recuperar">
                                Enviar instruções
                            </button>
                        </div>
                    </form>
                </div>
                <?php
            }
            ?>
        </div>
    </body>
</html>