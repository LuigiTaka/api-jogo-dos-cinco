CREATE TABLE IF NOT EXISTS perguntas
(

    id          INT PRIMARY KEY AUTO_INCREMENT,
    pergunta    VARCHAR(255) UNIQUE NOT NULL,
    dt_inserted DATETIME DEFAULT NOW(),
    dt_updated  DATETIME DEFAULT NULL ON UPDATE NOW(),

    CONSTRAINT uk_perguntas UNIQUE (id, pergunta)
);


CREATE TABLE IF NOT EXISTS respostas
(

    id          INT PRIMARY KEY AUTO_INCREMENT,
    resposta    VARCHAR(255) NOT NULL,
    id_pergunta INT NOT NULL,
    dt_inserted DATETIME DEFAULT NOW(),
    dt_updated  DATETIME ON UPDATE NOW(),

    FOREIGN KEY (id_pergunta) REFERENCES perguntas(id),
    CONSTRAINT uk_respostas UNIQUE (id, resposta)
);