<?php

//  Configurações do Script
// ==============================
$_PS['criptografia'] = md5("siscrva");
$_PS['conectaServidor'] = true;         // Abre uma conexão com o servidor MySQL?
$_PS['abreSessao'] = true;              // Inicia a sessão com um session_start()?
$_PS['caseSensitive'] = false;          // Usar case-sensitive? Onde 'thiago' é diferente de 'THIAGO'
$_PS['validaSempre'] = true;            // Deseja validar o usuário e a senha a cada carregamento de página?
// Evita que, ao mudar os dados do usuário no banco de dado o mesmo contiue logado.
$_PS['servidor'] = 'localhost';    // Servidor MySQL
$_PS['funcionario'] = 'u842352461_0338';        // Usuário MySQL
$_PS['senha'] = '8Balurs=@H&N';           // Senha MySQL
$_PS['banco'] = 'u842352461_0338';              // Banco de dados MySQL
$_PS['paginaLogin'] = 'login.php';      // Página de login
$_PS['tabela'] = 'funcionario';         // Nome da tabela onde os usuários são salvos

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

//valor global
$valorDigitalizacao = 14.55;
$cidadeOrigemCrva = 4239;

if ($_PS['conectaServidor']) {
    $_PS['link'] = new PDO("mysql:host=$_PS[servidor];dbname=$_PS[banco];charset=utf8", $_PS['funcionario'], $_PS['senha'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
}

if ($_PS['abreSessao']) {
    if (empty(session_id($_PS['criptografia']))) {
        session_start();
    }
}

function validaUsuario($funcionario, $senha) {
    global $_PS;
    $cS = ($_PS['caseSensitive']) ? 'BINARY' : '';
    // Usa a função addslashes para escapar as aspas
    $nfuncionario = addslashes($funcionario);
    $nsenha = addslashes($senha);
    // Monta uma consulta SQL (query) para procurar um usuário
    $result = $_PS['link']->prepare("SELECT * FROM `" . $_PS['tabela'] . "` WHERE " . $cS . " `email` = '" . $nfuncionario . "' AND " . $cS . " `senha` = '" . md5($nsenha) . "' LIMIT 1");
    $result->execute();
    $resultado = $result->fetch(PDO::FETCH_ASSOC);
    // Verifica se encontrou algum registro
    if (empty($resultado)) {
        // Nenhum registro foi encontrado => o usuário é inválido
        return false;
    } else {
        // Definimos dois valores na sessão com os dados do usuário
        $_SESSION['funcionarioID'] = $resultado['idfuncionario']; // Pega o valor da coluna 'id do registro encontrado no MySQL
        $_SESSION['funcionarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL
        // Verifica a opção se sempre validar o login
        if ($_PS['validaSempre']) {
            // Definimos dois valores na sessão com os dados do login
            $_SESSION['funcionarioLogin'] = $funcionario;
            $_SESSION['funcionarioCPF'] = $resultado['cpf'];
        }
        return true;
    }
}

/**
 * Função que protege uma página
 */
function protegePagina() {
    global $_PS;

    if (empty($_SESSION['funcionarioID']) OR empty($_SESSION['funcionarioNome'])) {
        // Não há usuário logado, manda pra página de login
        header("Location: " . $_PS['paginaLogin'] . "?stage=login");
    } else if (!isset($_SESSION['funcionarioID']) OR ! isset($_SESSION['funcionarioNome'])) {
        // Há usuário logado, verifica se precisa validar o login novamente
        if ($_PS['validaSempre'] == true) {
            // Verifica se os dados salvos na sessão batem com os dados do banco de dados
            if (!validaUsuario($_SESSION['funcionarioLogin'])) {
                // Os dados não batem, manda pra tela de login
                header("Location: " . $_PS['paginaLogin'] . "?stage=erro");
            }
        }
    }
}

/**
 * Função para expulsar um visitante
 */
if (isset($_GET['logout'])) {
    if (count($_SESSION) != 0) {
        session_destroy();
        // Remove o cookie da sessão se ele existir
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', false, (time() - 3600));
            unset($_COOKIE['PHPSESSID']);
        }
        header("Location: " . $_PS['paginaLogin'] . "?stage=finalizada");
    }
}
    