<?php

header('Content-Type: application/json; charset=UTF-8');

include_once "../zend.php";

function postValue($key)
{
  return trim($_POST[$key] ?? '');
}

function normalizarTelefone($valor)
{
  return preg_replace('/\D+/', '', trim((string) $valor));
}

function telefoneValido($valor)
{
  return (bool) preg_match('/^\d{10,11}$/', $valor);
}

function emailValido($valor)
{
  return filter_var($valor, FILTER_VALIDATE_EMAIL) !== false;
}

$campos = [];
$campos['nome'] = postValue('nome');
$campos['cpf_responsavel'] = postValue('cpf_responsavel');
$campos['email'] = postValue('email');
$campos['telefone'] = normalizarTelefone(postValue('telefone'));
$campos['nome_organizacao'] = postValue('nome_organizacao');
$campos['cnpj'] = postValue('cnpj');
$campos['endereco'] = postValue('endereco');
$campos['numero_colaboradores'] = (int)($_POST['numero_colaboradores'] ?? 0);
$campos['id_evento'] = (int)($_POST['id_evento'] ?? 0);
$campos['representante_1_nome'] = postValue('representante_1_nome');
$campos['representante_1_email'] = postValue('representante_1_email');
$campos['representante_1_telefone'] = normalizarTelefone(postValue('representante_1_telefone'));
$campos['representante_2_nome'] = postValue('representante_2_nome');
$campos['representante_2_email'] = postValue('representante_2_email');
$campos['representante_2_telefone'] = normalizarTelefone(postValue('representante_2_telefone'));
$campos['representante_3_nome'] = postValue('representante_3_nome');
$campos['representante_3_email'] = postValue('representante_3_email');
$campos['representante_3_telefone'] = normalizarTelefone(postValue('representante_3_telefone'));
$campos['primeira_participacao'] = postValue('primeira_participacao');
$campos['nome_certificado'] = postValue('nome_certificado');
$campos['como_soube'] = postValue('como_soube');
$campos['indicacao_organizacao'] = postValue('indicacao_organizacao');
$campos['id_forma_pagamento'] = (int)($_POST['id_forma_pagamento'] ?? 0);

$obrigatorios = [
  'nome' => 'nome do responsável',
  'cpf_responsavel' => 'CPF do responsável',
  'email' => 'e-mail do responsável',
  'telefone' => 'telefone do responsável',
  'nome_organizacao' => 'nome da organização',
  'cnpj' => 'CNPJ',
  'endereco' => 'endereco',
  'representante_1_nome' => 'nome do representante 1',
  'representante_1_email' => 'e-mail do representante 1',
  'representante_1_telefone' => 'telefone do representante 1',
  'primeira_participacao' => 'primeira participação',
  'nome_certificado' => 'nome da organização no certificado',
  'como_soube' => 'como ficou sabendo da certificação'
];

foreach ($obrigatorios as $campo => $label) {
  if ($campos[$campo] === '') {
    echo json_encode(['success' => false, 'erro' => 'Informe ' . $label . '.'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

$emailsObrigatorios = [
  'email' => 'e-mail do responsável',
  'representante_1_email' => 'e-mail do representante 1',
];

foreach ($emailsObrigatorios as $campo => $label) {
  if (!emailValido($campos[$campo])) {
    echo json_encode(['success' => false, 'erro' => 'Informe um ' . $label . ' válido.'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

$emailsOpcionais = [
  'representante_2_email' => 'e-mail do representante 2',
  'representante_3_email' => 'e-mail do representante 3',
];

foreach ($emailsOpcionais as $campo => $label) {
  if ($campos[$campo] !== '' && !emailValido($campos[$campo])) {
    echo json_encode(['success' => false, 'erro' => 'Informe um ' . $label . ' válido.'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

$telefonesObrigatorios = [
  'telefone' => 'telefone do responsável',
  'representante_1_telefone' => 'telefone do representante 1',
];

foreach ($telefonesObrigatorios as $campo => $label) {
  if (!telefoneValido($campos[$campo])) {
    echo json_encode(['success' => false, 'erro' => 'Informe um ' . $label . ' válido com DDD.'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

$telefonesOpcionais = [
  'representante_2_telefone' => 'telefone do representante 2',
  'representante_3_telefone' => 'telefone do representante 3',
];

foreach ($telefonesOpcionais as $campo => $label) {
  if ($campos[$campo] !== '' && !telefoneValido($campos[$campo])) {
    echo json_encode(['success' => false, 'erro' => 'Informe um ' . $label . ' válido com DDD.'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}

if ($campos['numero_colaboradores'] <= 0 || $campos['id_evento'] <= 0) {
  echo json_encode(['success' => false, 'erro' => 'Informe a quantidade de colaboradores e selecione um evento.'], JSON_UNESCAPED_UNICODE);
  exit;
}

if ($campos['id_forma_pagamento'] <= 0) {
  echo json_encode(['success' => false, 'erro' => 'Informe a forma de pagamento.'], JSON_UNESCAPED_UNICODE);
  exit;
}

if (!in_array($campos['primeira_participacao'], ['sim', 'nao'], true)) {
  echo json_encode(['success' => false, 'erro' => 'Informe corretamente se e sua primeira participacao.'], JSON_UNESCAPED_UNICODE);
  exit;
}

$logo = $_FILES['logo_organizacao'] ?? null;
if (!$logo || ($logo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
  echo json_encode(['success' => false, 'erro' => 'Envie a logo da organizacao.'], JSON_UNESCAPED_UNICODE);
  exit;
}

$campos['logo_organizacao'] = $logo;

if (!Inscricao::novo($campos)) {
  echo json_encode(['success' => false, 'erro' => Inscricao::$erro], JSON_UNESCAPED_UNICODE);
  exit;
}

echo json_encode(['success' => true, 'mensagem' => 'Inscrição criada. Logo informaremos os próximos passos.'], JSON_UNESCAPED_UNICODE);
exit;
