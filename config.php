<?php

class config {
    public const items_per_page = 20;
    
    public const root_dir = __DIR__;
    public const core_dir = __DIR__ . "/core/";
    public const module_dir = __DIR__ . "/module/";
    public const database_dir = __DIR__ . "/data/";
    public const data_dir = __DIR__ . "/data/";
    public const templates_dir = __DIR__ . "/templates/";
    public const styles_dir = __DIR__ . "/styles/";
    public const contests_dir = self::data_dir . "/contests/";
    public const tasks_dir = self::data_dir . "/tasks/";
    public const tmp_dir = self::data_dir . "/tmp/";
    
    public const salt = "unpredictable_salt_value";
    
    public const dsn = "sqlite:" . self::database_dir . "/database.sqlite";

    private const CORE = [
        "mc/classifier",
        "mc/crud",
        "mc/database",
        "mc/filesystem",
        "mc/logger",
        "mc/router",
        "mc/template",
// meta data
        "meta/capabilities",
        "meta/contest_tasks",
        "meta/contestants",
        "meta/contests",
        "meta/role_capabilities",
        "meta/roles",
        "meta/solutions",
        "meta/tasks",
        "meta/task_tests",
        "meta/users",
// html
        "html/style",
        "html/buildable",
        "html/text",
        "html/a",
        "html/html_element",
        "html/page",
        "html/builder",
        "html/form",
        "html/input",
        "html/label",
        "html/option",
        "html/widget/nav",
// other
        "Contest",
        "ContestTable",
        "Participant",
        "ParticipantResult",
        "Profile",
        "Task",
        "TaskResult",
        "Test",
// renders
        "/../renders/Common",
        "/../renders/admin/Contest",
        "/../renders/admin/Task",
    ];

    public static function core() {
        foreach (self::CORE as $module) {
            include_once self::core_dir . "{$module}.php";
        }
    }

    public static function load_modules() {
        $db = new mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, "modules");
        $modules = $crud->all();
        foreach ($modules as $module) {
            $module_name = $module["name"];
            include_once config::module_dir . "/{$module_name}/index.php";
        }
    }
}

config::core();
