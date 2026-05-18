ALTER TABLE public.eventos_inscricao
    ADD COLUMN aceite_termo boolean NOT NULL DEFAULT false;

ALTER TABLE public.eventos_config
    ADD COLUMN help_termo_aceite_projetos text;

UPDATE public.eventos_config
   SET help_termo_aceite_projetos = COALESCE(
       help_termo_aceite_projetos,
       'TERMO DE ACEITE PARA ACESSO AOS PROJETOS

Declaro que li e estou de acordo com as condicoes apresentadas para participar da etapa de cadastro e acompanhamento dos projetos vinculados a esta inscricao.

Comprometo-me a fornecer informacoes veridicas, manter os dados atualizados e respeitar os criterios, prazos e orientacoes definidos pela organizacao do evento.

Ao marcar o aceite, reconheco minha responsabilidade sobre as informacoes e materiais que forem enviados nesta area.'
   );
