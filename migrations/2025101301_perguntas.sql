-- Tabela de formulario
CREATE TABLE eventos_empresa (
    id_empresa serial NOT NULL PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT
);

-- Tabela de formulario
CREATE TABLE eventos_formulario (
    id_formulario INTEGER NOT NULL PRIMARY KEY,
	descricao VARCHAR
);

-- Tabela de perguntas
CREATE TABLE eventos_pergunta (
    id_pergunta SERIAL NOT NULL PRIMARY KEY,	
    id_formulario INTEGER NOT NULL,
    pergunta TEXT NOT NULL,
    tipo VARCHAR NOT NULL,
    opcoes TEXT NULL,
    grupo varchar,
    ordem integer,
    FOREIGN KEY (id_formulario) REFERENCES eventos_formulario(id_formulario) ON DELETE CASCADE
);

CREATE TABLE eventos_envio(
    id_envio serial NOT NULL PRIMARY KEY,
    id_empresa INTEGER,
    id_formulario INTEGER,    
    data_envio TIMESTAMP WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    sexo varchar,
    idade integer,
    frequencia varchar,
    FOREIGN KEY (id_formulario) REFERENCES eventos_formulario(id_formulario) ON DELETE CASCADE,
    FOREIGN KEY (id_empresa) REFERENCES eventos_empresa(id_empresa) ON DELETE CASCADE
);


-- Tabela de resposta
CREATE TABLE eventos_resposta (
    id_resposta serial NOT NULL PRIMARY KEY,
    id_envio INTEGER,
    id_pergunta INTEGER,
    resposta TEXT NOT NULL,
    observacao TEXT,
    FOREIGN KEY (id_envio) REFERENCES eventos_envio(id_envio) ON DELETE CASCADE,
    FOREIGN KEY (id_pergunta) REFERENCES eventos_pergunta(id_pergunta) ON DELETE CASCADE
);