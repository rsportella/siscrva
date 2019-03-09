<?php
//  Configurações do Script
// ==============================
include './confdb.php';
$_PS['conectaServidor'] = true;
$_PS['abreSessao']      = true;
$_PS['caseSensitive']   = false;
$_PS['validaSempre']    = true;
$_PS['paginaLogin']     = 'login.php';
$_PS['tabela']          = 'funcionario';


if ($_PS['conectaServidor']) {
    $_PS['link'] = new PDO("mysql:host=$_PS[servidor];dbname=$_PS[banco];"
            . "charset=utf8", $_PS['usuario_db'], $_PS['senha']
            , array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}

if ($_PS['abreSessao']) {
    session_start();
    setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
    date_default_timezone_set('America/Sao_Paulo');
}

function validaUsuario($usuario, $senha) {
    global $_PS;
    $cS = ($_PS['caseSensitive']) ? 'BINARY' : '';
    // Usa a função addslashes para escapar as aspas
    $ret_usuario = addslashes($usuario);
    $ret_senha = addslashes($senha);
    $result = $_PS['link']->prepare("SELECT * FROM `" . $_PS['tabela']
            . "` WHERE " . $cS . " `email` = '" . $ret_usuario . "' AND "
            . $cS . " `senha` = '" . $ret_senha . "' LIMIT 1");
    $result->execute();
    $resultado = $result->fetch(PDO::FETCH_ASSOC);
    if (empty($resultado)) {
        return false;
    } else {
        $_SESSION['usuario_codigo']     = $resultado['idfuncionario'];
        $_SESSION['usuario_nome']       = $resultado['nome'];
        if ($_PS['validaSempre']) {
            $_SESSION['usuario_login']  = $usuario;
            $_SESSION['usuario_senha']  = md5($senha);
            $_SESSION['usuario_cpf']    = $resultado['cpf'];
        }
        return true;
    }
}

/**
 * Função que protege uma página
 */
function protegePagina() {
    global $_PS;

    if (!isset($_SESSION['usuario_codigo']) OR ! isset($_SESSION['usuario_nome'])) {
        header("Location: " . $_PS['paginaLogin'] . "?stage=login");
    } else if (!isset($_SESSION['usuario_codigo']) OR ! isset($_SESSION['usuario_nome'])) {
        if ($_PS['validaSempre'] == true) {
            if (!validaUsuario($_SESSION['usuario_login'], $_SESSION['usuario_senha'])) {
                header("Location: " . $_PS['paginaLogin'] . "?stage=erro");
            }
        }
    }
}

/**
 * Função para expulsar um visitante
 */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: " . $_PS['paginaLogin'] . "?stage=finalizada");
}
    