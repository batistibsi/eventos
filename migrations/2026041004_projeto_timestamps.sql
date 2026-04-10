ALTER TABLE public.eventos_projeto
    ADD COLUMN created_at timestamp without time zone;

ALTER TABLE public.eventos_projeto
    ADD COLUMN updated_at timestamp without time zone;

UPDATE public.eventos_projeto
   SET created_at = now()
 WHERE created_at IS NULL;

UPDATE public.eventos_projeto
   SET updated_at = created_at
 WHERE updated_at IS NULL;

ALTER TABLE public.eventos_projeto
    ALTER COLUMN created_at SET NOT NULL;

ALTER TABLE public.eventos_projeto
    ALTER COLUMN updated_at SET NOT NULL;
