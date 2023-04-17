# Yet Another PHP Test Framework

## What is it?

Simple PHP framework for writing Unit Tests. Is dependent from (https://github.com/mcroitor/logger)[mcroitor/logger] project.

## How to install

You can use another my project :) (https://github.com/mcroitor/module_manager)[mcroitor/module_manager] for install.

1. Download module manager from https://github.com/mcroitor/module_manager (`manager.php` file only).
2. Create file `modules.json`:
```json
[
    {
        "user" : "mcroitor",
        "repository" : "logger",
        "source" : "./src/",
        "destination" : "./"
    },
        {
        "user" : "mcroitor",
        "repository" : "mctester",
        "source" : "./mc/",
        "destination" : "./mc/"
    }
]
```
3. Execute
```bash
php manager.php --install --config=modules.json
``` 
4. Done!

## How to use

