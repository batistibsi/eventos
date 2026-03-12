alter table eventos_tarefa add column id_usuario integer;

ALTER TABLE IF EXISTS public.eventos_tarefa
    ADD FOREIGN KEY (id_usuario)
    REFERENCES public.eventos_usuario (id_usuario) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;