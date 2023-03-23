<?php

namespace mc\render\admin;

class Task {
    public static function viewAll()
    {
        $db = new \mc\sql\database(\config::dsn);
        $tasks = $db->select("tasks");
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/admin/task/all.tpl.php")
        );
        $task_items = "";
        foreach ($tasks as $task) {
            $task_items .= "<tr><td>{$task['id']}</td>" .
                "<td><a href='/?q=task/view/{$task['id']}'>{$task['name']}</a></td>" .
                "<td>{$task['memory']}</th>" .
                "<td>{$task['time']}</td></tr>\n";
        }
        return $result->fill(["<!-- tasks-items -->" => $task_items])->value();
    }

    public static function create()
    {
        \mc\logger::stdout()->info("post: " . json_encode($_POST));
        if (!empty($_POST)) {
            \mc\logger::stdout()->warn("redirect to registration");
            return Task::register();
        }
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/admin/task/create.tpl.php")
        );

        return $result->value();
    }

    public static function update($params)
    {
        if (empty($params)) {
            header("location:/?q=tasks");
            exit();
        }
        $taskId = (int)$params[0];
        if ($taskId === 0) {
            header("location:/?q=tasks");
            exit();
        }
        \mc\logger::stdout()->info("post: " . json_encode($_POST));
        if (!empty($_POST)) {
            \mc\logger::stdout()->warn("redirect to update task");
            return Task::up($taskId);
        }
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/admin/task/update.tpl.php")
        );

        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, "tasks");
        $task = $crud->select($taskId);

        $data = [
            "<!-- id -->" => $task["id"],
            "<!-- name -->" => $task["name"],
            "<!-- memory -->" => $task["memory"],
            "<!-- time -->" => $task["time"],
        ];

        return $result->fill($data)->value();
    }

    private static function register()
    {
        $data = [
            "name" => filter_input(INPUT_POST, "name", FILTER_DEFAULT),
            "memory" => filter_input(INPUT_POST, "memory", FILTER_VALIDATE_INT),
            "time" => filter_input(INPUT_POST, "time", FILTER_VALIDATE_INT),
        ];
        $db = new \mc\sql\database(\config::dsn);
        $db->insert("tasks", $data);
        header("location:/?q=tasks");
        exit();
    }

    private static function up($taskId)
    {
        $data = [
            "name" => filter_input(INPUT_POST, "name", FILTER_DEFAULT),
            "memory" => filter_input(INPUT_POST, "memory", FILTER_VALIDATE_INT),
            "time" => filter_input(INPUT_POST, "time", FILTER_VALIDATE_INT),
        ];
        $db = new \mc\sql\database(\config::dsn);
        $db->update("tasks", $data, ["id" => $taskId]);
        header("location:/?q=tasks");
        exit();
    }

    public static function view(array $params)
    {
        if (empty($params)) {
            header("location:/?q=tasks");
            exit();
        }
        $taskId = (int)$params[0];
        if ($taskId === 0) {
            header("location:/?q=tasks");
            exit();
        }
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, "tasks");
        $task = $crud->select($taskId);
        $result = new \mc\template(
            file_get_contents(
                \config::templates_dir . "/admin/task/view.tpl.php"
            )
        );
        $data = [
            "<!-- id -->" => $task["id"],
            "<!-- name -->" => $task["name"],
            "<!-- memory -->" => $task["memory"],
            "<!-- time -->" => $task["time"],
        ];

        return $result->fill($data)->value();
    }
}
