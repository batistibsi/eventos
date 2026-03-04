<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Página de Inscrição</title>

  <!-- Bootstrap 4.6 (jsDelivr) -->
  <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
        crossorigin="anonymous">

  <style>
    body { background: #f8fafc; }
    .hero {
      padding: 70px 0;
      background: #ffffff;
      border-bottom: 1px solid #e9ecef;
    }
    .hero h1 { font-weight: 700; }
    .card-soft {
      border: 1px solid #e9ecef;
      border-radius: 16px;
      box-shadow: 0 8px 20px rgba(0,0,0,.06);
    }
  </style>

  <!-- reCAPTCHA v2 -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>

  <!-- PÁGINA HOME -->
  <section id="paginaHome">
    <header class="hero">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-7">
            <h1 class="mb-3">Participe do nosso evento</h1>
            <p class="lead text-muted mb-4">
              Um texto de apresentação aqui. Explique o objetivo, benefícios, data e o que a pessoa ganha ao se inscrever.
            </p>
            <button id="btnIrInscricao" class="btn btn-primary btn-lg">
              Inscreva-se
            </button>
          </div>
          <div class="col-lg-5 mt-4 mt-lg-0">
            <div class="card card-soft">
              <div class="card-body">
                <h5 class="mb-2">Resumo</h5>
                <ul class="mb-0 text-muted">
                  <li>Data: 10/04/2026</li>
                  <li>Horário: 19:00</li>
                  <li>Local: Online</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main class="container py-5">
      <div class="row">
        <div class="col-lg-8">
          <h3>Sobre</h3>
          <p class="text-muted">
            Detalhes do evento/curso/inscrição. Pode colocar agenda, palestrantes, perguntas frequentes, etc.
          </p>
        </div>
      </div>
    </main>
  </section>

  <!-- PÁGINA INSCRIÇÃO -->
  <section id="paginaInscricao" class="d-none">
    <div class="container py-5">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Formulário de Inscrição</h2>
        <button id="btnVoltarHome" class="btn btn-outline-secondary">
          Voltar
        </button>
      </div>

      <div class="card card-soft">
        <div class="card-body p-4 p-md-5">

          <div id="alerta" class="alert d-none" role="alert"></div>

          <form id="formInscricao" novalidate>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Nome *</label>
                <input type="text" class="form-control" name="nome" required maxlength="80">
              </div>
              <div class="form-group col-md-6">
                <label>E-mail *</label>
                <input type="email" class="form-control" name="email" required maxlength="120">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Telefone</label>
                <input type="text" class="form-control" name="telefone" maxlength="20">
              </div>
              <div class="form-group col-md-6">
                <label>Cidade</label>
                <input type="text" class="form-control" name="cidade" maxlength="60">
              </div>
            </div>

            <div class="form-group">
              <label>Mensagem</label>
              <textarea class="form-control" name="mensagem" rows="3" maxlength="500"></textarea>
            </div>

            <div class="form-group">
              <div class="g-recaptcha" data-sitekey="SUA_SITE_KEY_AQUI"></div>
              <small class="text-muted">Marque o captcha para enviar.</small>
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary" id="btnEnviar">Enviar inscrição</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </section>

  <!-- jQuery + Bootstrap 4 -->
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
          crossorigin="anonymous"></script>

  <script>
    function irParaInscricao() {
      $('#paginaHome').addClass('d-none');
      $('#paginaInscricao').removeClass('d-none');
      window.scrollTo(0, 0);

      $('#alerta').addClass('d-none').removeClass('alert-success alert-danger').text('');
      $('#formInscricao')[0].reset();
      if (window.grecaptcha) grecaptcha.reset();
    }

    function voltarParaHome() {
      $('#paginaInscricao').addClass('d-none');
      $('#paginaHome').removeClass('d-none');
      window.scrollTo(0, 0);
    }

    $(function () {
      $('#btnIrInscricao').on('click', irParaInscricao);
      $('#btnVoltarHome').on('click', voltarParaHome);

      $('#formInscricao').on('submit', function (e) {
        e.preventDefault();

        const form = this;
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const captcha = (window.grecaptcha) ? grecaptcha.getResponse() : '';
        if (!captcha) {
          $('#alerta')
            .removeClass('d-none')
            .removeClass('alert-success')
            .addClass('alert alert-danger')
            .text('Por favor, confirme o captcha.');
          return;
        }

        const dados = $(form).serializeArray().reduce((acc, x) => {
          acc[x.name] = x.value;
          return acc;
        }, {});
        dados['g-recaptcha-response'] = captcha;

        $('#btnEnviar').prop('disabled', true).text('Enviando...');

        $.ajax({
          url: '/api/inscricao', // <- sua rota
          method: 'POST',
          data: dados,
          success: function () {
            $('#alerta')
              .removeClass('d-none')
              .removeClass('alert-danger')
              .addClass('alert alert-success')
              .text('Inscrição enviada com sucesso!');

            setTimeout(voltarParaHome, 1200);
          },
          error: function () {
            $('#alerta')
              .removeClass('d-none')
              .removeClass('alert-success')
              .addClass('alert alert-danger')
              .text('Não foi possível enviar. Tente novamente.');

            if (window.grecaptcha) grecaptcha.reset();
          },
          complete: function () {
            $('#btnEnviar').prop('disabled', false).text('Enviar inscrição');
          }
        });
      });
    });
  </script>

</body>
</html>