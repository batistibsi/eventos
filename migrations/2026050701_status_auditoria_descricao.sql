ALTER TABLE public.eventos_status_auditoria
	ADD COLUMN descricao varchar(500);

UPDATE public.eventos_status_auditoria
SET descricao = 'Aguradando a atribuição dos projetos'
WHERE id_status_auditoria = 1;

UPDATE public.eventos_status_auditoria
SET descricao = 'Seus projetos estao sendo auditados'
WHERE id_status_auditoria = 2;

UPDATE public.eventos_status_auditoria
SET descricao = 'Insira as suas justificativas para o auditor'
WHERE id_status_auditoria = 3;

UPDATE public.eventos_status_auditoria
SET descricao = 'Suas justificativas estao sendo revisadas'
WHERE id_status_auditoria = 4;

UPDATE public.eventos_status_auditoria
SET descricao = 'Classificacao final ja realizada'
WHERE id_status_auditoria = 5;
