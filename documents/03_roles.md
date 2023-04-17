# Roles

Each role is a set of capabilities. Role is a property of user.

Each object functionality defines a list of capabilities.

## Example of objects, functionalities and capabilities:

- __task__:
  - `task_create` - create a task
  - `task_edit` - edit a task
  - `task_view` - view a task
  - `task_view_list` - view a list of tasks
  - `task_delete` - remove a task
  - `task_solve` - send solutions for task
- __contest__:
  - `contest_create` - create a contest
  - `contest_edit` - edit a contest
  - `contest_view` - view a contest
  - `contest_view_list` - view a list of contests
  - `contest_delete` - remove a contest
  - `contest_participate` - solve tasks from contests
  - `contest_enrol_user` - enrol a user for participation
  - `contest_unenrol_user` - unenrol a user from contest
- __user__:
  - `user_create` - create a user
  - `user_edit` - edit user info
  - `user_view` - view user info
  - `user_view_list` - view user list
  - `user_delete` - remove a user
  - `user_change_role` - change user role

## roles and capabilities

- __contestant__
  - `task_view`
  - `task_view_list`
  - `task_solve`
  - `contest_view`
  - `contest_participate`
  - `user_view_list`
- __contest creator__
  - `contest_create` - create a contest
  - `contest_edit` - edit a contest
  - `contest_view` - view a contest
  - `contest_view_list` - view a list of contests
  - `contest_delete` - remove a contest
  - `task_create` - create a task
  - `task_edit` - edit a task
  - `task_view` - view a task
  - `task_view_list` - view a list of tasks
  - `task_delete` - remove a task
  - `contest_enrol_user` - enrol a user for participation
  - `contest_unenrol_user` - unenrol a user from contest
- __administrator__
  - `task_create` - create a task
  - `task_edit` - edit a task
  - `task_view` - view a task
  - `task_view_list` - view a list of tasks
  - `task_delete` - remove a task
  - `task_solve` - send solutions for task
  - `contest_create` - create a contest
  - `contest_edit` - edit a contest
  - `contest_view` - view a contest
  - `contest_view_list` - view a list of contests
  - `contest_delete` - remove a contest
  - `contest_participate` - solve tasks from contests
  - `contest_enrol_user` - enrol a user for participation
  - `contest_unenrol_user` - unenrol a user from contest
  - `user_create` - create a user
  - `user_edit` - edit user info
  - `user_view` - view user info
  - `user_view_list` - view user list
  - `user_delete` - remove a user
  - `user_change_role` - change user role
