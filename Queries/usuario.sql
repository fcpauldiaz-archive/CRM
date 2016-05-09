CREATE TABLE usuario (
		id INT NOT NULL, 
		username TEXT NOT NULL, 
		username_canonical TEXT NOT NULL, 
		email TEXT NOT NULL, 
		email_canonical TEXT NOT NULL, 
		enabled BOOLEAN NOT NULL, 
		salt TEXT NOT NULL, 
		password TEXT NOT NULL, 
		last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		locked BOOLEAN NOT NULL, 
		expired BOOLEAN NOT NULL, 
		expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		confirmation_token TEXT DEFAULT NULL, 
		password_requested_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		roles TEXT NOT NULL, 
		credentials_expired BOOLEAN NOT NULL, 
		credentials_expire_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, 
		twitter_id TEXT DEFAULT NULL,
		twitter_token TEXT DEFAULT NULL,
		twitter_secret_token TEXT DEFAULT NULL,
		PRIMARY KEY(id)
);

CREATE SEQUENCE usuario_id_seq;

CREATE UNIQUE INDEX INDEX_1 ON usuario (username_canonical);

CREATE UNIQUE INDEX INDEX_2 ON usuario (email_canonical);

CREATE UNIQUE INDEX INDEX_3 ON usuario (id);
