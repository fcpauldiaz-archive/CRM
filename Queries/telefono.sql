CREATE SEQUENCE telefono_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE telefono (
    id INT NOT NULL,
    numero_telefono VARCHAR(14) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE telefono
    ADD cliente_id INT DEFAULT NULL;

ALTER TABLE telefono
    ADD CONSTRAINT FK_CLIENTE_TELEFONO
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_TELEFONO ON telefono (cliente_id);
