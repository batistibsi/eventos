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

  -- evita duplicar inscrição do mesmo email no mesmo evento
  constraint uq_evento_email unique (id_evento, email),

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