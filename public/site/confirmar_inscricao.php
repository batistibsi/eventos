<?php

$token = trim($_GET['token'] ?? '');
if ($token === '') die('Token inválido.');

include_once "../zend.php";

$inscricao = Inscricao::buscaToken($token);

if(!$inscricao){
  $msg = "Inscrição não encontrada!";
}

$msg = "Inscrição confirmada com sucesso! Obrigado, {$inscricao['nome']}.";