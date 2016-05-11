CREATE SEQUENCE campo_dinamico_id INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE campo_dinamico (
    id INT NOT NULL,
    nombre VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE campo_dinamico
    ADD tipo_columna_id INT NOT NULL;

ALTER TABLE campo_dinamico
    ADD CONSTRAINT FK_CAMPO_DINAMICO_TIPO_COLUMNA
    FOREIGN KEY (tipo_columna_id)
    REFERENCES tipo_columna (id);
