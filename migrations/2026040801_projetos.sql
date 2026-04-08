create table eventos_projeto(
	id_projeto serial not null primary key,
	id_usuario integer not null,
	id_evento integer not null,
	status_projeto integer not null DEFAULT 0,
	nome varchar
);

ALTER TABLE IF EXISTS public.eventos_projeto
    ADD FOREIGN KEY (id_usuario)
    REFERENCES public.eventos_usuario (id_usuario) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

ALTER TABLE IF EXISTS public.eventos_projeto
    ADD FOREIGN KEY (id_evento)
    REFERENCES public.eventos_evento (id_evento) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;