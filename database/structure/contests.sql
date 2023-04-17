-- contest table
CREATE TABLE IF NOT EXISTS contests (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    start INTEGER,
    end INTEGER
);
