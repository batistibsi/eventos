<?php

header('Content-Type: application/json; charset=UTF-8');

include_once "../zend.php";

$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$id_evento = (int)($_POST['id_evento'] ?? null);

if ($nome === '' || $email === '' || $id_evento <= 0) {
  //http_response_code(400);
  echo json_encode(['success' => false, 'erro' => 'Informe nome, email e evento.'], JSON_UNESCAPED_UNICODE);
  exit;
}

$campos = [];
$campos['nome'] = $nome;
$campos['email'] = $email;
$campos['id_evento'] = $id_evento;

if (!Inscricao::novo($campos)) {
  echo json_encode(['success' => false, 'erro' => Inscricao::$erro], JSON_UNESCAPED_UNICODE);
  exit;
}

echo json_encode(['success' => true, 'mensagem' => 'Inscrição criada. Confirme pelo e-mail.'], JSON_UNESCAPED_UNICODE);
exit;