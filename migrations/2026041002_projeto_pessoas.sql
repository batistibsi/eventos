ALTER TABLE public.eventos_projeto
    ADD COLUMN publico_tipo character varying(50);

ALTER TABLE public.eventos_projeto
    ADD COLUMN quantidade_publico_interno integer;

ALTER TABLE public.eventos_projeto
    ADD COLUMN quantidade_publico_externo integer;
