create table eventos_status_auditoria(
	id_status_auditoria serial not null primary key,
	nome varchar,
	ordem integer not null
);

create table eventos_auditoria_perfil(
	id_status_auditoria integer,
	id_perfil integer,
    CONSTRAINT eventos_auditoria_perfil_pkey PRIMARY KEY (id_status_auditoria,id_perfil)
);

ALTER TABLE IF EXISTS public.eventos_auditoria_perfil
    ADD FOREIGN KEY (id_status_auditoria)
    REFERENCES public.eventos_status_auditoria (id_status_auditoria) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

ALTER TABLE IF EXISTS public.eventos_auditoria_perfil
    ADD FOREIGN KEY (id_perfil)
    REFERENCES public.eventos_perfil (id_perfil) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;


INSERT INTO public.eventos_status_auditoria(nome, ordem)
	VALUES 
('Entrada Projetos', 1),
('Auditoria Inicial', 2),
('Feedback Empresa', 3),
('Revisao Auditoria', 4),
('Classificação', 5);

INSERT INTO public.eventos_auditoria_perfil(id_status_auditoria, id_perfil)
	VALUES 
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(2, 2),
(3, 2),
(4, 2),
(5, 2);

alter table eventos_inscricao add column id_status_auditoria integer;
alter table eventos_inscricao add column id_auditor integer;

ALTER TABLE IF EXISTS public.eventos_inscricao
    ADD FOREIGN KEY (id_status_auditoria)
    REFERENCES public.eventos_status_auditoria (id_status_auditoria) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

ALTER TABLE IF EXISTS public.eventos_inscricao
    ADD FOREIGN KEY (id_auditor)
    REFERENCES public.eventos_usuario (id_usuario) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;