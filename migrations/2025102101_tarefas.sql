insert into eventos_perfil values(3,'Relator');

-- Table: eventos_tipo_tarefa

-- DROP TABLE IF EXISTS eventos_tipo_tarefa;

CREATE TABLE IF NOT EXISTS eventos_tipo_tarefa
(
    id_tipo_tarefa serial NOT NULL PRIMARY KEY,
	nome varchar not null,
    kanban BOOLEAN	
);

-- Table: eventos_tipo_tarefa

-- DROP TABLE IF EXISTS eventos_tipo_tarefa;

CREATE TABLE IF NOT EXISTS eventos_tipo_tarefa_permissao
(
    id_tipo_tarefa INTEGER NOT NULL,
	id_perfil INTEGER NOT NULL,
	visualizar BOOLEAN,
	realizar BOOLEAN,
	atribuir BOOLEAN,
    id_tipo_tarefa_sequencia INTEGER
);

ALTER TABLE IF EXISTS public.eventos_tipo_tarefa_permissao
    ADD PRIMARY KEY (id_tipo_tarefa,id_perfil)
    INCLUDE (id_perfil);
ALTER TABLE IF EXISTS public.eventos_tipo_tarefa_permissao
    ADD FOREIGN KEY (id_perfil)
    REFERENCES public.eventos_perfil (id_perfil) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;
	ALTER TABLE IF EXISTS public.eventos_tipo_tarefa_permissao
    ADD FOREIGN KEY (id_tipo_tarefa)
    REFERENCES public.eventos_tipo_tarefa (id_tipo_tarefa) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;
ALTER TABLE IF EXISTS public.eventos_tipo_tarefa_permissao
    ADD FOREIGN KEY (id_tipo_tarefa_sequencia)
    REFERENCES public.eventos_tipo_tarefa (id_tipo_tarefa) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

-- Table: eventos_tarefa

-- DROP TABLE IF EXISTS eventos_tarefa;

CREATE TABLE IF NOT EXISTS eventos_tarefa
(
    id_tarefa serial NOT NULL PRIMARY KEY,
	id_envio integer NOT NULL,
    id_tipo_tarefa integer NOT NULL,
    data_abertura timestamp without time zone NOT NULL DEFAULT NOW(),
	data_fechamento timestamp without time zone,
	comentario varchar,
	comentario_interno varchar
);

ALTER TABLE IF EXISTS public.eventos_tarefa
    ADD FOREIGN KEY (id_envio)
    REFERENCES public.eventos_envio (id_envio) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

ALTER TABLE IF EXISTS public.eventos_tarefa
    ADD FOREIGN KEY (id_tipo_tarefa)
    REFERENCES public.eventos_tipo_tarefa (id_tipo_tarefa) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;

INSERT INTO eventos_tipo_tarefa(nome,kanban)
VALUES
('Não iniciado',true),
('Em análise',true),
('Tratativa Interna',true),
('Aguardando Empresa',true),
('Devolutiva da Empresa',true),
('Feedback ao Relator',true),
('Devolutiva ao relator',true),
('Incompleto',true),
('Não procede',false),
('Concluído',false);

delete from eventos_tipo_tarefa_permissao;

INSERT INTO public.eventos_tipo_tarefa_permissao(
	id_perfil, id_tipo_tarefa, visualizar, realizar, atribuir, id_tipo_tarefa_sequencia)
VALUES 
(2, 1, true, false, false,NULL),
(2, 2, true, false, false,NULL),
(2, 3, true, false, false,NULL),
(2, 4, true, true, false,5),
(2, 5, true, false, true,NULL),
(2, 6, true, false, false,NULL),
(2, 7, true, false, false,NULL),
(2, 8, true, false, false,NULL),
(2, 9, true, false, false,NULL),
(2, 10, true, false, false,NULL),

(3, 1, true, false, false,NULL),
(3, 2, true, false, false,NULL),
(3, 3, true, false, false,NULL),
(3, 4, true, false, false,NULL),
(3, 5, true, false, false,NULL),
(3, 6, true, true, false,7),
(3, 7, true, false, true,NULL),
(3, 8, true, true, false,7),
(3, 9, true, true, false,7),
(3, 10, true, false, false,NULL);