# task structure

## task archive structure

Exported task is stored as ZIP archive with name `task_<sysid>.zip`.

This backup has the following structure:

1. `task.json` - task definition. contains task description and limits.
2. `tests.json` - tests definition. contains definition tests with test points.
3. `tests` - folder which contains tests defined as pair of input and output files.
4. `solution/solution_<index>.<ext>` - official solution source code.

Problem must have an official solution (or solutions) from the author. This solution is used:

- to check correctness of the task (tests or solution)
- to generate tests for the task
- to estimate memory and time limits
