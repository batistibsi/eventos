ALTER TABLE public.eventos_inscricao ADD COLUMN cpf_responsavel character varying(14);
ALTER TABLE public.eventos_inscricao ADD COLUMN nome_organizacao character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN cnpj character varying(18);
ALTER TABLE public.eventos_inscricao ADD COLUMN endereco character varying(255);
ALTER TABLE public.eventos_inscricao ADD COLUMN numero_colaboradores integer;
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_1_nome character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_1_email character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_1_telefone character varying(20);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_2_nome character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_2_email character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_2_telefone character varying(20);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_3_nome character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_3_email character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN representante_3_telefone character varying(20);
ALTER TABLE public.eventos_inscricao ADD COLUMN primeira_participacao boolean;
ALTER TABLE public.eventos_inscricao ADD COLUMN nome_certificado character varying(150);
ALTER TABLE public.eventos_inscricao ADD COLUMN logo_organizacao character varying(255);
ALTER TABLE public.eventos_inscricao ADD COLUMN como_soube character varying(100);
ALTER TABLE public.eventos_inscricao ADD COLUMN indicacao_organizacao text;

UPDATE public.eventos_inscricao
SET
    cpf_responsavel = COALESCE(cpf_responsavel, ''),
    nome_organizacao = COALESCE(nome_organizacao, nome),
    cnpj = COALESCE(cnpj, ''),
    endereco = COALESCE(endereco, ''),
    numero_colaboradores = COALESCE(numero_colaboradores, 1),
    representante_1_nome = COALESCE(representante_1_nome, nome),
    representante_1_email = COALESCE(representante_1_email, email),
    representante_1_telefone = COALESCE(representante_1_telefone, ''),
    primeira_participacao = COALESCE(primeira_participacao, false),
    nome_certificado = COALESCE(nome_certificado, nome),
    logo_organizacao = COALESCE(logo_organizacao, ''),
    como_soube = COALESCE(como_soube, 'Nao informado');

ALTER TABLE public.eventos_inscricao ALTER COLUMN cpf_responsavel SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN nome_organizacao SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN cnpj SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN endereco SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN numero_colaboradores SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN representante_1_nome SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN representante_1_email SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN representante_1_telefone SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN primeira_participacao SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN nome_certificado SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN logo_organizacao SET NOT NULL;
ALTER TABLE public.eventos_inscricao ALTER COLUMN como_soube SET NOT NULL;