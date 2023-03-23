-- contests table
CREATE TABLE contests (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    start INTEGER,
    end INTEGER
);

-- contestants table
CREATE TABLE contestants (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    firstname TEXT,
    lastname TEXT,
    INSTITUTION TEXT
);

-- tasks table
CREATE TABLE tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT,
    memory INTEGER NOT NULL,
    time INTEGER NOT NULL
);

-- contest_tasks
CREATE TABLE contest_tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contest_id TEXT NOT NULL,
    task_id INTEGER NOT NULL,
    weight INTEGER
);

-- solutions
CREATE TABLE solutions (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contester_id INTEGER,
    contest_task_id INTEGER,
    timestamp INTEGER,
    state INTEGER,
    points INTEGER,
    path TEXT
);