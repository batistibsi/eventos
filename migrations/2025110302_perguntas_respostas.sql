delete from eventos_tarefa;

delete from eventos_envio;

delete from eventos_pergunta;

ALTER TABLE IF EXISTS public.eventos_resposta DROP COLUMN IF EXISTS id_pergunta;

alter table eventos_resposta add column pergunta text COLLATE pg_catalog."default" NOT NULL;
alter table eventos_resposta add column tipo character varying COLLATE pg_catalog."default" NOT NULL;
alter table eventos_resposta add column opcoes text COLLATE pg_catalog."default";
alter table eventos_resposta add column grupo character varying COLLATE pg_catalog."default";
alter table eventos_resposta add column ordem integer;

INSERT INTO public.eventos_pergunta(
	id_formulario, pergunta, tipo, opcoes, grupo, ordem)
VALUES 
	(1, 'Lembre-se de mencionar a pessoa e a área onde ela trabalha, junto com seu elogio.', 'texto', null, 'Qual seu elogio?', 1),
	(2, '', 'select', '["FRAUDE, CORRUPÇÃO E ATIVIDADES ILÍCITAS","MEIO AMBIENTE","RELACIONAMENTO COM COLABORADORES","RELACIONAMENTO COM TERCEIROS"]', 'Qual o tipo de denúncia?', 1),
	(2, 'Faça um relato detalhado de sua denúncia. Quanto mais informações, melhor.', 'texto', null, 'Descreva sua denúncia:', 2),
	(2, 'Se não souber a informação exata, informe data e horário aproximado.', 'texto', null, 'Quando ocorreu o relato que está denunciando?', 3),
	(2, 'Informe nome/sobrenome, departamento do responsável e também de testemunhas, se houver.', 'texto', null, 'Que pessoas estavam envolvidas nessa denúncia?', 4),
	(2, 'Se não lembrar da regra completa, escreva o que lembrar.', 'texto', null, 'Qual foi a regra descumprida em seu relato?', 5),
	(3, 'Escreva qual a sua dúvida e quais informações você gostaria de mais informações.', 'texto', null, 'Qual sua dúvida?', 1),
	(4, 'Dúvida', 'texto', null, 'Sobre LGPD, você tem...', 1),
    (4, 'Sugestão', 'texto', null, 'Sobre LGPD, você tem...', 2),
	(5, 'Inclua o máximo de informações que tiver sobre seu relato.', 'texto', null, 'Qual sua reclamação?', 1),
	(5, 'Caso você tenha feito uma reclamação, inclua também sua sugestão para solução.', 'texto', null, 'Qual sua sugestão para a reclamação?', 2),
	(6, 'Quanto mais informações, melhor!', 'texto', null, 'Qual sua sugestão?', 1);

alter table eventos_envio add column arquivos varchar;