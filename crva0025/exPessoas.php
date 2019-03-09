<?php

include("seguranca.php");
protegePagina();
require_once('connect.php');
require_once('./funcoes.php');
/*
 * Criando e exportando planilhas do Excel
 * /
 */
// Definimos o nome do arquivo que será exportado
$arquivo = 'planilha.xls';
// Criamos uma tabela HTML com o formato da planilha
$html = '';
$html .= '<table>';
$html .= '<tr>';
$html .= '<td colspan="3"><h2>Clientes CRVA 0050</h2></tr>';
$html .= '</tr>';
$html .= '<tr>';
$html .= '<td><b>Nome</b></td>';
$html .= '<td><b>CPF</b></td>';
$html .= '<td><b>Telefone</b></td>';
$html .= '</tr>';

$result = $db->prepare("SELECT `nome`, `cpf`, `telefone` FROM `cliente` WHERE (`cpf` NOT LIKE '' AND `cpf` NOT LIKE '0') AND (`telefone` NOT LIKE '' AND `telefone` NOT LIKE '0') ORDER BY `nome` ASC");
$result->execute();
while ($linha = $result->fetch(PDO::FETCH_ASSOC)) {
    if (strlen($linha[cpf]) == 11) {
        $html .= '<tr>';
        $html .= "<td>$linha[nome]</td>";

        $iniciocpf = (mb_strlen($linha[cpf]) == 10) ? '0' : '';
        $cpf = $iniciocpfcnpj . substr($linha[cpf], -11, -8) . '.' . substr($linha[cpf], -8, -5) . '.' . substr($linha[cpf], -5, -2) . '-' . substr($linha[cpf], -2);

        $html .= "<td>" . ((valCpf($cpf)) ? $cpf : "<i style='color: red'>" . $cpf . "</i>") . "</td>";
        $html .= "<td>$linha[telefone]</td>";
        $html .= '</tr>';
    }
}

$html .= '</table>';
// Configurações header para forçar o download
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-type: application/x-msexcel");
header("Content-Disposition: attachment; filename=\"{$arquivo}\"");
header("Content-Description: PHP Generated Data");
// Envia o conteúdo do arquivo
echo $html;
exit;
