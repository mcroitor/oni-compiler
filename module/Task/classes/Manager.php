<?php

namespace Task;

use config;

class Manager
{
    public const dir = \config::module_dir . "/Task/";
    public const templates_dir = self::dir . "/templates/";

    public static function create(array $params)
    {
        // TODO: def
        //      if post is empty, publish form
        //      otherwise insert in database
        //      and redirect to the task list
        if (isset($_POST["create-task"])) {
            self::insertData();
            header("location:/?q=task/list");
            return "";
        }

        return file_get_contents(self::templates_dir . "task.create.tpl.php");
    }

    private static function insertData()
    {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $data = [
            \meta\tasks::NAME => filter_input(INPUT_POST, "task-name"),
            \meta\tasks::DESCRIPTION => filter_input(INPUT_POST, "task-description"),
            \meta\tasks::MEMORY => filter_input(INPUT_POST, "task-memory"),
            \meta\tasks::TIME => filter_input(INPUT_POST, "task-time"),
        ];
        \mc\logger::stdout()->info("data prepared: " . json_encode($data));
        $crud->insert($data);
    }

    /**
     * Returns HTML, list of tasks, from <b>$param[0]</b> total <b>$param[1]</b> tasks.
     * If <b>$param[1]</b> not set, selects 20 tasks.
     * If <b>$param[0]</b> selects from first.
     *
     * @param array $params
     * @return string
     */
    public static function list(array $params): string
    {
        $from = $params[0] ?? 1;
        $offset = $params[1] ?? 20;

        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $tasks = $crud->all($from, $offset);

        $list = "";

        foreach ($tasks as $task) {
            $list .= (new \mc\template(
                file_get_contents(self::templates_dir . "tasklist.element.tpl.php")
            ))->fill([
                "<!-- name -->" => $task[\meta\tasks::NAME],
                "<!-- memory -->" => $task[\meta\tasks::MEMORY],
                "<!-- time -->" => $task[\meta\tasks::TIME],
            ])->value();
        }
        return (new \mc\template(
            file_get_contents(self::templates_dir . "tasklist.tpl.php")
        ))->fill([
            "<!-- tasklist element -->" => $list
        ])->value();
    }
}
