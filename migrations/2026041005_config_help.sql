ALTER TABLE public.eventos_config
    ADD COLUMN help_titulo character varying(150);

ALTER TABLE public.eventos_config
    ADD COLUMN help_subtitulo character varying(255);

ALTER TABLE public.eventos_config
    ADD COLUMN help_conteudo text;

ALTER TABLE public.eventos_config
    ADD COLUMN help_contato_nome character varying(150);

ALTER TABLE public.eventos_config
    ADD COLUMN help_contato_whatsapp character varying(20);

UPDATE public.eventos_config
   SET help_titulo = COALESCE(help_titulo, 'Cadastro de Projetos'),
       help_subtitulo = COALESCE(help_subtitulo, 'Orientacoes para preencher corretamente o formulario e evitar problemas no envio.'),
       help_conteudo = COALESCE(help_conteudo, 'O cadastro do projeto deve ser preenchido com atencao, contemplando todas as informacoes solicitadas ao longo do formulario.

Preencha os dados principais do projeto, como nome, datas, justificativa, objetivos e ODS, sempre de acordo com a atuacao e os resultados efetivamente realizados.

Inclua as evidencias qualitativas e quantitativas em seus respectivos blocos, respeitando o limite de ate 5 arquivos por area.

Revise os campos antes de submeter, pois apos o envio para analise o projeto nao podera mais ser editado.

Quando houver campos sobre itens, pessoas, parceiros ou outros dados quantitativos, informe valores reais e coerentes com as evidencias anexadas.'),
       help_contato_nome = COALESCE(help_contato_nome, 'Henrique Nascimento'),
       help_contato_whatsapp = COALESCE(help_contato_whatsapp, '44997399515')
 WHERE id_config = 1;
