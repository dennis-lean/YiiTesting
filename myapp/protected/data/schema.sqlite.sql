CREATE TABLE dennis_user
(
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	username VARCHAR(128) NOT NULL,
	password VARCHAR(128) NOT NULL,
	salt VARCHAR(128) NOT NULL,
	reset_token VARCHAR(128) NULL,
	reset_expired VARCHAR(128) NULL
);

INSERT INTO dennis_user (username, password, salt) VALUES ('demo@myapp.com','2e5c7db760a33498023813489cfadc0b','28b206548469ce62182048fd9cf91760');
