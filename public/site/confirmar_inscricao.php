<?php
declare(strict_types=1);

$dbDsn  = 'pgsql:host=localhost;port=5432;dbname=SEU_BANCO';
$dbUser = 'SEU_USUARIO';
$dbPass = 'SUA_SENHA';

$token = trim($_GET['token'] ?? '');
if ($token === '') die('Token inválido.');

$pdo = new PDO($dbDsn, $dbUser, $dbPass, [
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$stmt = $pdo->prepare("
  update evento_inscricao
  set status = 'CONFIRMADO',
      confirmado_em = now()
  where token_confirmacao = :token
    and status = 'PENDENTE'
    and token_expira_em >= now()
  returning id_inscricao, email, nome
");
$stmt->execute([':token' => $token]);
$ok = $stmt->fetch();

if (!$ok) {
  die('Token inválido, expirado ou inscrição já confirmada.');
}

echo "Inscrição confirmada com sucesso! Obrigado, {$ok['nome']}.";