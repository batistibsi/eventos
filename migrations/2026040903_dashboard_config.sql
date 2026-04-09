alter table eventos_config add column dashboard_titulo varchar(150);
alter table eventos_config add column dashboard_carrossel_titulo varchar(150);
alter table eventos_config add column dashboard_carrossel_subtitulo varchar(255);
alter table eventos_config add column dashboard_carrossel_imagens text;
alter table eventos_config add column dashboard_botao_inscricao_titulo varchar(150);
alter table eventos_config add column dashboard_botao_inscricao_link text;
alter table eventos_config add column dashboard_botao_material_titulo varchar(150);
alter table eventos_config add column dashboard_botao_material_link text;
alter table eventos_config add column dashboard_aviso_texto text;

update eventos_config
set dashboard_titulo = coalesce(dashboard_titulo, 'Programe-se'),
    dashboard_carrossel_titulo = coalesce(dashboard_carrossel_titulo, 'Destaques do evento'),
    dashboard_carrossel_subtitulo = coalesce(dashboard_carrossel_subtitulo, 'Adicione uma URL de imagem por linha para montar o carrossel.'),
    dashboard_botao_inscricao_titulo = coalesce(dashboard_botao_inscricao_titulo, 'Inscrição no evento'),
    dashboard_botao_material_titulo = coalesce(dashboard_botao_material_titulo, 'Material de apoio'),
    dashboard_botao_material_link = coalesce(dashboard_botao_material_link, '../../dashboard/material'),
    dashboard_aviso_texto = coalesce(dashboard_aviso_texto, 'Use este espaço para comunicados importantes, instruções e orientações do evento.');
