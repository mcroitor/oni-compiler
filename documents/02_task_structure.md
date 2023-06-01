# task structure

## task archive structure

Exported task is stored as ZIP archive with name `task_<sysid>.zip`.

This backup has the following structure:

 1. `task.json` - task definition. contains tesk description and limits.
 2. `tests.json` - tests definition. contains definition tests with test points.
 3. `tests` - folder which contains tests defined as pair of input and output files.