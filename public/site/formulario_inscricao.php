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

    @media (max-width: 576px) {
      .hero-title {
        font-size: 34px !important;
      }
    }
  </style>
</head>

<body>

  <header class="hero">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="d-flex align-items-center">
          <img src="./images/logo_header.png" alt="Logo" class="hero-logo mr-3">
          <div>
            <div class="brand" style="font-size:24px; line-height:1.1;">Inscreva-se no Evento</div>
            <div class="subtitle">Preencha os dados da organização e escolha uma data disponível.</div>
          </div>
        </div>

        <span class="badge-accent mt-3 mt-md-0">Vagas limitadas</span>
      </div>
    </div>
  </header>

  <? if (count($eventos)): ?>

    <main class="container py-3">
      <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

          <div class="card card-soft">
            <div class="card-body p-4 p-md-5">
              <h3 class="mb-1">Dados da inscrição</h3>
              <p class="subtitle mb-4"></p>

              <div id="alerta" class="alert d-none" role="alert"></div>

              <form id="formInscricao" novalidate enctype="multipart/form-data">
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

                                <hr class="my-4">                <h5 class="mb-3">Representantes</h5>

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

</body>

</html>
















