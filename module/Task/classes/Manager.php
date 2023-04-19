<?php

namespace Task;

use config;
use mc\template;
use ZipArchive;

class Manager
{
    public const dir = \config::module_dir . "/task/";
    public const templates_dir = self::dir . "/templates/";

    private static function getTaskPath(string $taskId) {
        return \config::tasks_dir . "{$taskId}/";
    }

    private static function getTestTaskPath(string $taskId) {
        return self::getTaskPath($taskId) . "tests/";
    } 

    /**
     * show form for task creation or, if POST data is present,
     * create new task.
     * @param array $params not used
     */
    public static function create(array $params)
    {
        if (isset($_POST["create-task"])) {
            $task_id = self::insertData();
            self::createStructure($task_id);
            header("location:/?q=task/update/{$task_id}");
            return "";
        }

        return file_get_contents(self::templates_dir . "task.create.tpl.php");
    }

    public static function update(array $params)
    {
        if (isset($_POST["update-task"])) {
            $task_id = filter_input(INPUT_POST, "task-id");
            self::updateData();
            header("location:/?q=task/update/{$task_id}");
            return "";
        }
        $task_id = empty($params[0]) ? 0 : (int)$params[0];
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $task = $crud->select($task_id);
        $tpl = new template(
            file_get_contents(self::templates_dir . "task.update.tpl.php")
        );

        return $tpl->fill([
            "<!-- task-id -->" => $task[\meta\tasks::ID],
            "<!-- task-name -->" => $task[\meta\tasks::NAME],
            "<!-- task-description -->" => $task[\meta\tasks::DESCRIPTION],
            "<!-- task-memory -->" => $task[\meta\tasks::MEMORY],
            "<!-- task-time -->" => $task[\meta\tasks::TIME],
        ])->value();
    }

    /**
     * update task, return ID of updated task
     * @return string ID of updated task
     */
    private static function updateData()
    {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $data = [
            \meta\tasks::ID => filter_input(INPUT_POST, "task-id"),
            \meta\tasks::NAME => filter_input(INPUT_POST, "task-name"),
            \meta\tasks::DESCRIPTION => filter_input(INPUT_POST, "task-description"),
            \meta\tasks::MEMORY => filter_input(INPUT_POST, "task-memory"),
            \meta\tasks::TIME => filter_input(INPUT_POST, "task-time"),
        ];
        \mc\logger::stdout()->info("data prepared: " . json_encode($data));
        $crud->update($data);
        return $data[\meta\tasks::ID];
    }

    /**
     * create new task.
     */
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
        return $crud->insert($data);
    }

    /**
     * create new folder for task.
     */
    private static function createStructure(string $task_id)
    {
        mkdir(self::getTaskPath($task_id));
        mkdir(self::getTestTaskPath($task_id));
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
        $from = empty($params[0]) ? 0 : (int)$params[0];
        $offset = empty($params[1]) ? 20 : (int)$params[1];

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
                "<!-- id -->" => $task[\meta\tasks::ID],
            ])->value();
        }
        return (new \mc\template(
            file_get_contents(self::templates_dir . "tasklist.tpl.php")
        ))->fill([
            "<!-- tasklist element -->" => $list
        ])->value();
    }

    /**
     * remove task by ID then redirects to tasks list.
     * @param array $params - first element contains task ID
     */
    public static function remove(array $params)
    {
        $id = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $crud->delete($id);
        header("location:/?q=task/list");
        return "";
    }

    /**
     * view a task by ID.
     * @param array $params - first element contains task ID
     */
    public static function view(array $params)
    {
        $id = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $task = $crud->select($id);
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "task.view.tpl.php")
        );
        return $tpl->fill([
            "<!-- task-name -->" => $task[\meta\tasks::NAME],
            "<!-- task-description -->" => $task[\meta\tasks::DESCRIPTION],
            "<!-- task-time -->" => $task[\meta\tasks::TIME],
            "<!-- task-memory -->" => $task[\meta\tasks::MEMORY],
        ])->value();
    }

    /**
     * Show form for test uploading
     * @param array $params
     */
    public static function tests(array $params)
    {
        $id = empty($params[0]) ? -1 : (int)$params[0];

        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "task.tests.upload.tpl.php")
        );
        return $tpl->fill([
            "<!-- task-id -->" => $id,
        ])->value();
    }

    public static function export(array $params)
    {
        $taskId = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        $task = $crud->select($taskId);

        $fileName = "task_{$taskId}.zip";
        $filePath = self::getTaskPath($taskId) . $fileName;
        $za = new ZipArchive;
        $za->open($filePath, ZipArchive::CREATE);
        $za->addFromString("task.json", json_encode($task));
        $za->close();

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Content-Length: " . filesize($filePath));

        readfile($filePath);
        exit();
    }

    public static function import(array $params)
    {
        $taskId = empty($params[0]) ? -1 : (int)$params[0];
        return $taskId;
    }
}
