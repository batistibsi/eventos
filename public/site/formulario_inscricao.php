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
      --brand: #183885;
      --accent: #abca69;
      --brand-dark: #0f255d;
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
        linear-gradient(135deg, var(--brand-dark) 0%, var(--brand) 58%, #224ea6 100%);
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
      font-size: 13px;
      font-weight: 800;
      letter-spacing: .04em;
      text-transform: uppercase;
      margin-bottom: 14px;
    }

    .hero-logo {
      height: 72px;
      width: auto;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, .08);
      border-radius: 12px;
      padding: 6px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
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
  </style>
</head>

<body>

  <header class="hero">
    <div class="container hero-wrap">
      <div class="row align-items-center">
        <div class="col-lg-8 mb-4 mb-lg-0">
          <div class="d-flex align-items-center mb-4">
            <img src="./images/logo_header.png" alt="Logo Instituto ACIM" class="hero-logo mr-3">
            <div>
              <span class="hero-kicker">Instituto ACIM</span>
              <div class="brand" style="font-size:24px; line-height:1.1;">Inscrição Social IMPACTACIM 2026</div>
            </div>
          </div>

          <h1 class="hero-title">Certificação IMPACTACIM: Jornada de Sustentabilidade</h1>
          <p class="hero-description">
            A Certificação IMPACTACIM: Jornada de Sustentabilidade é um reconhecimento de metodologia própria do Instituto ACIM, voltada à organizações que atuam com projetos relacionados à temática ESG (ambiental, social e governança), assim como sua contribuição para o alcance dos Objetivos de Desenvolvimento Sustentável, demonstrando compromisso em prol da sustentabilidade.
          </p>
        </div>

        <div class="col-lg-4">
          <div class="hero-panel">
            <div class="hero-panel-title">Informações importantes</div>
            <p class="mb-3">
              <span class="badge-accent">Vagas limitadas</span>
            </p>
            <p class="mb-3">
              <strong>Acesse o regulamento da edição <?= date('Y') ?></strong><br>
              <a target="_blank" href="https://drive.google.com/file/d/15djoXHqN02QFcBM1dvmroazq5voK4hm3/view" class="hero-link">neste link</a>
            </p>
            <p class="mb-2"><strong>Em caso de dúvidas</strong></p>
            <p class="mb-0">
              Henrique Nascimento<br>
              <a href="https://wa.me/5544999842554?text=Ol%C3%A1%2C%20tenho%20d%C3%BAvidas%20sobre%20a%20inscri%C3%A7%C3%A3o%20para%20a%20Certifica%C3%A7%C3%A3o%20IMPACTACIM." target="_blank" rel="noopener noreferrer" class="hero-contact" aria-label="Falar no WhatsApp com Henrique Nascimento">
                <span class="hero-contact-icon" aria-hidden="true">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" focusable="false">
                    <path fill="currentColor" d="M17.47 14.38c-.27-.13-1.58-.78-1.82-.87c-.24-.09-.42-.13-.6.14c-.17.26-.68.87-.83 1.05c-.15.17-.31.2-.57.07c-.27-.13-1.12-.41-2.14-1.32c-.79-.7-1.33-1.57-1.48-1.84c-.15-.26-.02-.41.11-.54c.12-.12.26-.31.39-.46c.13-.15.17-.26.26-.44c.09-.17.04-.33-.02-.46c-.07-.13-.6-1.45-.82-1.98c-.22-.52-.44-.45-.6-.46h-.51c-.17 0-.46.07-.7.33c-.24.26-.92.9-.92 2.19c0 1.29.94 2.54 1.07 2.71c.13.17 1.85 2.82 4.47 3.95c.62.27 1.12.43 1.5.55c.63.2 1.21.17 1.67.1c.51-.08 1.58-.65 1.8-1.25c.22-.61.22-1.13.15-1.24c-.06-.11-.24-.17-.5-.3Z" />
                    <path fill="currentColor" d="M12.04 2C6.55 2 2.08 6.46 2.08 11.95c0 1.75.46 3.46 1.33 4.96L2 22l5.21-1.37a9.9 9.9 0 0 0 4.82 1.24h.01c5.49 0 9.96-4.46 9.96-9.95A9.88 9.88 0 0 0 19.09 4.9A9.91 9.91 0 0 0 12.04 2Zm0 18.18h-.01a8.22 8.22 0 0 1-4.19-1.15l-.3-.18l-3.09.81l.83-3.01l-.19-.31a8.2 8.2 0 0 1-1.27-4.39c0-4.54 3.69-8.24 8.23-8.24a8.18 8.18 0 0 1 5.84 2.42a8.17 8.17 0 0 1 2.41 5.82c0 4.54-3.69 8.23-8.22 8.23Z" />
                  </svg>
                </span>
                <span>44 99984-2554</span>
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

                <div class="intro-note">
                  <strong>Preencha o formulário abaixo</strong> com os dados da organização e dos representantes que participarão da jornada.
                </div>
                <h3 class="mb-1">Dados da inscrição</h3>
                <p class="subtitle mb-4">Selecione uma data disponível e envie todas as informações obrigatórias para concluir sua inscrição.</p>

                <div class="form-row">
                  <div class="form-group col-md-8">
                    <label class="mb-1">Nome do responsável *</label>
                    <input type="text" class="form-control" name="nome" required maxlength="120"
                      placeholder="Nome do responsável pela organização">
                    <small class="help">Nome do responsável da organização que irá assinar o termo de compromisso.</small>
                  </div>
                  <div class="form-group col-md-4">
                    <label class="mb-1">CPF do responsável *</label>
                    <input type="text" class="form-control" name="cpf_responsavel" required maxlength="11"
                      placeholder="Apenas numeros" pattern="[0-9]{11}" inputmode="numeric">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-5">
                    <label class="mb-1">E-mail do responsável *</label>
                    <input type="email" class="form-control" name="email" required maxlength="120"
                      placeholder="responsavel@organizacao.com.br">
                  </div>
                  <div class="form-group col-md-7">
                    <label class="mb-1">Nome da organização *</label>
                    <input type="text" class="form-control" name="nome_organizacao" required maxlength="150"
                      placeholder="Nome da organização">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-4">
                    <label class="mb-1">CNPJ *</label>
                    <input type="text" class="form-control" name="cnpj" required maxlength="14"
                      placeholder="Apenas numeros" pattern="[0-9]{14}" inputmode="numeric">
                    <small class="help">Informe apenas os 14 numeros do CNPJ.</small>
                  </div>
                  <div class="form-group col-md-8">
                    <label class="mb-1">Endereço *</label>
                    <input type="text" class="form-control" name="endereco" required maxlength="200"
                      placeholder="Rua, avenida, bairro, cidade">
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-5">
                    <label class="mb-1">Número de colaboradores/voluntários *</label>
                    <input type="number" class="form-control" name="numero_colaboradores" required min="1" step="1"
                      placeholder="Quantidade">
                  </div>
                  <div class="form-group col-md-7">
                    <label class="mb-1">Data do evento *</label>
                    <select class="form-control" name="id_evento" required>
                      <option value="">Selecione uma data...</option>
                      <?php foreach ($eventos as $evento): ?>
                        <option value="<?= $evento['id_evento'] ?>">
                          <?= htmlspecialchars($evento['label'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <small class="help">As datas disponíveis para o evento.</small>
                  </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Representantes</h5>

                <div class="rep-card">
                  <div class="rep-title">Representante 1</div>
                  <div class="form-group">
                    <label class="mb-1">Nome completo do representante 1 *</label>
                    <input type="text" class="form-control" name="representante_1_nome" required maxlength="120"
                      placeholder="Nome completo">
                    <small class="help">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label class="mb-1">Telefone do representante 1 *</label>
                      <input type="tel" class="form-control" name="representante_1_telefone" required maxlength="20"
                        placeholder="(00) 00000-0000">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="mb-1">E-mail do representante 1 *</label>
                      <input type="email" class="form-control" name="representante_1_email" required maxlength="120"
                        placeholder="representante1@organizacao.com.br">
                    </div>
                  </div>
                </div>

                <div class="rep-card">
                  <div class="rep-title">Representante 2</div>
                  <div class="form-group">
                    <label class="mb-1">Nome completo do representante 2</label>
                    <input type="text" class="form-control" name="representante_2_nome" maxlength="120"
                      placeholder="Nome completo">
                    <small class="help">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label class="mb-1">Telefone do representante 2</label>
                      <input type="tel" class="form-control" name="representante_2_telefone" maxlength="20"
                        placeholder="(00) 00000-0000">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="mb-1">E-mail do representante 2</label>
                      <input type="email" class="form-control" name="representante_2_email" maxlength="120"
                        placeholder="representante2@organizacao.com.br">
                    </div>
                  </div>
                </div>

                <div class="rep-card">
                  <div class="rep-title">Representante 3</div>
                  <div class="form-group">
                    <label class="mb-1">Nome completo do representante 3</label>
                    <input type="text" class="form-control" name="representante_3_nome" maxlength="120"
                      placeholder="Nome completo">
                    <small class="help">Preencha corretamente, pois o nome será utilizado para emissão dos certificados de participação.</small>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-6">
                      <label class="mb-1">Telefone do representante 3</label>
                      <input type="tel" class="form-control" name="representante_3_telefone" maxlength="20"
                        placeholder="(00) 00000-0000">
                    </div>
                    <div class="form-group col-md-6">
                      <label class="mb-1">E-mail do representante 3</label>
                      <input type="email" class="form-control" name="representante_3_email" maxlength="120"
                        placeholder="representante3@organizacao.com.br">
                    </div>
                  </div>
                </div>

                <hr class="my-4">

                <div class="form-group">
                  <label class="mb-2 d-block">É sua primeira participação? *</label>
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
                  <input type="text" class="form-control" name="nome_certificado" required maxlength="150"
                    placeholder="Como o nome deve aparecer no certificado e troféu">
                  <small class="help">Informe como o nome da empresa deve aparecer nos materiais.</small>
                </div>

                <div class="form-group">
                  <label class="mb-1">Logo da organização *</label>
                  <input type="file" class="form-control-file" name="logo_organizacao" required accept=".pdf,image/*">
                  <small class="help">Anexe a logo da organização. Arquivos aceitos: PDF ou imagem até 10 MB.</small>
                </div>

                <div class="form-group">
                  <label class="mb-1">Como ficou sabendo da certificação? *</label>
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

        $('#formInscricao').on('submit', function(e) {
          e.preventDefault();

          const form = this;
          if (!form.checkValidity()) {
            form.reportValidity();
            return;
          }

          const dados = new FormData(form);

          $('#btnEnviar').prop('disabled', true).text('Enviando...');

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
              $('#btnEnviar').prop('disabled', false).text('Enviar inscrição');
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