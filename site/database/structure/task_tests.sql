-- tasks table
CREATE TABLE IF NOT EXISTS task_tests (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    task_id INTEGER NOT NULL,
    label TEXT NOT NULL,
    input TEXT NOT NULL,
    output TEXT NOT NULL,
    points INTEGER NOT NULL,
    FOREIGN KEY (task_id) REFERENCES task (id)
);
