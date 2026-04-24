create table if not exists eventos_forma_pagamento (
    id_forma_pagamento serial primary key,
    descricao varchar(120) not null,
    ativo boolean not null default true,
    created_at timestamp without time zone not null default now()
);

do $$
begin
    if not exists (
        select 1
          from pg_constraint
         where conname = 'uq_eventos_forma_pagamento_descricao'
    ) then
        alter table eventos_forma_pagamento
            add constraint uq_eventos_forma_pagamento_descricao unique (descricao);
    end if;
end $$;

alter table eventos_inscricao
    add column if not exists id_forma_pagamento integer;

alter table if exists public.eventos_inscricao
    drop constraint if exists eventos_inscricao_id_forma_pagamento_fkey;

alter table if exists public.eventos_inscricao
    add constraint eventos_inscricao_id_forma_pagamento_fkey
    foreign key (id_forma_pagamento) references public.eventos_forma_pagamento (id_forma_pagamento);
