# Contest export structure

A contest export is a ZIP archive with the following structure:

```
contest_{id}.zip
├── contest.json       # contest data
├── tasks.json         # list of task IDs
├── participants.json  # list of participants
├── tasks/
│   ├── {task_id}/
│   │   ├── task.json  # task definition
│   │   ├── tests.json # tests definition
│   │   └── tests/     # test files
│   │       ├── 01.input
│   │       └── 01.output
│   └── ...
└── solutions/
    ├── user_{user_id}/
    │   ├── solution1.cpp
    │   ├── solution2.py
    │   └── ...
    └── ...
```

## Files

### contest.json
Contest data from the `contests` table.

### tasks.json
Array of task IDs included in the contest.

### participants.json
Array of contest participants:
```json
[
  {
    "id": 1,
    "firstname": "John",
    "lastname": "Doe"
  }
]
```

### tasks/{task_id}/task.json
Task definition from the `tasks` table.

### tasks/{task_id}/tests.json
Array of task tests from the `task_tests` table.

### solutions/user_{user_id}/
Participant's solutions - source code files.
