<?php
global $_PS;
$idestado = ($_REQUEST['idestado']);
$cidades = array();

$result = $_PS['link']->prepare("SELECT `idcidade`, `nome` FROM `cidade` WHERE `estado` = $idestado ORDER BY `nome`");
$result->execute();
while ($cidade = $result->fetch(PDO::FETCH_ASSOC)) {
    $cidades[] = array(
        'idcidade' => $cidade['idcidade'],
        'nome' => $cidade['nome'],
    );
}
echo( json_encode($cidades) );
