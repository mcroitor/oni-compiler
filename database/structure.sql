PRAGMA foreign_keys = ON;

-- capabilities
CREATE TABLE capabilities (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

INSERT INTO capabilities (name) VALUES
-- task
('task_create'),            -- create a task
('task_edit'),              -- edit a task
('task_view'),              -- view a task
('task_view_list'),         -- view a list of tasks
('task_delete'),            -- remove a task
('task_solve'),             -- send solutions for task
-- contest
('contest_create'),         -- create a contest
('contest_edit'),           -- edit a contest
('contest_view'),           -- view a contest
('contest_view_list'),      -- view a list of contests
('contest_delete'),         -- remove a contest
('contest_participate'),    -- solve tasks from contests
('contest_enrol_user'),     -- enrol a user for participation
-- user
('user_create'),            -- create a user
('user_edit'),              -- edit user info
('user_view'),              -- view user info
('user_view_list'),         -- view user list
('user_delete');            -- remove a user

-- roles
CREATE TABLE roles (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL
);

INSERT INTO roles (name) VALUES
('user'),
('contestant'),
('contest_creator'),
('manager'),
('administrator');

-- capabilities by roles
CREATE TABLE role_capabilities (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    role_id INTEGER NOT NULL,
    capability_id INTEGER NOT NULL,
    FOREIGN KEY (role_id) REFERENCES role (id),
    FOREIGN KEY (capability_id) REFERENCES capability (id)
);

-- users
CREATE TABLE users (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT,
    password TEXT NOT NULL,
    firstname TEXT,
    lastname TEXT,
    institution TEXT
    role INTEGER DEFAULT 1,
    FOREIGN KEY (role_id) REFERENCES role (id)
);

INSERT INTO users (name, email, password, firstname, lastname, institution, role) VALUES
('admin', 'admin@localhost', 'adminpassword', 'Super', 'Admin', '', 4);

-- contest table
CREATE TABLE contests (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    start INTEGER,
    end INTEGER
);

-- contestant table
CREATE TABLE contestants (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    FOREIGN KEY (user_id) REFERENCES user (id)
);

-- tasks table
CREATE TABLE tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    memory INTEGER NOT NULL,
    time INTEGER NOT NULL
);

-- contest_task
CREATE TABLE contest_tasks (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    contest_id TEXT NOT NULL,
    task_id INTEGER NOT NULL,
    weight INTEGER DEFAULT 1,
    FOREIGN KEY (contest_id) REFERENCES contest (id) 
);

-- solutions
CREATE TABLE solutions (
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
