-- contestant capabilities
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='task_view';

INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='task_view_list';

INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='task_solve';

INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='contest_view';

INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='contest_participate';

INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contestant' AND capabilities.name='user_view_list';

-- __contest creator__
-- `contest_create` - create a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_create';
-- `contest_edit` - edit a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_edit';
-- `contest_view` - view a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_view';
-- `contest_view_list` - view a list of contests
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_view_list';
-- `contest_delete` - remove a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_delete';
-- `task_create` - create a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='task_create';
-- `task_edit` - edit a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='task_edit';
-- `task_view` - view a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='task_view';
-- `task_view_list` - view a list of tasks
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='task_view_list';
-- `task_delete` - remove a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='task_delete';
-- `contest_enrol_user` - enrol a user for participation
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_enrol_user';
-- `contest_unenrol_user` - unenrol a user from contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles 
INNER JOIN capabilities WHERE roles.name='contest_creator' AND capabilities.name='contest_unenrol_user';

-- __administrator__
-- `task_create` - create a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_create';
-- `task_edit` - edit a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_edit';
-- `task_view` - view a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_view';
-- `task_view_list` - view a list of tasks
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_view_list';
-- `task_delete` - remove a task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_delete';
-- `task_solve` - send solutions for task
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='task_solve';
-- `contest_create` - create a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_create';
-- `contest_edit` - edit a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_edit';
-- `contest_view` - view a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_view';
-- `contest_view_list` - view a list of contests
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_view_list';
-- `contest_delete` - remove a contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_delete';
-- `contest_participate` - solve tasks from contests
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_participate';
-- `contest_enrol_user` - enrol a user for participation
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_enrol_user';
-- `contest_unenrol_user` - unenrol a user from contest
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='contest_unenrol_user';
-- `user_create` - create a user
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_create';
-- `user_edit` - edit user info
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_edit';
-- `user_view` - view user info
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_view';
-- `user_view_list` - view user list
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_view_list';
-- `user_delete` - remove a user
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_delete';
-- `user_change_role` - change user role
INSERT INTO role_capabilities (role_id, capability_id)
SELECT roles.id, capabilities.id FROM roles
INNER JOIN capabilities WHERE roles.name='administrator' AND capabilities.name='user_change_role';
