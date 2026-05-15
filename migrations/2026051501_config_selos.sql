ALTER TABLE public.eventos_config
ADD COLUMN IF NOT EXISTS selo_iniciante_arquivo text,
ADD COLUMN IF NOT EXISTS selo_bronze_arquivo text,
ADD COLUMN IF NOT EXISTS selo_prata_arquivo text,
ADD COLUMN IF NOT EXISTS selo_ouro_arquivo text;
