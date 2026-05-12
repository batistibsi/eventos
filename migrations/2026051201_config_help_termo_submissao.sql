ALTER TABLE public.eventos_config
    ADD COLUMN help_termo_submissao text;

UPDATE public.eventos_config
   SET help_termo_submissao = COALESCE(
       help_termo_submissao,
       'TERMO DE COMPROMISSO IMPACTACIM 2026

Ao realizar a inscricao na Certificacao IMPACTACIM: Jornada de Sustentabilidade 2026, a empresa participante declara que leu e esta de acordo com o Regulamento da Edicao 2026, comprometendo-se a cumprir integralmente todas as suas disposicoes.
A organizacao inscrita assume o compromisso de colaborar ativamente com o Instituto ACIM de Responsabilidade Social na realizacao dos objetivos da certificacao, bem como de respeitar os prazos estabelecidos ao longo do processo.
A empresa tambem se responsabiliza pela veracidade de todas as informacoes fornecidas durante a inscricao e participacao, isentando o Instituto ACIM de quaisquer reivindicacoes de terceiros relacionadas a esses dados.
No que diz respeito a protecao de dados, a participante compromete-se a atuar em conformidade com a Lei n 13.709/18 (Lei Geral de Protecao de Dados Pessoais - LGPD), assegurando o tratamento adequado das informacoes envolvidas no processo.
Ao se inscrever, a empresa autoriza o uso de seu nome, imagem, logotipo e materiais visuais, incluindo fotos e videos, pelo Instituto ACIM de Responsabilidade Social, exclusivamente para fins de divulgacao e promocao da certificacao, sem qualquer tipo de compensacao financeira.
A participante tambem concorda em nao reproduzir, compartilhar ou disponibilizar a terceiros os materiais, metodologias e conteudos utilizados ao longo da Certificacao IMPACTACIM.
O nao cumprimento das condicoes aqui estabelecidas podera resultar na exclusao da empresa do processo de certificacao.

Ao clicar em ''Sim, submeter'', a empresa declara ciencia e aceite integral deste termo.'
   );
