CREATE SEQUENCE valor_dinamico_id INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE valor_dinamico (
    id INT NOT NULL,
    valor VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE valor_dinamico
    ADD campo_dinamico_id INT NOT NULL;

ALTER TABLE valor_dinamico
    ADD CONSTRAINT FK_VALOR_CAMPO
    FOREIGN KEY (campo_dinamico_id)
    REFERENCES campo_dinamico (id);

ALTER TABLE valor_dinamico
    ADD cliente_id INT NOT NULL;

ALTER TABLE valor_dinamico
    ADD CONSTRAINT FK_VALOR_CLIENTE
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);
