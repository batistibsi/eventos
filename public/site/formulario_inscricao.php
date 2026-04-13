<?php

include_once "../zend.php";

$eventos = Evento::lista(true);

if (count($eventos)) {
  foreach ($eventos as $key => $value) {
    if (!Evento::confereVagas($value['id_evento'], $value['limite_vagas'])) {
      unset($eventos[$key]);
    }
  }
}

function formatarDataHoraEvento($valor)
{
  if (empty($valor)) {
    return '-';
  }

  $timestamp = strtotime($valor);

  if ($timestamp === false) {
    return htmlspecialchars($valor, ENT_QUOTES, 'UTF-8');
  }

  $meses = [
    1 => 'Jan',
    2 => 'Fev',
    3 => 'Mar',
    4 => 'Abr',
    5 => 'Mai',
    6 => 'Jun',
    7 => 'Jul',
    8 => 'Ago',
    9 => 'Set',
    10 => 'Out',
    11 => 'Nov',
    12 => 'Dez',
  ];

  return date('d', $timestamp) . ' ' . $meses[(int) date('n', $timestamp)];
}

?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Instituto ACIM | Responsabilidade Social</title>
  <meta http-equiv="refresh" content="600">

  <link rel="icon" href="./favicon.ico">

  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
    crossorigin="anonymous">

  <style>
    :root {
      --brand: rgb(8, 68, 68);
      --accent: #abca69;
      --brand-dark: #063c3c;
      --bg: #f6f8fb;
      --card: #ffffff;
      --muted: #6c757d;
    }

    body {
      background: var(--bg);
      color: #233046;
    }

    .hero {
      background:
        radial-gradient(900px 420px at 0% 0%, rgba(171, 202, 105, .22), transparent 60%),
        radial-gradient(820px 400px at 100% 0%, rgba(255, 255, 255, .09), transparent 55%),
        linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 58%, #0f7c7c 100%);
      color: #fff;
      padding: 42px 0 70px;
      position: relative;
      overflow: hidden;
    }

    .hero::after {
      content: "";
      position: absolute;
      inset: auto -10% -120px;
      height: 220px;
      background: radial-gradient(circle, rgba(255, 255, 255, .18) 0%, rgba(255, 255, 255, 0) 70%);
      pointer-events: none;
    }

    .brand {
      color: #fff;
      font-weight: 800;
      letter-spacing: -.4px;
    }

    .subtitle {
      color: var(--muted);
    }

    .badge-accent {
      background: rgba(171, 202, 105, .18);
      color: #f5f9e8;
      border: 1px solid rgba(171, 202, 105, .35);
      border-radius: 999px;
      padding: .45rem .8rem;
      font-weight: 700;
    }

    .card-soft {
      border: 1px solid rgba(0, 0, 0, .08);
      border-radius: 18px;
      box-shadow: 0 12px 28px rgba(0, 0, 0, .08);
      background: var(--card);
    }

    .btn-brand {
      background: var(--brand);
      border-color: var(--brand);
      color: #fff;
      border-radius: 12px;
      padding: 10px 14px;
      font-weight: 700;
    }

    .btn-brand:hover {
      filter: brightness(.95);
      color: #fff;
    }

    .form-control {
      border-radius: 12px;
      border: 1px solid rgba(0, 0, 0, .14);
      height: calc(1.5em + 1.1rem + 2px);
    }

    textarea.form-control {
      min-height: 110px;
    }

    .form-control:focus,
    .form-control-file:focus {
      border-color: rgba(24, 56, 133, .55);
      box-shadow: 0 0 0 .2rem rgba(24, 56, 133, .12);
    }

    .help {
      font-size: 12.5px;
      color: var(--muted);
      line-height: 1.35;
    }

    .form-group {
      display: flex;
      flex-direction: column;
    }

    .form-group .form-control,
    .form-group .form-control-file {
      margin-top: auto;
    }

    @media (min-width: 768px) {
      .form-row .help {
        min-height: 2.7em;
      }
    }

    .rep-card {
      background: linear-gradient(180deg, #fbfcff 0%, #f3f6fb 100%);
      border: 1px solid rgba(24, 56, 133, .10);
      border-radius: 16px;
      padding: 18px 18px 8px;
      margin-bottom: 18px;
      box-shadow: inset 0 1px 0 rgba(255, 255, 255, .75);
    }

    .rep-title {
      color: var(--brand);
      font-size: 1.15rem;
      font-weight: 800;
      letter-spacing: -.01em;
      text-transform: none;
      margin-bottom: 14px;
    }

    .hero-logo {
      height: 72px;
      width: auto;
      padding: 6px;
    }

    .hero-wrap {
      position: relative;
      z-index: 1;
    }

    .hero-kicker {
      display: inline-block;
      margin-bottom: 16px;
      color: rgba(255, 255, 255, .85);
      font-size: 12px;
      font-weight: 700;
      letter-spacing: .18em;
      text-transform: uppercase;
    }

    .hero-title {
      font-size: 44px;
      line-height: 1.05;
      font-weight: 800;
      margin-bottom: 18px;
      max-width: 720px;
    }

    .hero-description {
      color: rgba(255, 255, 255, .88);
      font-size: 17px;
      line-height: 1.65;
      max-width: 760px;
      margin-bottom: 0;
    }

    .hero-panel {
      background: rgba(255, 255, 255, .12);
      border: 1px solid rgba(255, 255, 255, .14);
      backdrop-filter: blur(8px);
      border-radius: 22px;
      padding: 24px;
      box-shadow: 0 20px 40px rgba(5, 15, 40, .18);
      height: 100%;
    }

    .hero-panel-title {
      font-size: 13px;
      font-weight: 800;
      letter-spacing: .08em;
      text-transform: uppercase;
      color: rgba(255, 255, 255, .72);
      margin-bottom: 12px;
    }

    .hero-panel p,
    .hero-panel a,
    .hero-panel strong {
      color: #fff;
    }

    .hero-link {
      display: inline-flex;
      align-items: center;
      font-weight: 700;
      text-decoration: none;
      border-bottom: 1px solid rgba(255, 255, 255, .35);
      padding-bottom: 2px;
    }

    .hero-link:hover {
      color: #fff;
      text-decoration: none;
      border-color: rgba(255, 255, 255, .8);
    }

    .hero-contact {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: 700;
      text-decoration: none;
    }

    .hero-contact svg {
      flex-shrink: 0;
    }

    .hero-contact-icon {
      width: 22px;
      height: 22px;
      border-radius: 999px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      background: #25d366;
      color: #fff;
      box-shadow: 0 4px 10px rgba(37, 211, 102, .22);
    }

    .hero-contact-icon svg {
      width: 12px;
      height: 12px;
      display: block;
    }

    .hero-contact:hover {
      color: #fff;
      text-decoration: none;
      opacity: .92;
    }

    .intro-card {
      margin-top: -34px;
      position: relative;
      z-index: 2;
    }

    .intro-note {
      background: linear-gradient(180deg, #fbfcff 0%, #f2f6fd 100%);
      border: 1px solid rgba(24, 56, 133, .08);
      border-radius: 16px;
      padding: 18px 20px;
      margin-bottom: 24px;
    }

    .intro-note strong {
      color: var(--brand);
    }

    .consent-box {
      background: linear-gradient(180deg, #fbfcff 0%, #f2f6fd 100%);
      border: 1px solid rgba(24, 56, 133, .08);
      border-radius: 16px;
      padding: 22px 24px;
      margin-bottom: 24px;
    }

    .consent-box p:last-child {
      margin-bottom: 0;
    }

    @media (max-width: 576px) {
      .hero-title {
        font-size: 34px !important;
      }
    }

    .page-footer {
      color: var(--muted);
      font-size: 14px;
      text-align: center;
      padding: 28px 15px 36px;
    }

    .turma-table-wrap {
      border: 1px solid rgba(24, 56, 133, .10);
      border-radius: 16px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 12px 28px rgba(8, 68, 68, .08);
    }

    .turma-table {
      margin-bottom: 0;
    }

    .turma-table thead th {
      background: #f2f6fd;
      color: var(--brand);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: .04em;
      text-transform: uppercase;
      border-top: 0;
      white-space: nowrap;
      text-align: center;
    }

    .turma-table tbody tr {
      transition: background .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .turma-table tbody tr:hover {
      background: #f8fbf5;
    }

    .turma-table td {
      vertical-align: middle;
      text-align: center;
      text-transform: uppercase;
    }

    .turma-radio-cell {
      width: 72px;
      text-align: center;
    }

    .turma-radio {
      appearance: none;
      -webkit-appearance: none;
      width: 24px;
      height: 24px;
      border-radius: 999px;
      border: 2px solid rgba(8, 68, 68, .28);
      background: #fff;
      display: inline-grid;
      place-items: center;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(8, 68, 68, .08);
      transition: transform .16s ease, border-color .16s ease, box-shadow .16s ease, background .16s ease;
    }

    .turma-radio::before {
      content: "";
      width: 10px;
      height: 10px;
      border-radius: 999px;
      transform: scale(0);
      transition: transform .16s ease;
      background: #fff;
    }

    .turma-radio:hover {
      border-color: var(--brand);
      transform: scale(1.04);
    }

    .turma-radio:checked {
      background: linear-gradient(135deg, var(--brand), #0f7c7c);
      border-color: var(--brand);
      box-shadow: 0 8px 18px rgba(8, 68, 68, .18);
    }

    .turma-radio:checked::before {
      transform: scale(1);
    }

    .turma-table tbody tr.turma-selecionada {
      background: linear-gradient(90deg, rgba(171, 202, 105, .16), rgba(8, 68, 68, .05));
      box-shadow: inset 4px 0 0 var(--accent);
    }

    .turma-table tbody tr.turma-selecionada td {
      color: var(--brand-dark);
      font-weight: 700;
    }

    @media (max-width: 767.98px) {
      .turma-table-wrap {
        border: 0;
        background: transparent;
        box-shadow: none;
        overflow: visible;
      }

      .turma-table,
      .turma-table thead,
      .turma-table tbody,
      .turma-table tr,
      .turma-table th,
      .turma-table td {
        display: block;
      }

      .turma-table {
        background: transparent;
      }

      .turma-table thead {
        display: none;
      }

      .turma-table tbody tr {
        margin-bottom: 14px;
        padding: 16px 16px 14px;
        border: 1px solid rgba(24, 56, 133, .10);
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 28px rgba(8, 68, 68, .08);
      }

      .turma-table tbody tr:last-child {
        margin-bottom: 0;
      }

      .turma-table td {
        border-top: 0;
        padding: 0;
        text-align: left;
        text-transform: none;
      }

      .turma-radio-cell {
        width: 100%;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
      }

      .turma-table td[data-label] {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        padding: 9px 0;
        border-top: 1px solid rgba(24, 56, 133, .08);
      }

      .turma-table td[data-label]::before {
        content: attr(data-label);
        flex: 0 0 42%;
        max-width: 140px;
        color: var(--brand);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
      }

      .turma-cell-value {
        flex: 1 1 auto;
        min-width: 0;
        text-align: right;
        font-size: 14px;
        line-height: 1.45;
        word-break: break-word;
      }

      .turma-table tbody tr.turma-selecionada {
        box-shadow: inset 4px 0 0 var(--accent), 0 14px 30px rgba(8, 68, 68, .12);
      }
    }
  </style>
</head>

<body>

  <header class="hero">
    <div class="container hero-wrap">
      <div class="row align-items-center">
        <div class="col-lg-8 mb-4 mb-lg-0">
          <div class="d-flex align-items-center mb-4">
            <img src="./images/logo_formulario.png" alt="Logo Inscrição Social IMPACTACIM 2026" class="hero-logo mr-3">
            <div>
              <span class="hero-kicker">Instituto ACIM</span>
              <div class="brand" style="font-size:24px; line-height:1.1;">Inscrição Social IMPACTACIM 2026</div>
            </div>
          </div>

          <h1 class="hero-title">Certificação IMPACTACIM: Jornada de Sustentabilidade</h1>
          <p class="hero-description">
            A Certificação IMPACTACIM: Jornada de Sustentabilidade é um reconhecimento de metodologia própria do Instituto ACIM, voltada à organizações que atuam com projetos relacionados a temática ESG (ambiental, social e governança), assim como sua contribuição para o alcance dos Objetivos de Desenvolvimento Sustentável, demonstrando compromisso em prol da sustentabilidade.
          </p>
        </div>

        <div class="col-lg-4">
          <div class="hero-panel">
            <div class="hero-panel-title">Informações importantes</div>
            <p class="mb-3">
              <span class="badge-accent">Vagas limitadas</span>
            </p>
            <p class="mb-3">
              <strong>Acesse o edital <?= date('Y') ?> </strong>
              <a target="_blank" href="https://docs.google.com/document/d/10gieVrHcRgXZAGxRnXcqEPjfaDJfNyildg333RTXAJI/edit?usp=sharing" class="hero-link">neste link</a>
            </p>
            <p class="mb-2"><strong>Em caso de dúvidas:</strong></p>
            <p class="mb-0">
              Kellen Oliveira<br>
              <a href="https://wa.me/5544998530909?text=Ol%C3%A1%2C%20tenho%20d%C3%BAvidas%20sobre%20a%20inscri%C3%A7%C3%A3o%20para%20a%20Certifica%C3%A7%C3%A3o%20IMPACTACIM." target="_blank" rel="noopener noreferrer" class="hero-contact" aria-label="Falar no WhatsApp com Kellen Oliveira">
                <span class="hero-contact-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false">
                    <path fill="currentColor" d="M17.47 14.38c-.27-.13-1.58-.78-1.82-.87c-.24-.09-.42-.13-.6.14c-.17.26-.68.87-.83 1.05c-.15.17-.31.2-.57.07c-.27-.13-1.12-.41-2.14-1.32c-.79-.7-1.33-1.57-1.48-1.84c-.15-.26-.02-.41.11-.54c.12-.12.26-.31.39-.46c.13-.15.17-.26.26-.44c.09-.17.04-.33-.02-.46c-.07-.13-.6-1.45-.82-1.98c-.22-.52-.44-.45-.6-.46h-.51c-.17 0-.46.07-.7.33c-.24.26-.92.9-.92 2.19c0 1.29.94 2.54 1.07 2.71c.13.17 1.85 2.82 4.47 3.95c.62.27 1.12.43 1.5.55c.63.2 1.21.17 1.67.1c.51-.08 1.58-.65 1.8-1.25c.22-.61.22-1.13.15-1.24c-.06-.11-.24-.17-.5-.3Z" />
                    <path fill="currentColor" d="M12.04 2C6.55 2 2.08 6.46 2.08 11.95c0 1.75.46 3.46 1.33 4.96L2 22l5.21-1.37a9.9 9.9 0 0 0 4.82 1.24h.01c5.49 0 9.96-4.46 9.96-9.95A9.88 9.88 0 0 0 19.09 4.9A9.91 9.91 0 0 0 12.04 2Zm0 18.18h-.01a8.22 8.22 0 0 1-4.19-1.15l-.3-.18l-3.09.81l.83-3.01l-.19-.31a8.2 8.2 0 0 1-1.27-4.39c0-4.54 3.69-8.24 8.23-8.24a8.18 8.18 0 0 1 5.84 2.42a8.17 8.17 0 0 1 2.41 5.82c0 4.54-3.69 8.23-8.22 8.23Z" />
                  </svg>
                </span>
                <span>44 99853-0909</span>
              </a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </header>

  <? if (count($eventos)): ?>

    <main class="container py-3">
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

          <div class="card card-soft intro-card">
            <div class="card-body p-4 p-md-5">

              <div id="alerta" class="alert d-none" role="alert"></div>

              <form id="formInscricao" novalidate enctype="multipart/form-data">

                <div class="consent-box">
                  <h3 class="mb-3">Confirmação de consentimento</h3>
                  <p>
                    Em conformidade com a LGPD (Lei Geral de Proteção de Dados - Lei Nº 13.709), tenho ciência que as informações acima serão utilizadas pelo Instituto ACIM para realizar confirmação de inscrição na Certificação 2025.
                  </p>
                  <p>
                    Informamos que ao concordar com a solicitação abaixo, serão fornecidos ao Instituto ACIM alguns dados pessoais tais como, nome completo, e-mail e telefone para efetuar contato com o(s) inscritos(s) no edital, quando necessário, motivo pelo qual manifesto a minha concordância, preenchendo os campos abaixo.
                  </p>

                  <div class="form-group mb-0 mt-4">
                    <label class="mb-2 d-block">Você concorda com o uso dos dados informados? *</label>
                    <small class="help d-block mb-2">Sua concordância é necessária para que o Instituto ACIM possa processar a inscrição e entrar em contato quando necessário.</small>
                    <div class="custom-control custom-radio mb-2">
                      <input type="radio" id="lgpd_concordo" name="lgpd_consentimento" value="sim" class="custom-control-input" required>
                      <label class="custom-control-label" for="lgpd_concordo">Sim, concordo</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="lgpd_nao_concordo" name="lgpd_consentimento" value="nao" class="custom-control-input" required>
                      <label class="custom-control-label" for="lgpd_nao_concordo">Não, não concordo</label>
                    </div>
                  </div>
                </div>

                <div id="mensagemNaoConcorda" class="alert alert-warning d-none" role="alert">
                  Agradecemos seu interesse. Pela não aceitação do uso dos dados informados, não será possível realizar a inscrição.
                </div>

                <div id="camposFormulario" class="d-none">
                  <div class="intro-note">
                    <strong>Preencha o formulário abaixo</strong> com os dados da organização e dos representantes que participarão da jornada.
                  </div>
                  <h3 class="mb-1">Dados da inscrição</h3>
                  <p class="subtitle mb-4">Selecione uma turma disponível e envie todas as informações obrigatórias para concluir sua inscrição.</p>

                  <div class="form-row">
                    <div class="form-group col-md-8">
                      <label class="mb-1">Nome da organização *</label>
                      <small class="help d-block mb-2">Informe o nome oficial da organização responsável pela inscrição.</small>
                      <input type="text" class="form-control" name="nome_organizacao" required maxlength="150"
                        placeholder="Nome da organização">
                    </div>
                    <div class="form-group col-md-4">
                      <label class="mb-1">CNPJ *</label>
                      <small class="help d-block mb-2">Informe apenas os 14 numeros do CNPJ, sem pontos, barras ou traços.</small>
                      <input type="text" class="form-control" name="cnpj" required maxlength="14"
                        placeholder="Apenas numeros" pattern="[0-9]{14}" inputmode="numeric">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-8">
                      <label class="mb-1">Endereço *</label>
                      <small class="help d-block mb-2">Preencha com rua, numero, bairro, cidade e demais informações relevantes.</small>
                      <input type="text" class="form-control" name="endereco" required maxlength="200"
                        placeholder="Rua, avenida, bairro, cidade">
                    </div>
                    <div class="form-group col-md-4">
                      <label class="mb-1">Colaboradores/voluntários *</label>
                      <small class="help d-block mb-2">Informe a quantidade total de colaboradores e/ou voluntários da organização.</small>
                      <input type="number" class="form-control" name="numero_colaboradores" required min="1" step="1"
                        placeholder="Quantidade">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-8">
                      <label class="mb-1">Nome do responsável *</label>
                      <small class="help d-block mb-2">Nome do responsável da organização que irá assinar o termo de compromisso.</small>
                      <input type="text" class="form-control" name="nome" required maxlength="120"
                        placeholder="Nome do responsável pela organização">
                    </div>
                    <div class="form-group col-md-4">
                      <label class="mb-1">CPF do responsável *</label>
                      <small class="help d-block mb-2">Informe apenas os 11 numeros do CPF do responsável, sem pontuação.</small>
                      <input type="text" class="form-control" name="cpf_responsavel" required maxlength="11"
                        placeholder="Apenas numeros" pattern="[0-9]{11}" inputmode="numeric">
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-8">
                      <label class="mb-1">E-mail do responsável *</label>
                      <small class="help d-block mb-2">Utilize um e-mail válido para receber confirmações e comunicados da inscrição.</small>
                      <input type="email" class="form-control" name="email" required maxlength="120"
                        placeholder="responsavel@organizacao.com.br">
                    </div>
                    <div class="form-group col-md-4">
                      <label class="mb-1">Telefone do responsável *</label>
                      <small class="help d-block mb-2">Informe um telefone com DDD para contato com o responsável pela inscrição.</small>
                      <input required type="tel" class="form-control" name="telefone" maxlength="20"
                        placeholder="(00) 00000-0000">
                    </div>
                  </div>

                  <div class="rep-card">
                    <div class="rep-title">Turma da participação</div>
                    <div class="form-group mb-0">
                      <small class="help d-block mb-2">Selecione uma das turmas disponíveis para participação da organização.</small>
                      <div class="table-responsive turma-table-wrap">
                        <table class="table turma-table">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Turma</th>
                              <th>Observação</th>
                              <th>Treinamento D1</th>
                              <th>Treinamento D2</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($eventos as $evento): ?>
                              <tr>
                                <td class="turma-radio-cell">
                                  <input
                                    type="radio"
                                    class="turma-radio"
                                    name="id_evento"
                                    value="<?= htmlspecialchars($evento['id_evento'], ENT_QUOTES, 'UTF-8') ?>"
                                    required
                                    aria-label="Selecionar turma <?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?>">
                                </td>
                                <td data-label="Turma"><span class="turma-cell-value"><?= htmlspecialchars($evento['titulo'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                <td data-label="Observação"><span class="turma-cell-value"><?= !empty($evento['observacao']) ? nl2br(htmlspecialchars($evento['observacao'], ENT_QUOTES, 'UTF-8')) : '-' ?></span></td>
                                <td data-label="Treinamento D1"><span class="turma-cell-value"><?= formatarDataHoraEvento($evento['data_hora'] ?? null) ?></span></td>
                                <td data-label="Treinamento D2"><span class="turma-cell-value"><?= formatarDataHoraEvento($evento['data_hora_2'] ?? null) ?></span></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>

                  <hr class="my-4">
                  <h5 class="mb-3">Representantes</h5>

                  <div class="rep-card">
                    <div class="rep-title">Representante 1</div>
                    <div class="form-group">
                      <label class="mb-1">Nome completo do representante 1 *</label>
                      <small class="help d-block mb-2">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                      <input type="text" class="form-control" name="representante_1_nome" required maxlength="120"
                        placeholder="Nome completo">
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label class="mb-1">Telefone do representante 1 *</label>
                        <small class="help d-block mb-2">Informe um telefone com DDD para contato direto com o representante.</small>
                        <input type="tel" class="form-control" name="representante_1_telefone" required maxlength="20"
                          placeholder="(00) 00000-0000">
                      </div>
                      <div class="form-group col-md-6">
                        <label class="mb-1">E-mail do representante 1 *</label>
                        <small class="help d-block mb-2">Utilize um e-mail válido para envio de informações e materiais do evento.</small>
                        <input type="email" class="form-control" name="representante_1_email" required maxlength="120"
                          placeholder="representante1@organizacao.com.br">
                      </div>
                    </div>
                  </div>

                  <div class="rep-card">
                    <div class="rep-title">Representante 2</div>
                    <div class="form-group">
                      <label class="mb-1">Nome completo do representante 2</label>
                      <small class="help d-block mb-2">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                      <input type="text" class="form-control" name="representante_2_nome" maxlength="120"
                        placeholder="Nome completo">
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label class="mb-1">Telefone do representante 2</label>
                        <small class="help d-block mb-2">Informe um telefone com DDD caso este representante também participe da comunicação.</small>
                        <input type="tel" class="form-control" name="representante_2_telefone" maxlength="20"
                          placeholder="(00) 00000-0000">
                      </div>
                      <div class="form-group col-md-6">
                        <label class="mb-1">E-mail do representante 2</label>
                        <small class="help d-block mb-2">Utilize um e-mail válido para compartilhar orientações e atualizações do evento.</small>
                        <input type="email" class="form-control" name="representante_2_email" maxlength="120"
                          placeholder="representante2@organizacao.com.br">
                      </div>
                    </div>
                  </div>

                  <div class="rep-card">
                    <div class="rep-title">Representante 3</div>
                    <div class="form-group">
                      <label class="mb-1">Nome completo do representante 3</label>
                      <small class="help d-block mb-2">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                      <input type="text" class="form-control" name="representante_3_nome" maxlength="120"
                        placeholder="Nome completo">
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label class="mb-1">Telefone do representante 3</label>
                        <small class="help d-block mb-2">Informe um telefone com DDD caso este representante precise receber contato.</small>
                        <input type="tel" class="form-control" name="representante_3_telefone" maxlength="20"
                          placeholder="(00) 00000-0000">
                      </div>
                      <div class="form-group col-md-6">
                        <label class="mb-1">E-mail do representante 3</label>
                        <small class="help d-block mb-2">Utilize um e-mail válido para envio de comunicados, se houver participação.</small>
                        <input type="email" class="form-control" name="representante_3_email" maxlength="120"
                          placeholder="representante3@organizacao.com.br">
                      </div>
                    </div>
                  </div>

                  <hr class="my-4">

                  <div class="form-group">
                    <label class="mb-2 d-block">É sua primeira participação? *</label>
                    <small class="help d-block mb-2">Informe se a organização já participou de edições anteriores da certificação.</small>
                    <div class="custom-control custom-radio mb-2">
                      <input type="radio" id="primeira_participacao_sim" name="primeira_participacao" value="sim" class="custom-control-input" required>
                      <label class="custom-control-label" for="primeira_participacao_sim">Sim, será minha primeira vez participando.</label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="primeira_participacao_nao" name="primeira_participacao" value="nao" class="custom-control-input" required>
                      <label class="custom-control-label" for="primeira_participacao_nao">Não, já participei em edições anteriores.</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="mb-1">Nome da organização no certificado *</label>
                    <small class="help d-block mb-2">Informe como o nome da organização deve aparecer no certificado e no troféu.</small>
                    <input type="text" class="form-control" name="nome_certificado" required maxlength="150"
                      placeholder="Como o nome deve aparecer no certificado e troféu">
                  </div>

                  <div class="form-group">
                    <label class="mb-1">Logo da organização *</label>
                    <small class="help d-block mb-2">Anexe a logo da organização em PDF ou imagem, com tamanho máximo de 10 MB.</small>
                    <input type="file" class="form-control-file" name="logo_organizacao" required accept=".pdf,image/*">
                  </div>

                  <div class="form-group">
                    <label class="mb-1">Como ficou sabendo da certificação? *</label>
                    <small class="help d-block mb-2">Selecione o canal pelo qual a organização conheceu a Certificação IMPACTACIM.</small>
                    <select class="form-control" name="como_soube" required>
                      <option value="">Selecione...</option>
                      <option value="E-mail">E-mail</option>
                      <option value="Redes Sociais">Redes Sociais</option>
                      <option value="Eventos do Instituto ACIM">Eventos do Instituto ACIM</option>
                      <option value="Participante de edições anteriores">Participante de edições anteriores</option>
                      <option value="Outro">Outro</option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label class="mb-1">Gostaria de indicar alguma organização para participar?</label>
                    <small class="help d-block mb-2">Se desejar, informe o nome e o contato de outra organização que possa se interessar pela certificação.</small>
                    <textarea class="form-control" name="indicacao_organizacao" rows="4" maxlength="500"
                      placeholder="Se sim, deixe o contato aqui."></textarea>
                  </div>

                  <div class="d-flex align-items-center justify-content-between mt-4 flex-wrap">
                    <small class="help mb-2 mb-md-0">
                      Ao enviar, você concorda em receber comunicações por e-mail.
                    </small>
                    <button type="submit" id="btnEnviar" class="btn btn-brand">
                      Enviar
                    </button>
                  </div>
                </div>
              </form>

            </div>
          </div>

        </div>
      </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
      crossorigin="anonymous"></script>

    <script>
      $(function() {
        const camposFormulario = $('#camposFormulario');
        const mensagemNaoConcorda = $('#mensagemNaoConcorda');
        const btnEnviar = $('#btnEnviar');
        const camposObrigatorios = camposFormulario.find(':input').not('[type="button"], [type="submit"], [type="reset"], [disabled]');

        function atualizarConsentimento() {
          const consentimento = $('input[name="lgpd_consentimento"]:checked').val();

          if (consentimento === 'sim') {
            mensagemNaoConcorda.addClass('d-none');
            camposFormulario.removeClass('d-none');
            camposObrigatorios.prop('disabled', false);
            btnEnviar.prop('disabled', false);
            return;
          }

          camposFormulario.addClass('d-none');
          camposObrigatorios.prop('disabled', true);
          btnEnviar.prop('disabled', true);

          if (consentimento === 'nao') {
            mensagemNaoConcorda.removeClass('d-none');
          } else {
            mensagemNaoConcorda.addClass('d-none');
          }
        }

        function showAlert(type, msg) {
          const alerta = $('#alerta');

          alerta
            .removeClass('d-none alert-success alert-danger alert-warning')
            .addClass('alert-' + type)
            .text(msg);

          if (type === 'danger') {
            $('html, body').animate({
              scrollTop: alerta.offset().top - 20
            }, 300);
          }
        }

        $('input[name="lgpd_consentimento"]').on('change', atualizarConsentimento);
        atualizarConsentimento();

        $(document).on('change', 'input[name="id_evento"]', function() {
          $('.turma-table tbody tr').removeClass('turma-selecionada');
          $(this).closest('tr').addClass('turma-selecionada');
        });

        $('#formInscricao').on('submit', function(e) {
          e.preventDefault();

          const form = this;
          const consentimento = $('input[name="lgpd_consentimento"]:checked').val();

          if (consentimento !== 'sim') {
            showAlert('warning', 'Para prosseguir com a inscrição, é necessário concordar com o uso dos dados informados.');
            return;
          }

          if (!form.checkValidity()) {
            form.reportValidity();
            return;
          }

          const dados = new FormData(form);

          btnEnviar.prop('disabled', true).text('Enviando...');

          $.ajax({
            url: './inscrever.php',
            method: 'POST',
            data: dados,
            dataType: 'text',
            processData: false,
            contentType: false,
            success: function(response) {
              let data = response;

              if (typeof response === 'string') {
                try {
                  data = JSON.parse(response.trim());
                } catch (e) {
                  console.error('Resposta inválida do backend:', response);
                  showAlert('danger', 'A resposta do servidor veio em formato inválido.');
                  return;
                }
              }

              if (data && data.success) {
                showAlert('success', data.mensagem || 'Inscrição enviada com sucesso.');
                $('#formInscricao').slideUp(200);
              } else {
                showAlert('danger', (data && data.erro) ? data.erro : 'Não foi possível concluir a inscrição.');
              }
            },
            error: function(xhr) {
              console.error('Erro na requisição:', xhr.responseText);
              showAlert('danger', 'Não foi possível enviar. Tente novamente.');
            },
            complete: function() {
              btnEnviar.prop('disabled', false).text('Enviar inscrição');
            }
          });
        });
      });
    </script>

  <? else: ?>
    <h1 class="text-center py-5">NÃO HÁ EVENTOS DISPONÍVEIS NO MOMENTO</h1>
  <? endif; ?>

  <footer class="page-footer">
    &copy; <?= date('Y') ?> BemFeito Sistemas
  </footer>

</body>

</html>
