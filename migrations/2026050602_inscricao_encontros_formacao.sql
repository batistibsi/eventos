ALTER TABLE public.eventos_inscricao
    ADD COLUMN IF NOT EXISTS encontro_formacao_1 boolean;

ALTER TABLE public.eventos_inscricao
    ADD COLUMN IF NOT EXISTS encontro_formacao_2 boolean;
