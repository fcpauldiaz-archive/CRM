CREATE SEQUENCE correo_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE correo (
    id INT NOT NULL,
    correoElectronico VARCHAR(50) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE correo
    ADD cliente_id INT DEFAULT NULL;

ALTER TABLE correo
    ADD CONSTRAINT FK_77040BC9DE734E51
    FOREIGN KEY (cliente_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE;

CREATE INDEX IDX_77040BC9DE734E51 ON correo (cliente_id);
