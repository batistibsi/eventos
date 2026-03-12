delete from eventos_login;

delete from eventos_usuario where id_perfil = 3;

delete from eventos_perfil where id_perfil = 3;

update eventos_perfil set descricao = 'Cliente' where id_perfil = 2;

DROP TABLE IF EXISTS public.eventos_config;

ALTER TABLE IF EXISTS public.eventos_usuario DROP COLUMN IF EXISTS data_expiracao_login;

ALTER TABLE IF EXISTS public.eventos_usuario DROP COLUMN IF EXISTS codigo_vendedor;

ALTER TABLE IF EXISTS public.eventos_usuario DROP COLUMN IF EXISTS telefone;

ALTER TABLE IF EXISTS public.eventos_usuario DROP COLUMN IF EXISTS departamento;

ALTER TABLE IF EXISTS public.eventos_empresa
    ADD COLUMN logo character varying;

ALTER TABLE IF EXISTS public.eventos_empresa
    ADD COLUMN cor character varying;

ALTER TABLE IF EXISTS public.eventos_usuario
    ADD COLUMN id_empresa integer;
ALTER TABLE IF EXISTS public.eventos_usuario
    ADD FOREIGN KEY (id_empresa)
    REFERENCES public.eventos_empresa (id_empresa) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;