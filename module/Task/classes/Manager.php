<?php

namespace Task;

use config;
use mc\template;
use ZipArchive;

class Manager
{
    public const dir = \config::module_dir . "/task/";
    public const templates_dir = self::dir . "/templates/";

    private static function getTaskPath(string $taskId)
    {
        return \config::tasks_dir . "{$taskId}/";
    }

    private static function getTestTaskPath(string $taskId)
    {
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
            $taskId = self::insertData();
            self::createStructure($taskId);
            header("location:/?q=task/update/{$taskId}");
            return "";
        }

        return file_get_contents(self::templates_dir . "task.create.tpl.php");
    }

    public static function update(array $params)
    {
        if (isset($_POST["update-task"])) {
            $taskId = filter_input(INPUT_POST, "task-id");
            self::updateData();
            header("location:/?q=task/update/{$taskId}");
            return "";
        }
        $taskId = empty($params[0]) ? 0 : (int)$params[0];
        $task = self::get($taskId);
        $tpl = new template(
            file_get_contents(self::templates_dir . "task.update.tpl.php")
        );

        return $tpl->fill([
            "<!-- task-id -->" => $task[\meta\tasks::ID],
            "<!-- task-name -->" => $task[\meta\tasks::NAME],
            "<!-- task-description -->" => $task[\meta\tasks::DESCRIPTION],
            "<!-- task-memory -->" => $task[\meta\tasks::MEMORY],
            "<!-- task-time -->" => $task[\meta\tasks::TIME],
            "<!-- task-tests -->" => self::getTaskTests($taskId),
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
    private static function createStructure(string $taskId)
    {
        mkdir(self::getTaskPath($taskId));
        mkdir(self::getTestTaskPath($taskId));
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
        
        $db->delete(\meta\tasks::__name__, [\meta\tasks::ID => $id]);
        // delete tests
        $db->delete(\meta\task_tests::__name__, [\meta\task_tests::TASK_ID => $id]);
        // delete files
        // TODO #: implement this
        header("location:/?q=task/list");
        return "";
    }

    /**
     * view a task by ID.
     * @param array $params - first element contains task ID
     */
    public static function view(array $params)
    {
        $taskId = empty($params[0]) ? -1 : (int)$params[0];
        $task = self::get($taskId);

        if (empty($task)) {
            return "";
        }

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
        $taskId = empty($params[0]) ? -1 : (int)$params[0];

        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "task.tests.upload.tpl.php")
        );
        return $tpl->fill([
            "<!-- task-id -->" => $taskId,
        ])->value();
    }

    public static function export(array $params)
    {
        $taskId = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(config::dsn);

        $fileName = "task_{$taskId}.zip";
        $filePath = self::getTaskPath($taskId) . $fileName;
        $testsDir = \mc\filesystem::implode(self::getTaskPath($taskId), "tests");
        
        // prepare task definition
        $task = $db->select(\meta\tasks::__name__, ['*'],  [\meta\tasks::ID => $taskId])[0];
        // prepare tests definition
        $task_tests = $db->select(\meta\task_tests::__name__, ['*'],  [\meta\task_tests::TASK_ID => $taskId]);

        $za = new ZipArchive;
        $za->open($filePath, ZipArchive::CREATE);
        $za->addFromString("task.json", json_encode($task));
        $za->addFromString("tests.json", json_encode($task_tests));

        // add tests
        $za->addEmptyDir("tests");
        foreach ($task_tests as $test) {
            $inputTestFile = \mc\filesystem::implode($testsDir, $test[\meta\task_tests::INPUT]);
            $outputTestFile = \mc\filesystem::implode($testsDir, $test[\meta\task_tests::OUTPUT]);
            $za->addFile($inputTestFile, "tests/" . $test[\meta\task_tests::INPUT]);
            $za->addFile($outputTestFile, "tests/" . $test[\meta\task_tests::OUTPUT]);
        }
        $za->close();

        header("Content-Type: application/zip");
        header("Content-Disposition: attachment; filename={$fileName}");
        header("Content-Length: " . filesize($filePath));

        readfile($filePath);
        exit();
    }

    public static function import(array $params)
    {
        if (isset($_POST["task-import"])) {
            self::importTask($_FILES['tests']['tmp_name']);
            header("location:/?q=task/list");
            exit();
        }

        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "task.import.tpl.php")
        );
        return $tpl->value();
    }

    public static function importTask($zip)
    {
        if(file_exists($zip) === false) {
            \mc\logger::stdout()->error("cant import task: file `{$zip}` does not exists");
            return -1;
        }
        $db = new \mc\sql\database(config::dsn);
        $za = new ZipArchive;
        
        $za->open($zip, ZipArchive::RDONLY);
        // task definition
        $taskDefinition = $za->getFromName("task.json");
        $task = (array)json_decode($taskDefinition);
        unset($task[\meta\tasks::ID]);
        
        \mc\logger::stdout()->info("data prepared: " . json_encode($task));
        $taskId = $db->insert(\meta\tasks::__name__, $task);
        
        // tests definition
        self::createStructure($taskId);
        $outDir = self::getTestTaskPath($taskId);
        $testsDefinition = $za->getFromName("tests.json");
        $tests = (array)json_decode($testsDefinition);
        foreach ($tests as $test) {
            $test = (array)$test;
            unset($test[\meta\task_tests::ID]);
            $test[\meta\task_tests::TASK_ID] = $taskId;
            $db->insert(\meta\task_tests::__name__, $test);
            file_put_contents($outDir . $test["input"], $za->getFromName("tests/" . $test["input"]));
            file_put_contents($outDir . $test["output"], $za->getFromName("tests/" . $test["output"]));
        }

        // extract tests
        self::createStructure($taskId);


        $za->close();
        return $taskId;
    }

    protected static function getTaskTests($taskId) {
        $db = new \mc\sql\database(config::dsn);
        $tests = $db->select(
            \meta\task_tests::__name__,
            ["*"],
            [\meta\task_tests::TASK_ID => $taskId]);

        $result = "";
        foreach ($tests as $test) {
            $input = $test[\meta\task_tests::INPUT];
            $output = $test[\meta\task_tests::OUTPUT];
            $label = $test[\meta\task_tests::LABEL];
            $points = $test[\meta\task_tests::POINTS];
            $result .= "<tr><td>{$label}</td><td>{$input}</td><td>{$output}</td><td>{$points}</td></tr>\n";
        }
        return $result;
    }

    public static function get($taskId) {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\tasks::__name__);
        return $crud->select($taskId);
    }
}
