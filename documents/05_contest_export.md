# Contest export structure

Экспорт конкурса представляет собой ZIP-архив со следующей структурой:

```
contest_{id}.zip
├── contest.json       # данные конкурса
├── tasks.json         # список ID задач
├── participants.json  # список участников
├── tasks/
│   ├── {task_id}/
│   │   ├── task.json  # определение задачи
│   │   ├── tests.json # определение тестов
│   │   └── tests/     # файлы тестов
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

## Файлы

### contest.json
Данные конкурса из таблицы `contests`.

### tasks.json
Массив ID задач, входящих в конкурс.

### participants.json
Массив участников конкурса:
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
Определение задачи из таблицы `tasks`.

### tasks/{task_id}/tests.json
Массив тестов задачи из таблицы `task_tests`.

### solutions/user_{user_id}/
Решения участника - файлы исходного кода.
