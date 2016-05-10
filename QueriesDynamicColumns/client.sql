-- Tabla extra para columnas dinámicas de tipo INTEGER

CREATE TABLE client_extra_int (
    id INT NOT NULL,
    client_id INT NOT NULL,
    value INT NOT NULL,
    nombre_columna VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client_extra_int
    ADD CONSTRAINT FK_CLIENTE_EXTRA_INT
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_EXTRA_INT ON client_extra_int (cliente_id);

-- Tabla extra para columnas dinámicas de tipo DECIMAL

CREATE TABLE client_extra_decimal (
    id INT NOT NULL,
    client_id INT NOT NULL,
    value DOUBLE PRECISION NOT NULL,
    nombre_columna VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client_extra_decimal
    ADD CONSTRAINT FK_CLIENTE_EXTRA_DECIMAL
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_EXTRA_DECIMAL ON client_extra_decimal (cliente_id);

-- Tabla extra para columnas dinámicas de tipo STRING

CREATE TABLE client_extra_string (
    id INT NOT NULL,
    client_id INT NOT NULL,
    value VARCHAR(255) NOT NULL,
    nombre_columna VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client_extra_string
    ADD CONSTRAINT FK_CLIENTE_EXTRA_STRING
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_EXTRA_STRING ON client_extra_string (cliente_id);

-- Tabla extra para columnas dinámicas de tipo DATETIME

CREATE TABLE client_extra_datetime (
    id INT NOT NULL,
    client_id INT NOT NULL,
    value DATE NOT NULL,
    nombre_columna VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client_extra_datetime
    ADD CONSTRAINT FK_CLIENTE_EXTRA_DATETIME
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_EXTRA_DATETIME ON client_extra_datetime (cliente_id);

-- Tabla extra para columnas dinámicas de tipo BOOLEAN

CREATE TABLE client_extra_boolean (
    id INT NOT NULL,
    client_id INT NOT NULL,
    value BOOLEAN NOT NULL,
    nombre_columna VARCHAR(20) NOT NULL,
    PRIMARY KEY(id)
);

ALTER TABLE client_extra_boolean
    ADD CONSTRAINT FK_CLIENTE_EXTRA_BOOLEAN
    FOREIGN KEY (cliente_id)
    REFERENCES client (id);

CREATE INDEX IDX_CLIENTE_EXTRA_BOOLEAN ON client_extra_boolean (cliente_id);
