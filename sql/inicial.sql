create table eventos_evento (
  id_evento bigserial primary key,
  titulo text not null,
  data_hora timestamp not null,
  ativo boolean not null default true,
  limite_vagas integer null,
  created_at timestamp not null default now()
);

create table eventos_inscricao (
  id_inscricao bigserial primary key,
  id_evento bigint not null references eventos_evento(id_evento) on delete restrict,

  nome text not null,
  email text not null,

  status varchar not null,

  token_confirmacao text not null,
  token_expira_em timestamp not null,

  created_at timestamp not null default now(),
  confirmado_em timestamp null,

  -- token único (para link)
  constraint uq_token_confirmacao unique (token_confirmacao)
);

-- Table: public.eventos_perfil

-- DROP TABLE IF EXISTS public.eventos_perfil;

CREATE TABLE IF NOT EXISTS public.eventos_perfil
(
    id_perfil integer NOT NULL,
    descricao character varying(255) COLLATE pg_catalog."default",
    CONSTRAINT eventos_perfil_pkey PRIMARY KEY (id_perfil)
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.eventos_perfil
    OWNER to postgres;
		
	
-- Table: public.eventos_usuario

-- DROP TABLE IF EXISTS public.eventos_usuario;

CREATE TABLE IF NOT EXISTS public.eventos_usuario
(
    id_usuario serial NOT NULL,
    nome character varying(255) COLLATE pg_catalog."default" NOT NULL,
    email character varying(255) COLLATE pg_catalog."default",
    senha character(32) COLLATE pg_catalog."default" NOT NULL,
    ativo boolean DEFAULT true,
    id_perfil integer,
    CONSTRAINT eventos_usuario_pkey PRIMARY KEY (id_usuario),
    CONSTRAINT eventos_usuario_id_perfil_fkey FOREIGN KEY (id_perfil)
        REFERENCES public.eventos_perfil (id_perfil) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.eventos_usuario
    OWNER to postgres;
	
	
insert into eventos_perfil values(1,'Adminstrador');
	
insert into eventos_usuario(nome,email,senha,id_perfil)
	values('Administrador Bem Feito','batisti_bsi@hotmail.com','81dc9bdb52d04dc20036dbd8313ed055',1);


-- Table: public.eventos_login

-- DROP TABLE IF EXISTS public.eventos_login;

CREATE TABLE IF NOT EXISTS public.eventos_login
(
    id serial NOT NULL,
    id_usuario integer NOT NULL,
    data_hora timestamp without time zone DEFAULT now(),
    CONSTRAINT eventos_login_pkey PRIMARY KEY (id),
    CONSTRAINT eventos_login_id_usuario_fkey FOREIGN KEY (id_usuario)
        REFERENCES public.eventos_usuario (id_usuario) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

TABLESPACE pg_default;

ALTER TABLE IF EXISTS public.eventos_login
    OWNER to postgres;

INSERT INTO public.eventos_evento(
	titulo, data_hora, ativo, limite_vagas)
	VALUES ('Evento 1', '2026-04-01 16:00:00', true, 30),
	('Evento 2', '2026-05-01 17:00:00', true, 10),
	('Evento 3', '2026-06-01 12:00:00', true, 5);