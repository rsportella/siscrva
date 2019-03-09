<?php

setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

require_once("phpmailer/class.phpmailer.php");
$mail = new PHPMailer(true);
$mail->IsSMTP(); // Define que a mensagem será SMTP
try {
    $mail->Host = 'smtp.infosulst.com.br'; // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->SMTPAuth = true;  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Port = 587; //  Usar 587 porta SMTP
    $mail->Username = 'contato@infosulst.com.br'; // Usuário do servidor SMTP (endereço de email)
    $mail->Password = 'rsportella0410'; // Senha do servidor SMTP (senha do email usado)
    $mail->SetFrom('contato@infosulst.com.br', 'Contato do site'); //Seu e-mail
    $mail->Subject = 'Contato pelo site'; //Assunto do e-mail
    $mail->AddAddress('contato@infosulst.com.br', 'IServices');
    $texto = "<h3>Contato efetuado pelo site<br><small>infosulst.com.br</small></h3>"
            . "<b>" . $_POST['registro'] . "</b><br>"
            . "Sr./Sra. <b>" . $_POST['coordenador'] . "</b><br>"
            . "Titular <b>" . $_POST['titular'] . "</b><br>"
            . "Telefone <b>" . $_POST['telefone'] . "</b><br>"
            . "Email <b>" . $_POST['email'] . "</b><br>"
            . "Localidade <b>" . $_POST['localidade'] . "</b><br>"
            . "<hr>" . "<br>"
            . "IP " . $_SERVER["REMOTE_ADDR"] . "<br>"
            . "Navegador " . $_SERVER["HTTP_USER_AGENT"] . "<br>"
            . "Informações em " . date("d/m/y h:i");
    $mail->MsgHTML($texto);
    $mail->CharSet = "UTF-8";
    $mail->Send();
    header("Location: login.php?stage=cadastro");
} catch (phpmailerException $e) {
    header("Location: login.php?stage=cadastroerro");
}
