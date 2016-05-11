CREATE SEQUENCE venta_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE ventas
(
  id integer NOT NULL,
  producto_id integer,
  cantidad integer,
  total double precision,
  CONSTRAINT venta_id PRIMARY KEY (id),
  CONSTRAINT producto_id FOREIGN KEY (producto_id)
      REFERENCES producto (id) MATCH SIMPLE
      ON UPDATE NO ACTION ON DELETE NO ACTION
)