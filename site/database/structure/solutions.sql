-- solutions
CREATE TABLE IF NOT EXISTS solutions (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contestant_id INTEGER NOT NULL,
    contest_task_id INTEGER NOT NULL,
    timestamp INTEGER NOT NULL,
    state INTEGER DEFAULT 0,
    points INTEGER DEFAULT 0,
    path TEXT NOT NULL,
    FOREIGN KEY (contestant_id) REFERENCES contestant (id),
    FOREIGN KEY (contest_task_id) REFERENCES contest_task (id) 
);
