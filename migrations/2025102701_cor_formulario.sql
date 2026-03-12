alter table eventos_formulario add column cor varchar;

update eventos_formulario set cor = '#7a267b' where id_formulario = 1;
update eventos_formulario set cor = '#ffb600' where id_formulario = 2;
update eventos_formulario set cor = '#f66eb0' where id_formulario = 3;
update eventos_formulario set cor = '#01bda5' where id_formulario = 4;
update eventos_formulario set cor = '#0e5394' where id_formulario = 5;
update eventos_formulario set cor = '#f17722' where id_formulario = 6;