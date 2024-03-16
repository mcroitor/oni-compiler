-- tasks table
CREATE TABLE IF NOT EXISTS tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    memory INTEGER NOT NULL,
    time INTEGER NOT NULL
);
