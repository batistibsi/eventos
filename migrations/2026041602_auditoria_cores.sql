ALTER TABLE public.eventos_status_auditoria
	ADD COLUMN cor varchar(7);

UPDATE public.eventos_status_auditoria
SET cor = '#2563EB'
WHERE ordem = 1;

UPDATE public.eventos_status_auditoria
SET cor = '#7C3AED'
WHERE ordem = 2;

UPDATE public.eventos_status_auditoria
SET cor = '#D97706'
WHERE ordem = 3;

UPDATE public.eventos_status_auditoria
SET cor = '#DC2626'
WHERE ordem = 4;

UPDATE public.eventos_status_auditoria
SET cor = '#059669'
WHERE ordem = 5;

UPDATE public.eventos_status_auditoria
SET cor = '#475569'
WHERE cor IS NULL;

ALTER TABLE public.eventos_status_auditoria
	ALTER COLUMN cor SET NOT NULL;
