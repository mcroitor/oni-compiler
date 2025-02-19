<?php

class config
{
    public const items_per_page = 20;

    public const DS = DIRECTORY_SEPARATOR;

    public const root_dir = __DIR__;
    public const core_dir = self::root_dir . self::DS . "core";
    public const module_dir = self::root_dir . self::DS . "module";
    public const database_dir = self::root_dir . self::DS . ".." . self::DS . "data";
    public const data_dir = self::root_dir . self::DS . ".." . self::DS . "data";
    public const contests_dir = self::data_dir . self::DS . "contests";
    public const tasks_dir = self::data_dir . self::DS . "tasks";
    public const tmp_dir = self::data_dir . self::DS . "tmp";

    public const languages_config = self::root_dir . self::DS . "config.json";

    public const dsn = "sqlite:" . self::database_dir . self::DS . "database.sqlite";
    public static $db = null;

    private const CORE = [
        "mc/classifier",
        "mc/crud",
        "mc/database",
        "mc/filesystem",
        "mc/logger",
        "mc/router",
        "mc/template",
        // meta data
        "meta/contest_tasks",
        "meta/contestants",
        "meta/contests",
        "meta/solutions",
        "meta/tasks",
        "meta/task_tests",
    ];

    public static function core()
    {
        foreach (self::CORE as $module) {
            include_once self::core_dir . self::DS . "{$module}.php";
        }
        self::$db = new \mc\sql\database(self::dsn);
    }
}

config::core();
