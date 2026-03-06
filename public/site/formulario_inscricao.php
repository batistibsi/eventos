<?php
$datasEvento = [
  ['value' => '2026-04-10 19:00', 'label' => '10/04/2026 - 19:00'],
  ['value' => '2026-04-12 09:00', 'label' => '12/04/2026 - 09:00'],
  ['value' => '2026-04-15 20:00', 'label' => '15/04/2026 - 20:00'],
];
?>
<!doctype html>
<html lang="pt-br">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Instituto ACIM | Responsabilidade Social</title>

  <link rel="icon" href="./favicon.ico">

  <link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N"
    crossorigin="anonymous">

  <style>
    :root {
      --brand: #183885;
      --accent: #abca69;
      --bg: #f6f8fb;
      --card: #ffffff;
      --muted: #6c757d;
    }

    body {
      background: var(--bg);
    }

    .hero {
      background: radial-gradient(1200px 500px at 10% 10%, rgba(24, 56, 133, .18), transparent 60%),
        radial-gradient(800px 400px at 90% 20%, rgba(171, 202, 105, .20), transparent 55%),
        var(--card);
      border-bottom: 1px solid rgba(0, 0, 0, .06);
      padding: 44px 0 52px;
    }

    .brand {
      color: var(--brand);
      font-weight: 800;
      letter-spacing: -.4px;
    }

    .subtitle {
      color: var(--muted);
    }

    .badge-accent {
      background: rgba(171, 202, 105, .22);
      color: #2b3a16;
      border: 1px solid rgba(171, 202, 105, .35);
      border-radius: 999px;
      padding: .35rem .6rem;
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

    .form-control:focus {
      border-color: rgba(24, 56, 133, .55);
      box-shadow: 0 0 0 .2rem rgba(24, 56, 133, .12);
    }

    .help {
      font-size: 12.5px;
      color: var(--muted);
    }

    /* Logo */
    .hero-logo {
      height: 72px;
      width: auto;
      background: #fff;
      border: 1px solid rgba(0, 0, 0, .08);
      border-radius: 12px;
      padding: 6px;
      box-shadow: 0 8px 18px rgba(0, 0, 0, .06);
    }

    @media (max-width: 576px) {
      .hero-title {
        font-size: 34px !important;
      }
    }
  </style>
</head>

<body>

  <!-- Header com logo -->
  <header class="hero">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="d-flex align-items-center">
          <!-- Troque o src pelo caminho do seu logo -->
          <img src="./images/logo_header.png" alt="Logo" class="hero-logo mr-3">
          <div>
            <div class="brand" style="font-size:24px; line-height:1.1;">Inscreva-se no Evento</div>
            <div class="subtitle">Preencha seus dados e escolha uma data disponível.</div>
          </div>
        </div>

        <span class="badge-accent mt-3 mt-md-0">Vagas limitadas</span>
      </div>

    </div>
  </header>

  <main class="container py-5">
    <div class="row justify-content-center">
      <div class="col-lg-8">

        <div class="card card-soft">
          <div class="card-body p-4 p-md-5">
            <h3 class="mb-1">Dados da inscrição</h3>
            <p class="subtitle mb-4">Campos com * são obrigatórios.</p>

            <div id="alerta" class="alert d-none" role="alert"></div>

            <form id="formInscricao" novalidate>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label class="mb-1">Nome *</label>
                  <input type="text" class="form-control" name="nome" required maxlength="80"
                    placeholder="Seu nome completo">
                </div>
                <div class="form-group col-md-6">
                  <label class="mb-1">E-mail *</label>
                  <input type="email" class="form-control" name="email" required maxlength="120"
                    placeholder="seuemail@dominio.com">
                </div>
              </div>

              <div class="form-group">
                <label class="mb-1">Data do evento *</label>
                <select class="form-control" name="data_evento" required>
                  <option value="">Selecione uma data...</option>
                  <?php foreach ($datasEvento as $d): ?>
                    <option value="<?= htmlspecialchars($d['value'], ENT_QUOTES, 'UTF-8') ?>">
                      <?= htmlspecialchars($d['label'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="help">As datas disponíveis para o evento.</small>
              </div>

              <div class="d-flex align-items-center justify-content-between mt-4">
                <small class="help mb-0">
                  Ao enviar, você concorda em receber a confirmação por e-mail.
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
        $('#alerta')
          .removeClass('d-none alert-success alert-danger alert-warning')
          .addClass('alert-' + type)
          .text(msg);
      }

      $('#formInscricao').on('submit', function(e) {
        e.preventDefault();

        const form = this;
        if (!form.checkValidity()) {
          form.reportValidity();
          return;
        }

        const dados = $(form).serialize();

        $('#btnEnviar').prop('disabled', true).text('Enviando...');

        $.ajax({
          url: '/api/inscricao', // <- troque pela sua rota PHP
          method: 'POST',
          data: dados,
          success: function() {
            showAlert('success', 'Inscrição enviada! Verifique seu e-mail.');
            form.reset();
          },
          error: function() {
            showAlert('danger', 'Não foi possível enviar. Tente novamente.');
          },
          complete: function() {
            $('#btnEnviar').prop('disabled', false).text('Enviar inscrição');
          }
        });
      });
    });
  </script>

</body>

</html>