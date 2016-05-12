CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE client (
    id INT NOT NULL,
    fecha_nacimiento DATE DEFAULT NULL,
    nit VARCHAR(10) NOT NULL,
    frecuente BOOLEAN DEFAULT NULL,
    nombres VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) DEFAULT NULL,
    estado_civil VARCHAR(255) NOT NULL,
    foto_cliente VARCHAR(255) DEFAULT NULL,
    sexo VARCHAR(255) DEFAULT NULL,
    profesion VARCHAR(255) DEFAULT NULL,
    dpi VARCHAR(14) DEFAULT NULL,
    nacionalidad VARCHAR(255) DEFAULT NULL,
    twitter_username TEXT DEFAULT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client
    ADD tipo_membresia_id INT DEFAULT NULL;

ALTER TABLE client
    ADD CONSTRAINT FK_TIPO_MEMBRESIA
    FOREIGN KEY (tipo_membresia_id)
    REFERENCES tipo_membresia (id);

ALTER TABLE client
    ADD usuario_id INT DEFAULT NULL;

ALTER TABLE client
    ADD CONSTRAINT FK_USUARIO_ID
    FOREIGN KEY (usuario_id)
    REFERENCES usuario (id);

ALTER TABLE client 
    ADD CONSTRAINT UNIQUE_NIT UNIQUE (nit);
    
ALTER TABLE client 
    ADD CONSTRAINT UNIQUE_DPI UNIQUE (dpi);

CREATE INDEX IDX_TIPO_MEMBRESIA ON client (tipo_membresia_id);
CREATE INDEX IDX_USUARIO_ID ON client (usuario_id);
CREATE INDEX IDX_CLIENT_ID ON client (id);

