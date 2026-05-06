CREATE TABLE IF NOT EXISTS public.eventos_projeto_avaliacao_arquivo (
    id_projeto_avaliacao_arquivo bigserial PRIMARY KEY,
    id_projeto bigint NOT NULL,
    campo varchar(80) NOT NULL,
    nome_original varchar(255) NOT NULL,
    caminho_arquivo varchar(255) NOT NULL,
    tamanho_bytes bigint NULL,
    tipo_mime varchar(120) NULL,
    created_at timestamp without time zone NOT NULL DEFAULT now(),
    CONSTRAINT eventos_projeto_avaliacao_arquivo_id_projeto_fkey
        FOREIGN KEY (id_projeto) REFERENCES public.eventos_projeto (id_projeto) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS eventos_projeto_avaliacao_arquivo_id_projeto_idx
    ON public.eventos_projeto_avaliacao_arquivo (id_projeto);

CREATE INDEX IF NOT EXISTS eventos_projeto_avaliacao_arquivo_id_projeto_campo_idx
    ON public.eventos_projeto_avaliacao_arquivo (id_projeto, campo);
