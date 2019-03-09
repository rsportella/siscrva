<?php

require_once("seguranca.php");
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
    $senha = (isset($_POST['senha'])) ? $_POST['senha'] : '';
    if (validaUsuario($usuario, md5($senha))) {
        header("Location: index.php");
    } else {
        header("Location: " . $_PS['paginaLogin'] . "?stage=erro");
    }
}