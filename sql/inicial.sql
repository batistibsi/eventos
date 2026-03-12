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

create table eventos_config (
  id_config bigserial primary key,
  base_url_confirmacao text not null,
  token_validade_horas int not null default 24,

  smtp_host text not null,
  smtp_user text not null,
  smtp_pass text not null,
  smtp_port int not null default 587,
  smtp_secure text not null default 'tls', -- tls | ssl | none

  from_email text not null,
  from_name text not null,

  ativo boolean not null default true,
  updated_at timestamp not null default now()
);



INSERT INTO public.eventos_evento(
	titulo, data_hora, ativo, limite_vagas)
	VALUES ('Evento 1', '2026-04-01 16:00:00', true, 30),
	('Evento 2', '2026-05-01 17:00:00', true, 10),
	('Evento 3', '2026-06-01 12:00:00', true, 5);