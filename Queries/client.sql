CREATE SEQUENCE client_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE client (
    id INT NOT NULL,
    fecha_nacimiento VARCHAR(255) DEFAULT NULL,
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
    twitter_username VARCHAR(25) DEFAULT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client
    ADD tipo_membresia_id INT DEFAULT NULL;

ALTER TABLE client
    ADD CONSTRAINT FK_TIPO_MEMBRESIA
    FOREIGN KEY (tipo_membresia_id)
    REFERENCES tipo_membresia (id);

CREATE INDEX IDX_TIPO_MEMBRESIA ON client (tipo_membresia_id);
