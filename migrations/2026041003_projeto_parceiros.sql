ALTER TABLE public.eventos_projeto
    ADD COLUMN quantidade_parceiros integer;

ALTER TABLE public.eventos_projeto
    ADD COLUMN parceiros character varying(255);
