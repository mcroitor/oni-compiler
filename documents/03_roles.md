# Roles

Each role is a set of capabilities. Role is a property of user.

Each object functionality defines a list of capabilities.

## Example of objects, functionalities and capabilities

- __user__:
  - `user_create` - create a user
  - `user_edit` - edit user info
  - `user_view` - view user info
  - `user_view_list` - view user list
  - `user_delete` - remove a user
  - `user_change_role` - change user role
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

## roles and capabilities

- __contestant__
  - `task_view`
  - `task_view_list`
  - `task_solve`
  - `contest_view`
  - `contest_participate`
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
  - `user_view_list` - view user list
  - `user_view` - view user info
  - `user_create` - create a user
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

## Role and capabilities relation table

| ID | Capability           | Contestant | Contest creator | Administrator |
| -- | -------------------- | ---------- | --------------- | ------------- |
|    | __user__             |            |                 |               |
| 1  | user_create          |            | x               | x             |
| 2  | user_edit            |            | x               | x             |
| 3  | user_view            |            | x               | x             |
| 4  | user_view_list       |            | x               | x             |
| 5  | user_delete          |            | x               | x             |
| 6  | user_change_role     |            |                 | x             |
|    | __task__             |            |                 |               |
| 7  | task_create          |            | x               | x             |
| 8  | task_edit            |            | x               | x             |
| 9  | task_view            | x          | x               | x             |
| 10 | task_view_list       | x          | x               | x             |
| 11 | task_delete          |            | x               | x             |
| 12 | task_solve           | x          |                 |               |
|    | __contest__          |            |                 |               |
| 13 | contest_create       |            | x               | x             |
| 14 | contest_edit         |            | x               | x             |
| 15 | contest_view         | x          | x               | x             |
| 16 | contest_view_list    | x          | x               | x             |
| 17 | contest_delete       |            | x               | x             |
| 18 | contest_participate  | x          |                 |               |
| 19 | contest_enrol_user   |            | x               | x             |
| 20 | contest_unenrol_user |            | x               | x             |
