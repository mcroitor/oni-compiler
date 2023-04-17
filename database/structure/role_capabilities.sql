-- capabilities by roles
CREATE TABLE IF NOT EXISTS role_capabilities (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    capability_id INTEGER NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role (id),
    FOREIGN KEY (capability_id) REFERENCES capability (id)
);
