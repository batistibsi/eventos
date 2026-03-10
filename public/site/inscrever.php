<?php
declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ─────────────────────────────────────────────────────────────
// CONFIG
// ─────────────────────────────────────────────────────────────
$dbDsn  = 'pgsql:host=localhost;port=5432;dbname=eventos';
$dbUser = 'postgres';
$dbPass = '1234';

$stmt = $pdo->prepare("
  select
    base_url_confirmacao,
    token_validade_horas,
    smtp_host,
    smtp_user,
    smtp_pass,
    smtp_port,
    smtp_secure,
    from_email,
    from_name
  from config_email
  where ativo = true
  order by updated_at desc
  limit 1
");
$stmt->execute();
$cfg = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cfg) {
  throw new Exception('Configuração de e-mail não encontrada (config_email).');
}

$baseUrlConfirmacao = $cfg['base_url_confirmacao'];
$tokenValidadeHoras = (int)$cfg['token_validade_horas'];

$smtpHost = $cfg['smtp_host'];
$smtpUser = $cfg['smtp_user'];
$smtpPass = $cfg['smtp_pass'];
$smtpPort = (int)$cfg['smtp_port'];

// mapeia texto do banco -> PHPMailer
$smtpSecure = match (strtolower(trim($cfg['smtp_secure']))) {
  'tls', 'starttls' => PHPMailer::ENCRYPTION_STARTTLS,
  'ssl'             => PHPMailer::ENCRYPTION_SMTPS,
  'none', ''        => false,
  default           => PHPMailer::ENCRYPTION_STARTTLS,
};

$fromEmail = $cfg['from_email'];
$fromName  = $cfg['from_name'];

// ─────────────────────────────────────────────────────────────
// INPUT
// ─────────────────────────────────────────────────────────────
$nome     = trim($_POST['nome'] ?? '');
$email    = trim($_POST['email'] ?? '');
$idEvento = (int)($_POST['id_evento'] ?? 0);

if ($nome === '' || $email === '' || $idEvento <= 0) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'erro' => 'Informe nome, email e evento.']);
  exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  http_response_code(400);
  echo json_encode(['ok' => false, 'erro' => 'E-mail inválido.']);
  exit;
}

// token seguro
$token = bin2hex(random_bytes(24));
$expiraEm = (new DateTimeImmutable())->modify("+{$tokenValidadeHoras} hours")->format('Y-m-d H:i:s');

// ─────────────────────────────────────────────────────────────
// DB: salva inscrição
// ─────────────────────────────────────────────────────────────
try {
  $pdo = new PDO($dbDsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ]);

  // Se já existe inscrição pendente para o mesmo email/evento, você pode:
  // - impedir, ou
  // - regenerar token e reenviar.
  // Aqui vou regenerar token se já estiver PENDENTE.
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("
    select id_inscricao, status
    from evento_inscricao
    where id_evento = :id_evento and email = :email
    limit 1
  ");
  $stmt->execute([':id_evento' => $idEvento, ':email' => $email]);
  $existe = $stmt->fetch();

  if ($existe) {
    if ($existe['status'] === 'CONFIRMADO') {
      $pdo->rollBack();
      echo json_encode(['ok' => true, 'mensagem' => 'Você já está confirmado neste evento.']);
      exit;
    }

    // Atualiza token/expiração se estiver pendente/cancelado
    $stmt = $pdo->prepare("
      update evento_inscricao
      set nome = :nome,
          status = 'PENDENTE',
          token_confirmacao = :token,
          token_expira_em = :expira
      where id_inscricao = :id
    ");
    $stmt->execute([
      ':nome' => $nome,
      ':token' => $token,
      ':expira' => $expiraEm,
      ':id' => (int)$existe['id_inscricao']
    ]);

    $idInscricao = (int)$existe['id_inscricao'];
  } else {
    $stmt = $pdo->prepare("
      insert into evento_inscricao (id_evento, nome, email, token_confirmacao, token_expira_em)
      values (:id_evento, :nome, :email, :token, :expira)
      returning id_inscricao
    ");
    $stmt->execute([
      ':id_evento' => $idEvento,
      ':nome' => $nome,
      ':email' => $email,
      ':token' => $token,
      ':expira' => $expiraEm,
    ]);
    $idInscricao = (int)$stmt->fetchColumn();
  }

  $pdo->commit();
} catch (Throwable $e) {
  if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
  http_response_code(500);
  echo json_encode(['ok' => false, 'erro' => 'Erro ao salvar inscrição.', 'detalhe' => $e->getMessage()]);
  exit;
}

// ─────────────────────────────────────────────────────────────
// Email: envia link de confirmação
// ─────────────────────────────────────────────────────────────
$link = $baseUrlConfirmacao . '?token=' . urlencode($token);

$assunto = 'Confirme sua inscrição';
$corpoHtml = "
  <div style='font-family:Arial,sans-serif;font-size:14px;line-height:1.5'>
    <p>Olá, <b>" . htmlspecialchars($nome, ENT_QUOTES, 'UTF-8') . "</b>!</p>
    <p>Recebemos sua inscrição. Para confirmar, clique no link abaixo:</p>
    <p><a href='{$link}' target='_blank'>{$link}</a></p>
    <p>Este link expira em {$tokenValidadeHoras} horas.</p>
    <p>Se você não solicitou essa inscrição, ignore este e-mail.</p>
  </div>
";

try {
  $mail = new PHPMailer(true);
  $mail->CharSet = 'UTF-8';
  $mail->isSMTP();
  $mail->Host = $smtpHost;
  $mail->SMTPAuth = true;
  $mail->Username = $smtpUser;
  $mail->Password = $smtpPass;
  $mail->Port = $smtpPort;
  $mail->SMTPSecure = $smtpSecure;

  $mail->setFrom($fromEmail, $fromName);
  $mail->addAddress($email, $nome);
  $mail->Subject = $assunto;
  $mail->isHTML(true);
  $mail->Body = $corpoHtml;

  $mail->send();
} catch (Throwable $e) {
  // inscrição foi salva, mas falhou o email
  http_response_code(502);
  echo json_encode([
    'ok' => false,
    'erro' => 'Inscrição salva, mas falhou ao enviar e-mail.',
    'id_inscricao' => $idInscricao,
    'detalhe' => $e->getMessage()
  ]);
  exit;
}

// resposta ok
echo json_encode(['ok' => true, 'id_inscricao' => $idInscricao, 'mensagem' => 'Inscrição criada. Confirme pelo e-mail.']);