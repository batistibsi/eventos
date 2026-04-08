create table eventos_projeto_arquivo(
	id_projeto_arquivo serial not null primary key,
	id_projeto integer not null,
	nome_original varchar(255) not null,
	caminho_arquivo varchar(255) not null,
	tamanho_bytes bigint,
	tipo_mime varchar(120),
	created_at timestamp without time zone not null default now()
);

ALTER TABLE IF EXISTS public.eventos_projeto_arquivo
    ADD FOREIGN KEY (id_projeto)
    REFERENCES public.eventos_projeto (id_projeto) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE CASCADE
    NOT VALID;
