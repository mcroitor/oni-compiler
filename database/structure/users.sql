-- users
CREATE TABLE IF NOT EXISTS users (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT,
    password TEXT NOT NULL,
    firstname TEXT,
    lastname TEXT,
    institution TEXT,
    role_id INTEGER DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES role (id)
);
