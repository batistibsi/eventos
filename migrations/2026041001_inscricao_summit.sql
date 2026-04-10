create table if not exists eventos_inscricao_summit (
    id_inscricao_summit bigserial primary key,
    id_inscricao bigint not null,
    nome_representante varchar(150) not null,
    cargo_representante varchar(150) not null,
    telefone_contato varchar(20) not null,
    ordem smallint not null,
    created_at timestamp without time zone default now() not null
);

alter table eventos_inscricao_summit
    add constraint fk_eventos_inscricao_summit_inscricao
    foreign key (id_inscricao) references eventos_inscricao(id_inscricao) on delete cascade;

alter table eventos_inscricao_summit
    add constraint uq_eventos_inscricao_summit_ordem unique (id_inscricao, ordem);

alter table eventos_inscricao_summit
    add constraint ck_eventos_inscricao_summit_ordem check (ordem between 1 and 3);
