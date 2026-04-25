CREATE TABLE IF NOT EXISTS public.eventos_projeto_avaliacao (
    id_projeto_avaliacao bigserial PRIMARY KEY,
    id_projeto bigint NOT NULL,
    campo varchar(80) NOT NULL,
    aprovado boolean NULL,
    comentario text NULL,
    id_usuario_avaliador integer NULL,
    created_at timestamp without time zone NOT NULL DEFAULT now(),
    updated_at timestamp without time zone NOT NULL DEFAULT now(),
    CONSTRAINT eventos_projeto_avaliacao_campo_unique UNIQUE (id_projeto, campo),
    CONSTRAINT eventos_projeto_avaliacao_id_projeto_fkey
        FOREIGN KEY (id_projeto) REFERENCES public.eventos_projeto (id_projeto) ON DELETE CASCADE,
    CONSTRAINT eventos_projeto_avaliacao_id_usuario_avaliador_fkey
        FOREIGN KEY (id_usuario_avaliador) REFERENCES public.eventos_usuario (id_usuario) ON DELETE SET NULL
);

CREATE INDEX IF NOT EXISTS eventos_projeto_avaliacao_id_projeto_idx
    ON public.eventos_projeto_avaliacao (id_projeto);
