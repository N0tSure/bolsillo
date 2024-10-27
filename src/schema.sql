-- Marcadores
CREATE TABLE bookmark(
	id INTEGER CONSTRAINT pk_bookmark PRIMARY KEY AUTOINCREMENT,
	uri STRING NOT NULL
);

-- Users
CREATE TABLE users(
                      id INTEGER CONSTRAINT pk_users PRIMARY KEY AUTOINCREMENT,
                      email TEXT NOT NULL,
                      password TEXT NOT NULL,
                      name TEXT NOT NULL,
                      is_active BOOLEAN NOT NULL
);

-- Unique index
CREATE UNIQUE INDEX uq_users_email ON users (email);
