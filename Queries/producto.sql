CREATE SEQUENCE producto_id_seq INCREMENT BY 1 MINVALUE 1 START 1;
CREATE TABLE producto
(
  id integer NOT NULL,
  producto TEXT ,
  CONSTRAINT producto_id PRIMARY KEY (id)
);
CREATE INDEX IDX_CLIENT_ID ON producto (id);