alter table eventos_projeto add column ativo boolean default true;

update eventos_projeto set ativo = true where ativo is null;

alter table eventos_projeto alter column ativo set not null;
