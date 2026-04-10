update eventos_evento set data_summit = '01-11-2026';

alter table eventos_evento add column data_inscricao_summit date;

update eventos_evento
   set data_inscricao_summit = data_summit
 where data_inscricao_summit is null;

alter table eventos_evento alter column data_inscricao_summit set not null;
