-- contest_task
CREATE TABLE IF NOT EXISTS contest_tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contest_id TEXT NOT NULL,
    task_id INTEGER NOT NULL,
    weight INTEGER DEFAULT 1,
    FOREIGN KEY (contest_id) REFERENCES contest (id) 
);
