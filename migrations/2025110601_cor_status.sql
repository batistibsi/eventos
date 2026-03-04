alter table ouvidoria_tipo_tarefa add column cor varchar;


update ouvidoria_tipo_tarefa set cor = '#9E9E9E' where id_tipo_tarefa = 1;
update ouvidoria_tipo_tarefa set cor = '#1976D2' where id_tipo_tarefa = 2;
update ouvidoria_tipo_tarefa set cor = '#7E57C2' where id_tipo_tarefa = 3;
update ouvidoria_tipo_tarefa set cor = '#FB8C00' where id_tipo_tarefa = 4;
update ouvidoria_tipo_tarefa set cor = '#FDD835' where id_tipo_tarefa = 5;
update ouvidoria_tipo_tarefa set cor = '#26C6DA' where id_tipo_tarefa = 6;
update ouvidoria_tipo_tarefa set cor = '#4DB6AC' where id_tipo_tarefa = 7;
update ouvidoria_tipo_tarefa set cor = '#EF5350' where id_tipo_tarefa = 8;
update ouvidoria_tipo_tarefa set cor = '#C62828' where id_tipo_tarefa = 9;
update ouvidoria_tipo_tarefa set cor = '#2E7D32' where id_tipo_tarefa = 10;