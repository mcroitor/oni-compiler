<?php

class config {
    public const root_dir = __DIR__;
    public const core_dir = __DIR__ . "/core/";
    public const module_dir = __DIR__ . "/module/";
    public const data_dir = __DIR__ . "/database/";
    public const templates_dir = __DIR__ . "/templates/";
    public const styles_dir = __DIR__ . "/styles/";
    public const contests_dir = self::root_dir . "/contests/";
    
    public const salt = "unpredictable_salt_value";
    
    public const dsn = "sqlite:" . self::data_dir . "/database.sqlite";

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
            include_once (self::core_dir . "{$module}.php");
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
