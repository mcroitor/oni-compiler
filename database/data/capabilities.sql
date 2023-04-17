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
('contest_unenrol_user'),   -- unenrol a user from contest
-- user
('user_create'),            -- create a user
('user_edit'),              -- edit user info
('user_view'),              -- view user info
('user_view_list'),         -- view user list
('user_delete'),            -- remove a user
('user_change_role');       -- change user role
