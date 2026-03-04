create table ouvidoria_config(
    id_config integer not null primary key,
    nome_empresa varchar,
    cnpj_empresa varchar,
    email varchar,
    endereco varchar,
    dpo varchar,
    email_dpo varchar,
    smtp varchar,
    porta varchar,
    senha varchar
);

INSERT INTO
    public.ouvidoria_config(
        id_config,
        nome_empresa,
        cnpj_empresa,
        email,
        endereco,
        dpo,
        email_dpo,
        smtp,
        porta,
        senha
    )
VALUES
    (
        1,
        'Paraquedas Consultoria ESG',
        '35.048.928/0001-20',
        'batisti23@gmail.com',
        'R. Santos Dumont, 50 - Zona 03, Maringá - PR, 87050-100',
        'Henrique Nascimento',
        'henriquen.paraquedas@gmail.com',
        'smtp.gmail.com',
        '587',
        'brjzjqwyylvvqlpf'
    );