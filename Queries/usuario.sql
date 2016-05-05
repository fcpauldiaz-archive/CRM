CREATE TABLE usuario (
		id INT NOT NULL, 
		username VARCHAR(255) NOT NULL, 
		username_canonical VARCHAR(255) NOT NULL, 
		email VARCHAR(255) NOT NULL, 
		email_canonical VARCHAR(255) NOT NULL, 
		enabled BOOLEAN NOT NULL, 
		salt VARCHAR(255) NOT NULL, 
		password VARCHAR(255) NOT NULL, 
		last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		locked BOOLEAN NOT NULL, 
		expired BOOLEAN NOT NULL, 
		expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		confirmation_token VARCHAR(255) DEFAULT NULL, 
		password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		roles TEXT NOT NULL, credentials_expired BOOLEAN NOT NULL, 
		credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		PRIMARY KEY(id));

CREATE SEQUENCE usuario_id_seq;

CREATE OR REPLACE FUNCTION auto_increment()
 RETURNS "trigger" AS
 $$	
 BEGIN
   New.id:=currval('usuario_id_seq');
   Return NEW;
 END;
 $$ LANGUAGE plpgsql;

CREATE TRIGGER usuario_trigger 
BEFORE INSERT
ON usuario
FOR EACH ROW
EXECUTE PROCEDURE auto_increment();

CREATE UNIQUE INDEX INDEX_1 ON usuario (username_canonical);

CREATE UNIQUE INDEX INDEX_2 ON usuario (email_canonical);

CREATE UNIQUE INDEX INDEX_3 ON usuario (id);
