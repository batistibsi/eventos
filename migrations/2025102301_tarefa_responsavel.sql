alter table ouvidoria_tarefa add column id_usuario integer;

ALTER TABLE IF EXISTS public.ouvidoria_tarefa
    ADD FOREIGN KEY (id_usuario)
    REFERENCES public.ouvidoria_usuario (id_usuario) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;