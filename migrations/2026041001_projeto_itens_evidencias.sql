ALTER TABLE public.eventos_projeto
    ADD COLUMN tipo_item character varying(150);

ALTER TABLE public.eventos_projeto
    ADD COLUMN quantidade_itens numeric(12,2);

ALTER TABLE public.eventos_projeto
    ADD COLUMN unidade_medida character varying(80);

ALTER TABLE public.eventos_projeto_arquivo
    ADD COLUMN tipo_evidencia character varying(20);

UPDATE public.eventos_projeto_arquivo
   SET tipo_evidencia = 'qualitativa'
 WHERE tipo_evidencia IS NULL;

ALTER TABLE public.eventos_projeto_arquivo
    ALTER COLUMN tipo_evidencia SET NOT NULL;
