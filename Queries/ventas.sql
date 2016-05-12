CREATE SEQUENCE ventas_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE ventas
(
  id integer NOT NULL,
  producto_id integer,
  cantidad integer,
  total double precision,
  client_id integer default null,
  CONSTRAINT venta_id PRIMARY KEY (id),
  CONSTRAINT producto_id FOREIGN KEY (producto_id)
      REFERENCES producto (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION,
  CONSTRAINT client_id FOREIGN KEY (client_id)
      REFERENCES client (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
);

CREATE INDEX IDX_VENTA_ID ON ventas (id);
CREATE INDEX IDX_PRODUCTO_ID on ventas (producto_id);
