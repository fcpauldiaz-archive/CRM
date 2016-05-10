CREATE SEQUENCE tipo_membresia_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE tipo_membresia (
    id INT NOT NULL,
    tipo_membresia VARCHAR(25) NOT NULL,
    PRIMARY KEY(id)
);

