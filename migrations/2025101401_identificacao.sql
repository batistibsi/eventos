ALTER TABLE IF EXISTS public.ouvidoria_envio DROP COLUMN IF EXISTS sexo;

ALTER TABLE IF EXISTS public.ouvidoria_envio DROP COLUMN IF EXISTS idade;

ALTER TABLE IF EXISTS public.ouvidoria_envio DROP COLUMN IF EXISTS frequencia;

ALTER TABLE IF EXISTS public.ouvidoria_envio
    ADD COLUMN nome character varying;

ALTER TABLE IF EXISTS public.ouvidoria_envio
    ADD COLUMN telefone character varying;

ALTER TABLE IF EXISTS public.ouvidoria_envio
    ADD COLUMN email character varying;