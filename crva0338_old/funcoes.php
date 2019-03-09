<?php

$meses = array(
    1 => 'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro'
);

function limpa($alimpar) {
    $limpar = array('Reinforme', 'CNPJ: ', 'CPF: ', '.', '/', '-', ' ', ')', '(');
    return str_replace($limpar, '', $alimpar);
}

function cpfcnpj($cpfcnpj) {

    if (mb_strlen($cpfcnpj) == 11 || mb_strlen($cpfcnpj) == 10) {
        $iniciocpfcnpj = (mb_strlen($cpfcnpj) == 10) ? '0' : '';
        $cpfcnpj = 'CPF: ' . $iniciocpfcnpj . substr($cpfcnpj, -11, -8) . '.' . substr($cpfcnpj, -8, -5) . '.' . substr($cpfcnpj, -5, -2) . '-' . substr($cpfcnpj, -2);
    } elseif (strlen($cpfcnpj) == 14) {
        $cpfcnpj = 'CNPJ: ' . substr($cpfcnpj, -14, -12) . '.' . substr($cpfcnpj, -12, -9) . '.' . substr($cpfcnpj, -9, -6) . '-' . substr($cpfcnpj, -6, -2) . '/' . substr($cpfcnpj, -2);
    } else {
        $cpfcnpj = 'Reinforme';
    }
    return $cpfcnpj;
}

function datausa($data) {
    $data = explode('/', $data);
    return $data[2] . '-' . $data[1] . '-' . $data[0];
}

function DataHora($dataHora) {
    $dataHora = date('d/m/Y H:i:s', strtotime($dataHora));
    return $dataHora;
}

function Sodata($data) {
    $data = date('d/m/Y', strtotime($data));
    return $data;
}

function Hora($hora) {
    $hora = date('H:i:s', strtotime($hora));
    return $hora;
}

function idade($nascimento) {
    list($ano, $mes, $dia ) = explode('-', $nascimento);
    $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);
    $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);
    return $idade;
}

function valCpf($entracpf) {
    $cpf = preg_replace('/[^0-9]/', '', $entracpf);
    $digitoA = 0;
    $digitoB = 0;
    for ($i = 0, $x = 10; $i <= 8; $i++, $x--) {
        $digitoA += $cpf[$i] * $x;
    }
    for ($i = 0, $x = 11; $i <= 9; $i++, $x--) {
        if (str_repeat($i, 11) == $cpf) {
            return false;
        }
        $digitoB += $cpf[$i] * $x;
    }
    $somaA = (($digitoA % 11) < 2 ) ? 0 : 11 - ($digitoA % 11);
    $somaB = (($digitoB % 11) < 2 ) ? 0 : 11 - ($digitoB % 11);
    if ($somaA != $cpf[9] || $somaB != $cpf[10]) {
        return false;
    } else {
        return true;
    }
    return true;
}
