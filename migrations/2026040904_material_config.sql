alter table eventos_config add column material_titulo varchar(150);
alter table eventos_config add column material_video_principal_titulo varchar(150);
alter table eventos_config add column material_video_principal_link text;
alter table eventos_config add column material_videos_secundarios text;
alter table eventos_config add column material_arquivos text;
alter table eventos_config add column material_links_topo text;
alter table eventos_config add column material_links_lista text;

update eventos_config
set material_titulo = coalesce(material_titulo, 'Material de apoio e videos'),
    material_video_principal_titulo = coalesce(material_video_principal_titulo, 'Video principal'),
    material_videos_secundarios = coalesce(material_videos_secundarios, E'Video 1 | \nVideo 2 | \nVideo 3 | \nVideo 4 | \nVideo 5 | \nVideo 6 | '),
    material_arquivos = coalesce(material_arquivos, E'Arquivo 1 | \nArquivo 2 | \nArquivo 3 | \nEdital | '),
    material_links_topo = coalesce(material_links_topo, E'3 pdf\nAlguns links'),
    material_links_lista = coalesce(material_links_lista, 'Titulo do link | https://');
