delete from ouvidoria_login;

delete from ouvidoria_usuario where id_perfil = 3;

delete from ouvidoria_perfil where id_perfil = 3;

update ouvidoria_perfil set descricao = 'Cliente' where id_perfil = 2;

DROP TABLE IF EXISTS public.ouvidoria_config;

ALTER TABLE IF EXISTS public.ouvidoria_usuario DROP COLUMN IF EXISTS data_expiracao_login;

ALTER TABLE IF EXISTS public.ouvidoria_usuario DROP COLUMN IF EXISTS codigo_vendedor;

ALTER TABLE IF EXISTS public.ouvidoria_usuario DROP COLUMN IF EXISTS telefone;

ALTER TABLE IF EXISTS public.ouvidoria_usuario DROP COLUMN IF EXISTS departamento;

ALTER TABLE IF EXISTS public.ouvidoria_empresa
    ADD COLUMN logo character varying;

ALTER TABLE IF EXISTS public.ouvidoria_empresa
    ADD COLUMN cor character varying;

ALTER TABLE IF EXISTS public.ouvidoria_usuario
    ADD COLUMN id_empresa integer;
ALTER TABLE IF EXISTS public.ouvidoria_usuario
    ADD FOREIGN KEY (id_empresa)
    REFERENCES public.ouvidoria_empresa (id_empresa) MATCH SIMPLE
    ON UPDATE NO ACTION
    ON DELETE NO ACTION
    NOT VALID;