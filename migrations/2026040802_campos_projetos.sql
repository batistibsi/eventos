ALTER TABLE public.eventos_projeto ADD COLUMN data_inicializacao date;
ALTER TABLE public.eventos_projeto ADD COLUMN data_finalizacao date;
ALTER TABLE public.eventos_projeto ADD COLUMN justificativa text;
ALTER TABLE public.eventos_projeto ADD COLUMN area_atuacao character varying(30);
ALTER TABLE public.eventos_projeto ADD COLUMN objetivos text;
ALTER TABLE public.eventos_projeto ADD COLUMN ods_principal character varying(10);
ALTER TABLE public.eventos_projeto ADD COLUMN demais_ods_relacionados text;
