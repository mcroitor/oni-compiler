-- contestant table
CREATE TABLE IF NOT EXISTS contestants (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contest_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (contest_id) REFERENCES contest (id),
    FOREIGN KEY (user_id) REFERENCES user (id)
);
